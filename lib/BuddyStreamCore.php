<?php

/*
 * Load the extentions
 */

BuddyStreamExtentions::loadExtentions();


/**
 * Add BuddyStream core stylesheets and javascripts
 *
 */

wp_register_script('buddystream-colorbox', plugins_url() . '/buddystream/extentions/default/colorbox/jquery.colorbox-min.js', array('jquery'), '1.3.18');
wp_enqueue_script('buddystream-colorbox');

wp_register_style('buddystream-colorbox', plugins_url() . '/buddystream/extentions/default/colorbox/colorbox.css', false, '1.3.18', 'screen');
wp_enqueue_style('buddystream-colorbox');

wp_register_style('buddystream-default', plugins_url() . '/buddystream/extentions/default/style.css', false, '2.5', 'screen');
wp_enqueue_style('buddystream-default');

wp_register_script('buddystream', plugins_url() . '/buddystream/extentions/default/main.js');
wp_enqueue_script('buddystream');


/**
 * Create a new activity item with the BuddyStream container
 */

function buddystreamCreateActivity($params){
    
    global $bp, $wpdb;
    
    //    buddystreamCreateActivity(array(
    //         'user_id'       => $user_meta->user_id,
    //         'extention'     => 'facebook',
    //         'type'          => 'photo',
    //         'content'       => $content,
    //         'item_id'       => $item['id'],
    //         'raw_date'      => $item['created_time'],
    //         'actionlink'    => ' http://www.facebook.com/profile.php?id=' .get_user_meta($user_meta->user_id, 'facestream_user_id',1)
    //        )
    //     );
    
    if(is_array($params)){
    
        //load config of extention
        $originalText = $params['content'];
        foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extention){
            if(isset($extention['hashtag'])){
                $originalText = str_replace($extention['hashtag'],"",$originalText);
                $originalText = str_replace("&nbsp;"," ",$originalText);
                $originalText = trim($originalText);
            }
        }
        
        //check if the secondary_id already exists
        $secondary = $wpdb->get_row($wpdb->prepare("SELECT secondary_item_id FROM {$bp->activity->table_name} WHERE secondary_item_id='".$params['item_id']."'"));
        
        //do we already have this content if so do not import this item
        if($secondary == null){
        
            $activity = new BP_Activity_Activity();
            if(!$activity->check_exists_by_content($originalText)){
                remove_filter('bp_activity_action_before_save', 'bp_activity_filter_kses', 1);

                $activity->user_id           = $params['user_id'];
                $activity->component         = $params['extention'];
                $activity->type              = $params['extention'];
                $activity->content           = '<div class="buddystream_activity_container '.$params['extention'].'">'.$originalText.'</div>';
                $activity->secondary_item_id = $params['item_id'];
                $activity->date_recorded     = $params['raw_date'];

                if (!defined('BP_ENABLE_ROOT_PROFILES')) { $slug = BP_MEMBERS_SLUG; }

                if (get_site_option('buddystream_'.$params['extention'].'_hide_sitewide') == "on") {
                    $activity->hide_sitewide = 1;
                } else {
                    $activity->hide_sitewide = 0;
                }

                $activity->action .= '<a href="'.$bp->root_domain.'/'.$slug.'/'. bp_core_get_username($params['user_id']).'/" title="'.bp_core_get_username($params['user_id']).'">'.bp_core_get_user_displayname($params['user_id']).'</a>';
                $activity->action .= ' '.__('posted&nbsp;a', 'buddystream_'.$extention['name'])." ";
                $activity->action .= '<a href="'.$params['actionlink'].'" target="_blank" rel="external"> '.__($params['type'], 'buddystream_'.$extention['name']);
                $activity->action .= '</a>: ';

                remove_filter('bp_activity_action_before_save', 'bp_activity_filter_kses', 1);

                //extra check to be sure we don't have a empty activity
                $cleanContent = '';
                $cleanContent = trim(strip_tags($activity->content));
                
                //check if item does not exist in the blacklist
                if(get_user_meta($params['user_id'], 'buddystream_blacklist_ids',2) && !empty($cleanContent)){
                    if (!preg_match("/".$params['item_id']."/i", get_user_meta($params['user_id'], 'buddystream_blacklist_ids',1))) {
                        $activity->save();  
                        BuddyStreamFilters::updateDayLimitByOne($params['extention'],$params['user_id']);
                    }
                }else{
                    $activity->save();
                    BuddyStreamFilters::updateDayLimitByOne($params['extention'],$params['user_id']);
                }
            }
        }
    }
}

/*
 * On delete activity item add it to the import blackplist
 *
 */

add_action('bp_activity_action_delete_activity', 'buddystream_delete_activity',2,999);
function buddystream_delete_activity($activity_id,$user_id)
{
    //get the activity information to see if it is a imported one
    $activity = new BP_Activity_Activity( $activity_id );
    
    //loop the extentions to detirmine what items to hide (blacklist for import)
    foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extension){
        if($activity->type == $extension['name']){
            $blacklist_ids = get_user_meta($user_id, 'buddystream_blacklist_ids',1);
           if($blacklist_ids){
               $blacklist_ids .= ",".$activity->secondary_item_id;
           }else{
               $blacklist_ids = $activity->secondary_item_id;
           }
           update_user_meta($user_id,'buddystream_blacklist_ids', $blacklist_ids);
        }
    }
}


/**
 * Admin notices if networks are not yet configured or a update is there.
 * 
 */

function buddystream_admin_notice(){
  
        foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extention){
            if(get_site_option('buddystream_'.$extention['name'].'_power') == "on" && !get_site_option('buddystream_'.$extention['name'].'_setup')){
                $buddystreamNotices[] = '<li>- <strong>'.ucfirst($extention['displayname'])."</strong> ".__('is not yet configured.','buddystream_lang').'</li>';                     
            }
        }
        
        if(!empty($buddystreamNotices)){
              echo '<div class="updated"><ul>';
              
              foreach ($buddystreamNotices as $buddystreamNotice){
                  echo $buddystreamNotice;
              }
              
              echo'</ul></p></div>';
        }
    
    
}
add_action('admin_notices', 'buddystream_admin_notice');

/**
 * Check for an update 
 */
function buddyStreamCheckUpdate(){
    
    $versionInfo = BuddyStreamCurl::getJsonDecodedContent('http://buddystream.net/cronservice/version.php');
    
    if($versionInfo->version > BP_BUDDYSTREAM_VERSION){
         echo '<div class="error"><ul>
                  <li><strong>'.__('There is a new version of BuddyStream available login and download it on the BuddyStream website.','buddystream_lang').'</strong><br/>
                       '.__('Currently installed:','buddystream_lang') . ' ' . BP_BUDDYSTREAM_VERSION . ' | ' . __('Available: '.$versionInfo->version.'','buddystream_lang').'
                    </li>
                </div>';
    }
    
}

add_action('admin_notices', 'buddyStreamCheckUpdate');

/**
 * Get response from the BuddyStream License Server
 * 
 */
function buddystreamCheckLicense($licenseKey = null) {
    
    global $bp;
    
    require_once (ABSPATH . WPINC . '/class-feed.php');
    if($licenseKey != null){
    
        $url   = "http://buddystream.net/cronservice/check.php?licensekey="
             .$licenseKey
             ."&domain=".str_replace("http://","",$bp->root_domain)
             ."&output=rss"
             ."&validate=".md5(date('Ymd'));
    
        $feed = new SimplePie();
        $feed->set_feed_url($url);
	$feed->set_cache_class('WP_Feed_Cache');
	$feed->set_file_class('WP_SimplePie_File');
	$feed->set_cache_duration(apply_filters('wp_feed_cache_transient_lifetime', 0, $url));
	$feed->init();
	$feed->handle_content_type();
    
        $responseItems = fetch_feed($url);
        $responseItems->set_cache_duration(0);
        foreach ($feed->get_items() as $responseItem) {
            $response = $responseItem->get_description();
        }

        return json_decode($response);   
    }else{
        return false;
    }
}


/**
 * Write a javascript var to the header, we need this for the counter that has to be dynamic
 */
add_action('wp_head', 'buddystreamWriteHashtagsToHeader');
function buddystreamWriteHashtagsToHeader(){
    echo "<script> var buddystreamNetworks = '#twitter,#facebook,#facebookpage,#linkedin'; </script>";
    
}


/**
 * Send the message to the network
 */

add_action('bp_activity_content_before_save', 'buddystream_socialIt',1);

function buddystream_SocialIt($content, $shortLink = null)
{
    
    
    global $bp; 
    $user_id = $bp->loggedin_user->id;
    
    /*
     * Original text, filter out all hashtags of extentions
     */
    
    $originalText = BuddyStreamFilters::removeHashTags($content);
   
   /*
    * Check if it is a forum post/topic/reply/group
    */
    
    if ($bp->current_component == "groups" && $bp->current_action == "forum") {

        if($_POST['topic_title']){
            
            $cleanTitle = trim($_POST['topic_title']);
            $shortLink = bp_get_group_permalink($bp->groups->current_group) .
                        'forum/topic/' .
                         str_replace(
                             " ",
                             "-",
                             strtolower($cleanTitle)
                         ) . '/';

            $shortLink = buddystream_getShortUrl($shortLink);
            $content   = __('Just created a new topic:', 'buddystream') ." " . $_POST['topic_title']." - " .$_POST['topic_text']." ";
            
        }else{
            $shortLink = bp_get_group_permalink($bp->groups->current_group). 'forum/topic/' . $bp->action_variables[1] . '/';
            $shortLink = buddystream_getShortUrl($shortLink);
            $content   = __('Just responded to:', 'buddystream') . " " . str_replace("-", " ", $bp->action_variables[1]);

           foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extention){
                if($extention['hashtag']){
                    if (preg_match("/".$extention['hashtag']."/", $_POST['reply_text'])) {
                         $content .= $extention['hashtag'];
                    }
                }
            }     
        }
      }

      /*
       * We have the content ready for the networks send it out!
       */
      
       $cleanContent = $content;
       $cleanContent = str_replace('&amp;','&',$cleanContent);
       
       //Activity Plus Stuff
       
       //find a link of Activity Plus, if found convert for nice social context
        $link = BuddyStreamFilters::extractString($cleanContent, '[bpfb_link', '[/bpfb_link]');
        if($link){
            $arrLink = explode(" ",trim($link));
            $newLink = str_replace("url=", "", $arrLink[0]);
            $newLink = str_replace("'", "", $newLink);
            $newLink = str_replace("[", "", $newLink);
            $newLink = str_replace("]", "", $newLink);
            
            $cleanContent = str_replace($link,"",$cleanContent);
            $cleanContent = str_replace("[bpfb_link","",$cleanContent);
            $cleanContent = str_replace("[/bpfb_link]","",$cleanContent);
            $cleanContent .= " ".$newLink;
        }
        
        //find a image of Activity Plus, if found convert for nice social context
        $image = BuddyStreamFilters::extractString($cleanContent, '[bpfb_images]', '[/bpfb_images]');
        if($image){
            $wpUpload = wp_upload_dir();
            $uploadUrl = $wpUpload['baseurl'];
            $newImage = $uploadUrl.'/bpfb/'.trim($image);
            
            $cleanContent =  str_replace($image,"",$cleanContent);
            $cleanContent =  str_replace("[bpfb_images]","",$cleanContent);
            $cleanContent =  str_replace("[/bpfb_images]","",$cleanContent);
            $cleanContent .= " ".$newImage;   
        }
        
       
       foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extention){
        
           $arrHastags = explode(",", $extention['hashtag']);
           foreach($arrHastags as $hashtag){
                if (get_site_option("buddystream_".$extention['name']."_power")) {
                    if (preg_match("/".$hashtag."/", $content)) {
                        if(function_exists("buddystream".ucfirst($extention['name'])."PostUpdate")){
                            call_user_func("buddystream".ucfirst($extention['name'])."PostUpdate", $cleanContent, $shortLink, $user_id);
                        }
                    }
                }
            }
        }
    
        return $originalText; 
 }
 

/**
 * Add sharing to update form
 */

add_action('bp_activity_post_form_options', 'buddystreamAddSharing');

if(get_site_option('buddystream_group_sharing') == 'on'){
    add_action('groups_forum_new_topic_after', 'buddystreamAddSharing');
    add_action('bp_after_group_forum_post_new', 'buddystreamAddSharing');
    add_action('groups_forum_new_reply_after', 'buddystreamAddSharing');
}

function buddystreamAddSharing()
{
    global $bp;

    foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extention){
        if(isset($extention['hashtag']) && $extention['hashtag']){
            if (get_site_option("buddystream_".$extention['name']."_power")) {
                if(function_exists("buddystream".ucfirst($extention['name'])."Sharing")){
                    call_user_func("buddystream".ucfirst($extention['name'])."Sharing");
                }
            }
        }
    }
    
     echo '<div class="buddystream_hoverbox"></div>';
    
}


/**
 * If set add Sharebox functionality
 */

if(get_site_option('buddystream_sharebox') == 'on'){
    
    //check if any social sharing is turned on else don't show the ShareBox
    $shareBox = false;
    foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extention){
       if(get_site_option("buddystream_".$extention['name']."_share") == "on"){
           $shareBox = true;
       }
    }
    
   if($shareBox){
       add_action('bp_activity_entry_meta','BuddystreamShareButton',9);
       add_action('bp_group_forum_topic_meta', 'BuddystreamShareButton',9);
   }
}

function BuddystreamShareButton() {  
    
    $shares = array();
    foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extention){
        if(get_site_option("buddystream_".$extention['name']."_share") == "on"){
            $shares[] = $extention['name'];
        }
    }
    $shares = implode(',',$shares);
    
    
     echo '<a href="' . get_site_url() . '/wp-content/plugins/buddystream/extentions/default/templates/ShareBox.php?content=' .  urlencode(strip_tags(bp_get_activity_content_body())) . '&link=' . urlencode(bp_get_activity_thread_permalink()) . '&shares=' . $shares . ' " class="bs_lightbox button item-button">Sharebox!</a>';
}


/*
 * Add activity filters
 */

add_action('bp_activity_filter_options', 'buddystreamAddFilter', 1);
add_action('bp_member_activity_filter_options', 'buddystreamAddFilter', 1);

function buddystreamAddFilter()
{
    foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extention){
          if (get_site_option("buddystream_".$extention['name']."_power")) {
            echo'<option value="'.$extention['name'].'">' .
              __('Show '.ucfirst($extention['displayname']), 'buddystream_'.$extention['name']) .
              '</option>';
        }
    }
}


/**
  * Remove all hashtags of extentions from returns
  */
 
add_action('bp_get_activity_latest_update','removetags', 9);
function removetags($content)
{
    return BuddyStreamFilters::removeHashTags($content);
}

add_filter('group_forum_topic_title_before_save', 'tweetstream_topic');
function tweetstream_topic() {
    return BuddyStreamFilters::removeHashTags($_POST['topic_title']);
    
}

add_filter('group_forum_post_text_before_save', 'buddystream_filtertags_fp', 9);

function buddystream_filtertags_fp() {
    $content = $_POST['reply_text'];
    if ($content == "") {
        $content = $_POST['post_text'];
    }

    return BuddyStreamFilters::removeHashTags($content);
}

