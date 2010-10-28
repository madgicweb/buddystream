<?php

class BuddyStreamFacebookImport {
    public function doImport($limit)
    {


    global $bp,$wpdb;

    if (get_site_option('facestream_user_settings_syncbp') == 0) {

            include_once "classes/facebook/BuddystreamFacebook.php";
            $user_metas = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta where meta_key='facestream_stamp' order by meta_value LIMIT ".$limit.";"));

            if ($user_metas) {
                foreach ($user_metas as $user_meta) {

                    //import check
                    if($item['type']=="status") {
                        if(get_usermeta($user_id, 'facestream_syncupdatestoac')==0 || get_site_option('facestream_user_settings_syncupdatesbp')==0){
                            $import = 0;
                        }
                    }

                    if($item['type']=="link") {
                        if(get_usermeta($user_id, 'facestream_synclinkstoac')==0 || get_site_option('facestream_user_settings_synclinksbp')==0){
                            $import = 0;
                        }
                    }

                    if($item['type']=="photo") {
                        if(get_usermeta($user_id, 'facestream_syncphotostoac')==0 || get_site_option('facestream_user_settings_syncphotosbp')==0){
                            $import = 0;
                        }
                    }

                    if($item['type']=="video") {
                        if(get_usermeta($user_id, 'facestream_syncvideostoac')==0 || get_site_option('facestream_user_settings_syncvideosbp')==0){
                            $import = 0;
                        }
                    }


                   //max tweets per day
                   if (get_site_option('facestream_user_settings_maximport') != '') {
                       if (get_usermeta($user_meta->user_id, 'facestream_daycounter') <= get_site_option('facestream_user_settings_maximport')) {
                           $import = 1;
                       }else{
                           $import = 0;
                       }
                   }else{
                       $import = 1;
                   }

                   //timestamp must be older then 5 minutes!
                   if(!get_usermeta($user_meta->user_id, 'facestream_stamp')){
                       update_usermeta($user_meta->user_id, 'facestream_stamp',date('d-m-Y H:i:s'));
                   }

                    $tago = time() - strtotime(get_usermeta($user_meta->user_id, 'facestream_stamp'));
                    if ($tago > 300) {
                        $import = 1;
                    }
                    //end time check


                   if ($import == 1 && get_usermeta($user_meta->user_id, 'facestream_session_key')!="") {

                        //FACEBOOK
                        $facebook = new BuddystreamFacebook;
                        $facebook->setApplicationKey(get_site_option("facestream_application_id"));
                        $facebook->setApplicationSecret(get_site_option("facestream_application_secret"));
                        $facebook->setAccessToken(get_usermeta($user_meta->user_id, 'facestream_session_key'));
                        $facebook->setSource($bp->root_domain);
                        $facebook->setGoodFilters(get_site_option('facestream_filter').get_usermeta($user_meta->user_id,'facestream_filtergood'));
                        $facebook->setBadFilters(get_site_option('facestream_filterexplicit').get_usermeta($user_meta->user_id,'facetream_filterbad'));
                        $items = $facebook->requestWall();

                          if(is_array($items)){
                            foreach($items as $item){

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $item['id']),'show_hidden' => true));
                                 if(!$activity_info['activities'][0]->id) {
                                    //create new activity instance
                                    $activity = new BP_Activity_Activity();
                                    $activity->user_id = $user_meta->user_id;
                                    $activity->component = "facestream";
                                    $activity->type = "facebook";

                                    if (!defined('BP_ENABLE_ROOT_PROFILES')) {
                                        $slug = BP_MEMBERS_SLUG.'/';
                                    }

                                    $activity->action = '<a href="' . $bp->root_domain . '/' . $slug . bp_core_get_username($user_meta->user_id) . '/" title="' . bp_core_get_username($user_meta->user_id) . '">' . bp_core_get_user_displayname($user_meta->user_id) . '</a> <a href="http://www.facebook.com/profile.php?id=' . get_usermeta($user_meta->user_id, 'facestream_user_id') . '"><img src="'.WP_PLUGIN_URL.'/buddystream/images/facebook/icon-small.png"></a> ' . __('posted a', 'buddystream_lang') . ' <a href="http://www.facebook.com/profile.php?id=' .get_usermeta($user_meta->user_id, 'facestream_user_id') . '">' . $item["type"] . '</a>:';

                                    $message = "";
                                    $message = $item['message'];
                                    if(strlen($message) > 400){
                                       $message = substr($message,0,400)."...";
                                    }

                                    $description = "";
                                    $description = $item['message'];
                                    if(strlen($description) > 400){
                                       $description = substr($description,0,400)."...";
                                    }

                                    if($item['type']=="status"){
                                          $activity->content =
                                            '<div class="facebook_container">
                                               <div class="facebook_container_message">
                                                   '.$message.'
                                                </div>
                                            </div>';
                                    }

                                    if($item['type']=="photo"){
                                         $activity->content =
                                        '<div class="facebook_container">
                                           <div class="facebook_container_image">
                                              <a href="'.$item['link'].'"><img src="'.$item['picture'].'"></a>
                                           </div>
                                           <div class="facebook_container_message">
                                               '.$message.'
                                            </div>
                                        </div>';
                                    }


                                    if($item['type']=="link"){
                                        $activity->content =
                                        '<div class="facebook_container">
                                           <div class="facebook_container_image">
                                              <a href="'.$item['link'].'"><img src="'.$item['picture'].'"></a>
                                           </div>
                                           <div class="facebook_container_message">
                                               '.$description.'
                                            </div>
                                        </div>';
                                    }


                                    if($item['type']=="video"){

                                        if ($item['attribution'] != "YouTube") {
                                            $activity->content =
                                            '<div class="facebook_container">
                                               <div class="facebook_container_image">
                                                  <a href="'.$item['link'].'"><img src="'.$item['picture'].'"></a>
                                               </div>
                                               <div class="facebook_container_message">
                                                   '.$description.'
                                                </div>
                                            </div>';
                                        } else {

                                            $youtube_link = $item['source'];
                                            $youtube_link = str_replace("http://www.youtube.com/v/","",$youtube_link);
                                            $youtube_link = str_replace("&autoplay=1","",$youtube_link);
                                            $youtube_link = str_replace("&feature=autoshare","",$youtube_link);
                                            $youtube_link = "http://www.youtube.com/watch/?v=".$youtube_link;

                                            $activity->content =
                                            '<div class="facebook_container">
                                               <div class="facebook_container_image">
                                               </div>
                                               <div class="facebook_container_message">
                                                    '.$item['caption'].':<br>
                                                    <a href="'.$youtube_link.'" class="bs_lightbox" title="'.$item['name'].'">'.$item['name'].'</a>
                                                </div>
                                            </div>';
                                        }
                                    }

                                    $activity->secondary_item_id = $item['id'];
                                    $activity->date_recorded = gmdate('Y-m-d H:i:s', strtotime($item['created_time']));

                                    if(get_site_option('bs_facebook_hide_sitewide')){
                                        $activity->hide_sitewide = 1;
                                    }else{
                                        $activity->hide_sitewide = 0;
                                    }

                                    
                                    //check if item does not exist in the blacklist
                                    if (!preg_match("/".$item['id']."/i", get_usermeta($user_meta->user_id, 'buddystream_blacklist_ids'))) {
                                        $activity->save();
                                    }
                                    

                                    if (get_usermeta($user_meta->user_id, 'facestream_counterdate') != date('d-m-Y')) {
                                        update_usermeta($user_meta->user_id, 'facestream_daycounter', 0);
                                        update_usermeta($user_meta->user_id, 'facestream_counterdate', date('d-m-Y'));
                                    }
                                    update_usermeta($user_meta->user_id, 'facestream_daycounter', get_usermeta($user_meta->user_id, 'facestream_daycounter') + 1);
                                 }
                            }
                        }
                    }
                    update_usermeta($user_meta->user_id, 'facestream_stamp',date('d-m-Y H:i:s'));
                }
            }

        }
    }
}
