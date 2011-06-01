<?php
/**
 * This is the core file for BuddyStream where all the magic elfs come from.
 * 
 * NEVER EVER, AND I MEAN NEVER EDIT THIS FILE OR ALL THE MAGIC ELFS WILL GO AWAY
 *  
 */

define('BP_BUDDYSTREAM_VERSION', '2.0.5');
define('BP_BUDDYSTREAM_IS_INSTALLED', 1);

/**
 * Load the extentions into BuddyStream
 */

function buddystreamLoadExtentions(){

    $handle = opendir(WP_PLUGIN_DIR . "/buddystream/extentions");
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (file_exists(WP_PLUGIN_DIR."/buddystream/extentions/".$file."/core.php")) {
                    include(WP_PLUGIN_DIR."/buddystream/extentions/".$file."/core.php");
                    include(WP_PLUGIN_DIR."/buddystream/extentions/".$file."/".$file.".php");
                }
            }
        }
    }
}
buddystreamLoadExtentions();

/**
 * Load the extentions configs
 * @return array
 */
function buddystreamGetExtentions(){

    $handle = opendir(WP_PLUGIN_DIR . "/buddystream/extentions");
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (file_exists(WP_PLUGIN_DIR."/buddystream/extentions/".$file."/config.ini")) {
                    $config[] = parse_ini_file(WP_PLUGIN_DIR."/buddystream/extentions/".$file."/config.ini");
                }
            }
        }
    }

    return $config;
}

/**
 * Page loader for extentions
 */

function buddystreamPageLoader($extention){

     global $bp, $wpdb;
     $config = parse_ini_file(WP_PLUGIN_DIR."/buddystream/extentions/".$extention."/config.ini");

     if(!$_GET["settings"]){
         $page = ucfirst($config['defaultpage']);
     }else{
         $page = ucfirst($_GET["settings"]);
     }

     include WP_PLUGIN_DIR."/buddystream/extentions/".$extention."/templates/Admin".$page.".php";
}

/**
 * Userpage loader for extentions
 */

function buddystreamUserPageLoader($extention){

    global $bp;

     if ($bp->displayed_user->id != $bp->loggedin_user->id) {
            header('location:' . get_site_url());
     }

    add_action(
        'bp_template_title',
        'buddystream_'.$extention.'_settings_screen_title'
    );

    add_action(
        'bp_template_content',
        'buddystream_'.$extention.'_settings_screen_content'
    );

    bp_core_load_template(
        apply_filters(
            'bp_core_template_plugin',
            'members/single/plugins'
        )
    );
}

/**
 * Tabs loader for extentions
 */

function buddystreamTabLoader($extention){
    $tabs .= '<div class="buddystream_Adminmenu">';
     if (file_exists(WP_PLUGIN_DIR."/buddystream/extentions/".$extention."/config.ini")) {

        $config = parse_ini_file(WP_PLUGIN_DIR."/buddystream/extentions/".$extention."/config.ini");
        $arrTabs = explode(",", $config['pages']);

        foreach($arrTabs as $tab){
            $tab = trim($tab);
            $class = "";

            if($_GET['settings'] == $tab){
                $class = 'class="activetab"';
            }elseif(!$_GET['settings']){
                if($config['defaultpage'] == $tab){
                    $class = 'class="activetab"';
                }
            }
            $tabs.= '<a href="?page=buddystream_'.$extention.'&settings='.$tab.'" '.$class.'>'.__(ucfirst($tab),'buddystream_lang').'</a>';
        }   
     }
     
         $tabs.= '<a href="?page=buddystream_admin&settings=version2" class="tab_v2">V2</a>';
         $tabs.= '<span class="tab_description"><span id="tab_description_content"></span></span>';

    $tabs .='</div>';

    return $tabs;
}

/**
 * Setup user navigation for the extentions
 *
 */

function buddystream_setup_nav()
{
    global $bp;

    if(!buddystreamCheckRequirements()){
        foreach(buddystreamGetExtentions() as $extention){
            if (get_site_option("buddystream_".$extention['name']."_power")) {
                
                    bp_core_new_subnav_item(
                        array(
                            'name' => __(ucfirst($extention['name']), 'buddystream_'.$extention['name']),
                            'slug' => 'buddystream-'.$extention['name'],
                            'parent_url' => $bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/',
                            'parent_slug' => BP_SETTINGS_SLUG,
                            'screen_function' => 'buddystream_'.$extention['name'].'_user_settings',
                            'position' => 10,
                            'user_has_access' => bp_is_my_profile ()
                            )
                    );
            }
        }
    }
}
buddystream_setup_nav();

/**
 * Setup admin navigation for the extentions
 *
 */

add_action('admin_menu', 'buddystreamAdmin');
add_action('network_admin_menu', 'buddystreamAdmin');

function buddystreamAdmin() {

    if (!is_super_admin()) {
		return false;
    }

    /**
     * Load the BuddyStream menu into the admin
     */

    bp_core_add_admin_menu_page(
          array(
              'menu_title' => __('BuddyStream', 'buddystream'),
              'page_title' => __('Info', 'buddystream'),
              'access_level' => 10,
              'position' => 100,
              'file' => 'buddystream_admin',
              'function' => 'buddystream_welcome',
              'icon_url' => plugins_url(
                  'images/buddystream_icon.png',
                  __FILE__
              )
          )
      );


    /**
     * Load the extentions into the BuddyStream admin menu.
     */

    if (!buddystreamCheckRequirements()) {

        foreach(buddystreamGetExtentions() as $extention){
            if (get_site_option("buddystream_".$extention['name']."_power")) {
                add_submenu_page(
                'buddystream_admin',
                __(ucfirst($extention['name']), 'buddystream_'.$extention['name']),
                __(ucfirst($extention['name']), 'buddystream_'.$extention['name']),
                'manage_options',
                'buddystream_'.$extention['name'],
                'buddystream_'.$extention['name']
                );
            }
        }
    }
}

/**
 * Add a link to the settings page of BuddyStream on the plugins page
 */

function buddystream_admin_add_settings_link( $links, $file ) {
	if ( 'buddystream/buddystream.php' != $file ) {
		return $links;
    } else {
        $settings_link = '<a href="' . admin_url( 'admin.php?page=buddystream_admin' ) . '">' . __( 'Settings', 'buddystream' ) . '</a>';
        array_unshift( $links, $settings_link );
    }

	return $links;
}
add_filter( 'plugin_action_links', 'buddystream_admin_add_settings_link', 10, 2 );


/**
 * Global BuddyStream pages
 *
 */

function buddystream_welcome()
{
     if ($_GET["settings"] == "") {
        include "extentions/default/templates/Dashboard.php";
     } else if ($_GET["settings"] == "cronjob") {
         include "extentions/default/templates/Cronjob.php";
     } else if ($_GET["settings"] == "powercentral") {
         include "extentions/default/templates/Powercentral.php";
     } else if ($_GET["settings"] == "log") {
         include "extentions/default/templates/Log.php";
     } else if ($_GET["settings"] == "version2") {
         include "extentions/default/templates/Version2.php";
     }
     
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
    
    $originalText = buddyStreamRemoveHashTags($content);
   
   /*
    * Check if it is a forum post/topic/reply/group
    */
    
    if ($bp->current_component == "groups" && $bp->current_action == "forum") {

        if($_POST['topic_title']){
            $shortLink = bp_get_group_permalink($bp->groups->current_group) .
                        'forum/topic/' .
                         str_replace(
                             " ",
                             "-",
                             strtolower(
                                 $_POST['topic_title']
                             )
                         ) . '/';

            $shortLink = buddystream_getShortUrl($shortLink);
            $content   = __('Just created a new topic:', 'buddystream') ." " . $_POST['topic_title'];
        }else{
            $shortLink = bp_get_group_permalink($bp->groups->current_group). 'forum/topic/' . $bp->action_variables[1] . '/';
            $shortLink = buddystream_getShortUrl($shortLink);
            $content   = __('Just responded to:', 'buddystream') . " " . str_replace("-", " ", $bp->action_variables[1]);

           foreach(buddystreamGetExtentions() as $extention){
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
      
       $cleanContent = buddyStreamRemoveHashTags($content);
       foreach(buddystreamGetExtentions() as $extention){
        if($extention['hashtag']){
            if (get_site_option("buddystream_".$extention['name']."_power")) {
                if (preg_match("/".$extention['hashtag']."/", $content)) {
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
  * Remove all hashtags of extentions from returns
  */
 
add_action('bp_get_activity_latest_update','removetags', 9);
function removetags($content)
{
    return buddyStreamRemoveHashTags($content);
}

add_filter('group_forum_topic_title_before_save', 'tweetstream_topic');
function tweetstream_topic() {
    return buddyStreamRemoveHashTags($_POST['topic_title']);
    
}

add_filter('group_forum_post_text_before_save', 'buddystream_filtertags_fp', 9);
function buddystream_filtertags_fp() {
	$content = $_POST['reply_text'];
	if ($content == "") {
            $content = $_POST['post_text'];
        }
        
        return buddyStreamRemoveHashTags($content);
}
 
function buddyStreamRemoveHashTags($input){
    foreach(buddystreamGetExtentions() as $extention){
        if($extention['hashtag']){
            $input = str_replace($extention['hashtag'],"",$input);
        }
     }
     return $input;
 }
 
/*
 * Add activity filters
 */

add_action('bp_activity_filter_options', 'buddystreamAddFilter', 1);
add_action('bp_member_activity_filter_options', 'buddystreamAddFilter', 1);

function buddystreamAddFilter()
{
    foreach(buddystreamGetExtentions() as $extention){
          if (get_site_option("buddystream_".$extention['name']."_power")) {
            echo'<option value="'.$extention['name'].'">' .
              __('Show '.ucfirst($extention['name']), 'buddystream_'.$extention['name']) .
              '</option>';
        }
    }
}


/**
 * Add sharing to update form
 */

add_action('bp_activity_post_form_options', 'buddystreamAddSharing');
add_action('groups_forum_new_topic_after', 'buddystreamAddSharing');
add_action('bp_after_group_forum_post_new', 'buddystreamAddSharing');
add_action('groups_forum_new_reply_after', 'buddystreamAddSharing');

function buddystreamAddSharing()
{
    global $bp;

    foreach(buddystreamGetExtentions() as $extention){
        if($extention['hashtag']){
            if (get_site_option("buddystream_".$extention['name']."_power")) {
                if(function_exists("buddystream".ucfirst($extention['name'])."Sharing")){
                    call_user_func("buddystream".ucfirst($extention['name'])."Sharing");
                }
            }
        }
    }
}

/**
 * URL Shorting
 */

add_action('wp', 'buddystream_resolveShortUrl',1);
function buddystream_getShortUrl($url)
{
   global $bp;

   if ($url) {
       
       $url   = str_replace("#", "",$url);
       $input = date('dmyhis');
       $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
       $base  = strlen($index);

       for ($t = floor(log($input, $base)); $t >= 0; $t--) {
           $bcp = pow($base, $t);
           $a = floor($input / $bcp) % $base;
           $out = $out . substr($index, $a, 1);
           $input = $input - ($a * $bcp);
       }
       $shortId = strrev($out);

       update_user_meta($bp->loggedin_user->id, 'buddystream_' . $shortId, $url);
       $url = get_site_url() . '/' . $shortId;

       return $url;
   } else {
       return false;
   }
}

add_action('wp', 'buddystream_resolveShortUrl',1);
function buddystream_resolveShortUrl($url)
{
   global $wpdb;
   if (is_404()) {
       $short_id = str_replace("/", "", $_SERVER ['REQUEST_URI']);
       if ($short_id) {

           $usermeta = $wpdb->get_row(
               "SELECT * FROM {$wpdb->usermeta}
                WHERE meta_key='buddystream_" .$short_id ."'"
           );

           $url = $usermeta->meta_value;
           if ($url) {
               header('location:' . $url);
           }
       }
   }
}

/**
 * Add log item to the BuddyStream log table
 */
function buddystreamLog($message = "", $type="info"){
    if($message){
        global $wpdb;
        
        $results = $wpdb->get_results("SELECT count(id) as count FROM ".$wpdb->prefix."buddystream_log");
        
        if($results[0]->count >= 1000){
            $wpdb->query("DELETE FROM ".$wpdb->prefix."buddystream_log ORDER BY id ASC LIMIT 1");            
        }
        $wpdb->insert($wpdb->prefix."buddystream_log", array('message' => $message , 'type' => $type));
    }
}
/**
 * Add BuddyStream core stylesheets and javascripts
 *
 */

wp_register_script('prettyphoto', plugins_url() . '/buddystream/extentions/default/pretty/js/jquery.prettyPhoto.js', array('jquery'), '2.5.6');
wp_enqueue_script('prettyphoto');
wp_register_style('prettyphoto', plugins_url() . '/buddystream/extentions/default/pretty/css/prettyPhoto.css', false, '2.5.6', 'screen');
wp_enqueue_style('prettyphoto');
wp_register_script('buddystream', plugins_url() . '/buddystream/extentions/default/main.js');
wp_enqueue_script('buddystream');

/**
 * On delete activity item add it to the import blackplist
 *
 */

add_action('bp_activity_action_delete_activity', 'buddystream_delete_activity',2,999);
function buddystream_delete_activity($activity_id,$user_id)
{
    //get the activity information to see if it is a imported one
    $activity = new BP_Activity_Activity( $activity_id );
    
    //loop the extentions to detirmine what items to hide (blacklist for import)
    foreach(buddystreamGetExtentions() as $extension){
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
 * Create a new activity item with the BuddyStream container
 */

function buddystreamCreateActivity($params){
    
    global $bp;
    
    //    buddystreamCreateActivity(array(
    //         'user_id'       => $user_meta->user_id,
    //         'extention'     => 'facebook',
    //         'content'       => $content,
    //         'item_id'       => $item['id'],
    //         'raw_date'      => $item['created_time'],
    //         'actionlink'    => ' http://www.facebook.com/profile.php?id=' .get_user_meta($user_meta->user_id, 'facestream_user_id',1)
    //        )
    //     );
    
    if(is_array($params)){
    
        //load config of extention
        $config = parse_ini_file(WP_PLUGIN_DIR."/buddystream/extentions/".$params['extention']."/config.ini");

        $originalText = $params['content'];
        foreach(buddystreamGetExtentions() as $extention){
            if($extention['hashtag']){
                $originalText = str_replace($extention['hashtag'],"",$originalText);
            }
        }
        
        $activity = new BP_Activity_Activity();
        $activity->user_id           = $params['user_id'];
        $activity->component         = $params['extention'];
        $activity->type              = $params['extention'];
        $activity->content           = '<div class="buddystream_activity_container">'.$originalText.'</div>';
        $activity->secondary_item_id = $params['item_id'];
        $activity->date_recorded     = $params['raw_date'];
        
        if (!defined('BP_ENABLE_ROOT_PROFILES')) { $slug = BP_MEMBERS_SLUG; }

        if (get_site_option('buddystream_'.$params['extention'].'_hide_sitewide') == "on") {
            $activity->hide_sitewide = 1;
        } else {
            $activity->hide_sitewide = 0;
        }

        $activity->action .= '<a href="'.$bp->root_domain.'/'.$slug.'/'. bp_core_get_username($params['user_id']).'/" title="'.bp_core_get_username($params['user_id']).'">'.bp_core_get_user_displayname($params['user_id']).'</a>';
        $activity->action .= '&nbsp;<img src="'.plugins_url().'/buddystream/extentions/'.$params['extention'].'/'.$config['icon'].'">&nbsp;'.__('posted&nbsp;a', 'buddystream_'.$extention['name'])."&nbsp;";
        $activity->action .= '<a href="'.$params['actionlink'].'" target="_blank" rel="external">&nbsp;'.__($config['type'], 'buddystream_'.$extention['name']);
        $activity->action .= '</a>:&nbsp;';

        //check if item does not exist in the blacklist
        if(get_user_meta($user_id, 'buddystream_blacklist_ids',2)){
            if (!preg_match("/".$params['item_id']."/i", get_user_meta($params['user_id'], 'buddystream_blacklist_ids',1))) {
                $activity->save();
                update_user_meta($params['user_id'], $params['extention'].'_daycounter', get_user_meta($params['user_id'], $params['extention'].'_daycounter',1) + 1);
            }
        }else{
            $activity->save();
            update_user_meta($params['user_id'], $params['extention'].'_daycounter', get_user_meta($params['user_id'], $params['extention'].'_daycounter',1) + 1);
        }
    }
}


/**
 * Get response from the BuddyStream License Server
 * 
 */

function buddystreamCheckLicense($licenseKey) {
    global $bp;
    
    if($licenseKey){
    
    $url   = "http://buddystream.net/cronservice/check.php?licensekey="
             .$licenseKey
             ."&domain=".str_replace("http://","",$bp->root_domain)
             ."&validate=".md5(date('Ymd'));
    
    $agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
    $ch    = curl_init();

    curl_setopt($ch, CURLOPT_URL,$url );
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_VERBOSE,false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $response     = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if($httpcode>=200 && $httpcode<300){
        
        $response = json_decode($response);
        return $response;
        
    } else {
        return false;
    }
    }else{
        return false;
    }
}


/**
 * Check if a network is up and running (used by import)
 * 
 */

function buddystreamCheckNetwork($url) {
     
    $agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
    $ch    = curl_init();

    curl_setopt($ch, CURLOPT_URL,$url );
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_VERBOSE,false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $page     = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if($httpcode>=200 && $httpcode<300){
        return true;
    } else {
        return false;
    }
}

/**
 * Check if the server is ready to rock and roll!
 *
 */

function buddystreamCheckRequirements() {

    $error = false;

    if (phpversion() < '5.2.11') {
        $error .= "You got a older version of PHP installed (PHP ".phpversion()."), please upgrade to minimal version 5.2.11.<br>";
    }

    if (!extension_loaded("json")) {
        $error .= "The plugin needs JSON to be installed.<br>";
    }

    if (!extension_loaded("curl")) {
        $error .= "The plugin needs CURL to be installed.<br>";
    }

    return $error;
}


/**
 * Support for other plugins
 */

if (defined('ACHIEVEMENTS_IS_INSTALLED')) {
    add_action('dpa_achievement_unlocked','buddystream_achievement_unlocked', 10, 2);
    function buddystream_achievement_unlocked($achievement_id,$user_id)
    {
        $content = __('I just unlocked the', 'buddystream_lang')." ".dpa_get_achievement_name()." ".__('achievement!', 'buddystream');

        if(get_user_meta($user_id, 'tweetstream_achievements',1)){
            $content = "#twitter ".$content;
        }

        if(get_user_meta($user_id, 'facestream_achievements',1)){
            $content = "#facebook ".$content;
        }

        $shortLink = buddystream_getShortUrl(dpa_get_achievement_slug_permalink());
        buddystream_SocialIt($content,$shortLink,$user_id);
    }
}


/*
 * Upgrading
 */

if(!get_site_option("bp_buddystream_upgrade")){
    include "upgrade.php";
    update_site_option("bp_buddystream_upgrade","1.0.2.3");
}

//convert all old tweets to twitter types
if(get_site_option("buddystream_upgrade") != "2.0"){
    global $bp,$wpdb;
    
    $wpdb->query("UPDATE ".$bp->activity->table_name." SET type='twitter', component='twitter' where type='tweet';");
    $wpdb->query("UPDATE ".$bp->activity->table_name." SET type='flickr', component='flickr' where type='tweet';");
    $wpdb->query("UPDATE ".$bp->activity->table_name." SET type='twitter', component='twitter' where type='tweet';");
    $wpdb->query("UPDATE ".$bp->activity->table_name." SET type='lastfm', component='lastfm' where type='tweet';");
    $wpdb->query("UPDATE ".$bp->activity->table_name." SET type='youtube', component='youtube' where type='tweet';");
    update_site_option("buddystream_upgrade","2.0");    
}
