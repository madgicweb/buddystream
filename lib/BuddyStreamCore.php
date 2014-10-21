<?php

/*
 * Load the extensions
 */

$buddyStreamExtensions = new BuddyStreamExtensions();
$buddyStreamExtensions->loadExtensions();


/**
 * Add BuddyStream core stylesheets and javascript
 *
 */
if( ! is_admin()){


    if( ! get_site_option('buddystream_nobuddybox')) {
        wp_register_script('buddystream-buddybox',
            BP_BUDDYSTREAM_URL.'/extensions/default/buddybox/jquery.buddybox.js', array('jquery'), '1.3.18');
        wp_enqueue_script('buddystream-buddybox');

        wp_register_style('buddystream-buddybox',
            BP_BUDDYSTREAM_URL.'/extensions/default/buddybox/buddybox.css', false, '1.3.18', 'screen');
        wp_enqueue_style('buddystream-buddybox');
    }

    if( ! get_site_option('buddystream_nocss')) {
        wp_register_style('buddystream-default',
            BP_BUDDYSTREAM_URL.'/extensions/default/style.css', false, BP_BUDDYSTREAM_VERSION, 'screen');
        wp_enqueue_style('buddystream-default');

        wp_register_style('buddystream-bootstrap',
            BP_BUDDYSTREAM_URL.'/bootstrap/css/bootstrap.css', false, BP_BUDDYSTREAM_VERSION, 'screen');
        wp_enqueue_style('buddystream-bootstrap');
    }

    wp_register_script('buddystream-bootstrap', BP_BUDDYSTREAM_URL . '/bootstrap/js/bootstrap.js');

    wp_register_script('buddystream', BP_BUDDYSTREAM_URL . '/extensions/default/main.js');
    wp_enqueue_script('buddystream');

    wp_register_script("googlemaps","https://maps.googleapis.com/maps/api/js?sensor=false");
    wp_enqueue_script('googlemaps');

}

/**
 * Create a activity item
 * @param $params
 * @return string
 */
function buddystreamCreateActivity($params){

    global $bp, $wpdb;

    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamFilters = new BuddyStreamFilters();

    /**
     * buddystreamCreateActivity(array(
     *     'user_id'    => $user_meta->user_id,
     *     'extension'  => 'facebook',
     *     'type'       => 'photo',
     *     'content'    => $content,
     *     'item_id'    => $item['id'],
     *     'raw_date'   => $item['created_time'],
     *     'actionlink' => 'url_to_original_item')
     *  ));
     *
     */

    if(is_array($params)){

        //load config of extension
        $originalText = $params['content'];
        foreach($buddyStreamExtensions->getExtensionsConfigs() as $extension){
            if(isset($extension['hashtag'])){
                $originalText = str_replace($extension['hashtag'], "", $originalText);
                $originalText = trim($originalText);
            }
        }

        //set the content
        $content = "";
        $content = '<div class="buddystream_activity_container ' . $params['extension'] . '">' . $originalText . '</div>';

        if( ! buddyStreamCheckImportLog($params['user_id'],$params['item_id'],$params['extension']) && ! buddyStreamCheckExistingContent($content) && ! buddyStreamCheckExistingContent($originalText)){

            buddyStreamAddToImportLog($params['user_id'],$params['item_id'],$params['extension']);

            remove_filter('bp_activity_action_before_save', 'bp_activity_filter_kses', 1);

            $activity = new BP_Activity_Activity();
            $activity->user_id           = $params['user_id'];
            $activity->component         = $params['extension'];
            $activity->type              = $params['extension'];
            $activity->content           = $content;
            $activity->secondary_item_id = '';
            $activity->date_recorded     = $params['raw_date'];
            $activity->hide_sitewide     = 0;
            $activity->action .= '<a href="' . bp_core_get_user_domain($params['user_id']) .'" title="' . bp_core_get_username($params['user_id']).'">'.bp_core_get_user_displayname($params['user_id']).'</a>';
            $activity->action .= ' ' . __('posted&nbsp;a', 'buddystream_' . $extension['name']).' ';
            $activity->action .= '<a href="' . $params['actionlink'] . '" target="_blank" rel="external"> '.__($params['type'], 'buddystream_'.$extension['name']);
            $activity->action .= '</a>: ';

            if ( ! preg_match("/" . $params['item_id'] . "/i", get_user_meta($params['user_id'], 'buddystream_blacklist_ids', 1))) {

                $activity->save();
                $buddyStreamFilters->updateDayLimitByOne($params['extension'], $params['user_id']);


                return true;
            }
        }
    }

    return false;
}


/**
 * Add item to import log
 * @param $user_id
 * @param $id
 * @param $component
 */
function buddyStreamAddToImportLog($user_id, $id, $component){
    global $wpdb;

    $item_id = $user_id."-".$id."-".$component;
    $wpdb->query("INSERT INTO ".$wpdb->base_prefix."buddystream_imports set item_id='".$item_id."'");
}

/**
 * Check if item already exist in import log
 * @param $user_id
 * @param $id
 * @param $component
 * @return bool
 */
function buddyStreamCheckImportLog($user_id, $id, $component){

    global $wpdb;

    $item_id = $user_id."-".$id."-".$component;

    if( $wpdb->get_row("SELECT * FROM ".$wpdb->base_prefix."buddystream_imports WHERE item_id ='" . $item_id . "'")){
        return true;
    }

    $item_id = $id;

    if( $wpdb->get_row("SELECT * FROM ".$wpdb->base_prefix."buddystream_imports WHERE item_id ='" . $item_id . "'")){
        return true;
    }

    return false;
}

/**
 * Check if item already exists as content
 * @param $content
 * @return bool
 */
function buddyStreamCheckExistingContent($content){

    global $wpdb, $bp;

    if($wpdb->get_row("SELECT content FROM {$bp->activity->table_name} WHERE content='" . $content . "'")){
        return true;
    }

    return false;
}

/**
 * Admin notices if networks are not yet configured or a update is there.
 */
function buddystream_admin_notice(){

    $buddyStreamExtensions = new BuddyStreamExtensions();

    foreach($buddyStreamExtensions->getExtensionsConfigs() as $extension){
        if(get_site_option('buddystream_'.$extension['name'].'_power') == "on" && !get_site_option('buddystream_'.$extension['name'].'_setup')){
            $buddystreamNotices[] = '<li>- <strong>'.ucfirst($extension['displayname'])."</strong> ".__('is not yet configured.','buddystream_lang').'</li>';
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

    define('BP_BUDDYSTREAM_VERSION','3.2.5');

    $buddyStreamCurl = new BuddyStreamCurl();

    $versionInfo = $buddyStreamCurl->getJsonDecodedContent('http://buddystream.net/cronservice/version.php');

    if($versionInfo->version > BP_BUDDYSTREAM_VERSION){
        echo '<div class="error"><ul>
                  <li><strong>'.__('There is a new version of BuddyStream available login and download it on the BuddyStream website.', 'buddystream_lang').'</strong><br/>
                       ' . __('Currently installed:','buddystream_lang') . ' ' . BP_BUDDYSTREAM_VERSION . ' | ' . __('Available: '.$versionInfo->version.'','buddystream_lang').'
                    </li>
                </div>';
    }

}

add_action('admin_notices', 'buddyStreamCheckUpdate');

/**
 * Get response from the BuddyStream License Server
 * @param null $licenseKey
 * @return array|bool|mixed
 */
function buddystreamCheckLicense($licenseKey = null) {

    global $bp;

    require_once (ABSPATH . WPINC . '/class-feed.php');

    if($licenseKey != null){

        $url   = "http://buddystream.net/cronservice/check.php?licensekey="
            . $licenseKey
            . "&domain=" . str_replace("http://", "", $bp->root_domain)
            . "&contenturl=" . WP_CONTENT_URL
            . "&importurl=" .BP_BUDDYSTREAM_URL."import.php"
            . "&output=rss"
            . "&validate=" . md5(date('Ymd'));

        $responseItems = fetch_feed($url);
        if( ! is_wp_error( $responseItems ) ) {
            foreach ($responseItems->get_items() as $responseItem) {
                $response = $responseItem->get_description();
            }
        }

        return json_decode($response);
    }

    return false;
}

/**
 * Write a javascript var to the header, we need this for the counter that has to be dynamic
 */
add_action('wp_head', 'buddystreamWriteHashtagsToHeader');
function buddystreamWriteHashtagsToHeader(){
    echo "<script> var buddystreamNetworks = '#twitter,#facebook,#facebookpage,#linkedin,#foursquare,#location,#tumblr;' </script>";

}

/**
 * Send the message to the network
 */
add_action('bp_activity_content_before_save', 'buddystream_socialIt', 1);

function buddystream_SocialIt($content, $shortLink = null)
{

    global $bp;

    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamFilters = new BuddyStreamFilters();

    $user_id = $bp->loggedin_user->id;
    $originalText = $buddyStreamFilters->removeHashTags($content);

    if ($bp->current_component == "groups" && $bp->current_action == "forum") {

        if($_POST['topic_title']){

            $cleanTitle = trim($_POST['topic_title']);
            $shortLink  = bp_get_group_permalink($bp->groups->current_group) .
                'forum/topic/' .
                str_replace(
                    " ",
                    "-",
                    strtolower($cleanTitle)
                ) . '/';

            $shortLink = buddystream_getShortUrl($shortLink);
            $content   = __('Just created a new topic:', 'buddystream') . " " . $_POST['topic_title'] . " - " .$_POST['topic_text']." ";

        }else{
            $shortLink = bp_get_group_permalink($bp->groups->current_group). 'forum/topic/' . $bp->action_variables[1] . '/';
            $shortLink = buddystream_getShortUrl($shortLink);
            $content   = __('Just responded to:', 'buddystream') . " " . str_replace("-", " ", $bp->action_variables[1]);

            foreach($buddyStreamExtensions->getExtensionsConfigs() as $extension){
                if($extension['hashtag']){
                    if (strpos($_POST['reply_text'], $extension['hashtag'])) {
                        $content .= $extension['hashtag'];
                    }
                }
            }
        }
    }

    $cleanContent = $content;
    $cleanContent = str_replace('&amp;', '&', $cleanContent);

    //Activity Plus Stuff
    //find a link of Activity Plus, if found convert for nice social context
    $link = $buddyStreamFilters->extractString($cleanContent, '[bpfb_link', '[/bpfb_link]');

    if($link){
        $arrLink = explode(" ",trim($link));
        $newLink = str_replace("url=", "", $arrLink[0]);
        $newLink = str_replace("'", "", $newLink);
        $newLink = str_replace("[", "", $newLink);
        $newLink = str_replace("]", "", $newLink);

        $cleanContent = str_replace($link, "", $cleanContent);
        $cleanContent = str_replace("[bpfb_link", "", $cleanContent);
        $cleanContent = str_replace("[/bpfb_link]", "", $cleanContent);
        $cleanContent .= " " . $newLink;
    }

    //find a image of Activity Plus, if found convert for nice social context
    $image = $buddyStreamFilters->extractString($cleanContent, '[bpfb_images]', '[/bpfb_images]');

    if($image){
        $wpUpload  = wp_upload_dir();
        $uploadUrl = $wpUpload['baseurl'];
        $newImage  = $uploadUrl . '/bpfb/' . trim($image);

        $cleanContent =  str_replace($image, "", $cleanContent);
        $cleanContent =  str_replace("[bpfb_images]", "", $cleanContent);
        $cleanContent =  str_replace("[/bpfb_images]", "", $cleanContent);
        $cleanContent .= " " . $newImage;
    }

    //strip out all html so we can search clean content
    $cleanContent = strip_tags($cleanContent);

    foreach($buddyStreamExtensions->getExtensionsConfigs() as $extension){

        $arrHastags = explode(",", $extension['hashtag']);
        foreach($arrHastags as $hashtag){
            if (get_site_option("buddystream_" . $extension['name'] . "_power")) {

                //explode text to single words then check if hashtag is found
                $arrayContent = explode(" ", $cleanContent);
                if (in_array($hashtag, $arrayContent)) {
                    if(function_exists("buddystream" . ucfirst($extension['name']) . "PostUpdate")){
                        call_user_func("buddystream" . ucfirst($extension['name']) . "PostUpdate", $cleanContent, $shortLink, $user_id);
                    }
                }
            }
        }
    }

    //if use location is used add map image to content
    if( ! get_site_option("buddystream_nolocation")){
        if (preg_match("/#location/i", $originalText)) {

            if(isset($_COOKIE["buddystream_location"])){

                $arrLocation  = explode("#",$_COOKIE["buddystream_location"]);
                $mapIcon      = BP_BUDDYSTREAM_URL.'/images/marker.png';
                $mapUrl       = 'http://maps.googleapis.com/maps/api/staticmap?center=' . $arrLocation[0] . ',' . $arrLocation[1] . '&zoom=13&size=540x150&sensor=false&markers=icon%3A' . $mapIcon . '%7C' . $arrLocation[0] . ',' . $arrLocation[1] . '&format=png32';
                $originalText .= '<img class="buddystream_map_image" src="' . $mapUrl . '">';
            }
        }

        $originalText = str_replace("#location", "", $originalText);
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
    $buddyStreamExtensions = new BuddyStreamExtensions();

    foreach($buddyStreamExtensions->getExtensionsConfigs() as $extension){

        if(isset($extension['hashtag']) && $extension['hashtag']){

            if (get_site_option("buddystream_".$extension['name']."_power")) {
                if(function_exists("buddystream" . ucfirst($extension['name']) . "Sharing")){
                    call_user_func("buddystream" . ucfirst($extension['name']) . "Sharing");
                }
            }

        }
    }

    echo '<div class="buddystream_hoverbox"></div>';


    if( ! get_site_option("buddystream_nolocation")){
        echo'<span class="buddystream_share_button mylocation" id="' . __('Add my location', 'buddystream_location') . '"></span>';
    }

        echo "<script>var buddystream_url = '" . BP_BUDDYSTREAM_URL . "';</script>";

        echo '<div class="buddystream_show_location">
                <div class="buddystream_location_type"></div>
                <div class="buddystream_location_map"></div>
                <div class="buddystream_location_button use">'.__('Use this location','buddystream').'</div>
                <div class="buddystream_location_button cancel">'.__('Cancel','buddystream').'</div>
              </div>';
}

function BuddystreamShareButton() {

    $buddyStreamExtensions = new BuddyStreamExtensions();

    $shares = array();
    foreach($buddyStreamExtensions->getExtensionsConfigs() as $extension){
        if(get_site_option("buddystream_".$extension['name']."_share") == "on"){
            $shares[] = $extension['name'];
        }
    }
    $shares = implode(',',$shares);

    echo '<a href="' . BP_BUDDYSTREAM_URL . '/extensions/default/templates/ShareBox.php?content=' .  urlencode(strip_tags(bp_get_activity_content_body())) . '&link=' . urlencode(bp_get_activity_thread_permalink()) . '&shares=' . $shares . ' " class="bs_lightbox button item-button">Sharebox!</a>';
}

/*
 * Add activity filters
 */
add_action('bp_activity_filter_options', 'buddystreamAddFilter', 1);
add_action('bp_member_activity_filter_options', 'buddystreamAddFilter', 1);

function buddystreamAddFilter()
{

    $buddyStreamExtensions = new BuddyStreamExtensions();

    foreach($buddyStreamExtensions->getExtensionsConfigs() as $extension){
        if (get_site_option("buddystream_".$extension['name'] . "_power") && !$extension['parent']) {
            echo'<option value="' . $extension['name'] . '">' .
                __('Show ' . ucfirst($extension['displayname']), 'buddystream_' . $extension['name']) .
                '</option>';
        }
    }
}

/**
 * Remove all hash-tags of extensions from returns
 */
add_action('bp_get_activity_latest_update','removetags', 9);
add_action('bp_get_member_latest_update','removetags', 9);
function removetags($content)
{
    $buddyStreamFilters = new BuddyStreamFilters();
    return $buddyStreamFilters->removeHashTags($content);
}


add_filter('group_forum_topic_title_before_save', 'tweetstream_topic');
function tweetstream_topic() {

    $buddyStreamFilters = new BuddyStreamFilters();
    return $buddyStreamFilters->removeHashTags($_POST['topic_title']);

}

add_filter('group_forum_post_text_before_save', 'buddystream_filtertags_fp', 9);
function buddystream_filtertags_fp() {

    $buddyStreamFilters = new BuddyStreamFilters();

    $content = $_POST['reply_text'];

    if ($content == "") {
        $content = $_POST['post_text'];
    }

    return $buddyStreamFilters->removeHashTags($content);
}

if(defined(BP_BUDDYSTREAM_IS_PREMIUM)){
    add_filter('site_transient_update_plugins', 'buddystream_remove_update_nag');
}

function buddystream_remove_update_nag($value) {

    if($value){
        unset($value->response[plugin_basename(__FILE__) ]);
        return $value;
    }

    return $value;
}

/**
 * Don't show activity items marked as private
 * @param $a
 * @param $activities
 * @return mixed
 */
function buddystreamFilterOutPrivate( $a, $activities ) {

    foreach ( $activities->activities as $key => $activity ) {
        if ( $activity->type =='private') {
            unset( $activities->activities[$key] );

            $activities->activity_count = $activities->activity_count-1;
            $activities->total_activity_count = $activities->total_activity_count-1;
            $activities->pag_num = $activities->pag_num -1;
        }
    }

    $activities_new = array_values( $activities->activities );
    $activities->activities = $activities_new;

    return $activities;
}

add_action('bp_has_activities','buddystreamFilterOutPrivate', 10, 2 );