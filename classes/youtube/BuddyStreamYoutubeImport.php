<?php
class BuddyStreamYoutubeImport {

    public function doImport() {

        global $bp, $wpdb;

        $time_start = microtime_float_import();
        include_once "classes/youtube/BuddyStreamYoutube.php";

            $user_metas = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT user_id
                        FROM $wpdb->usermeta where
                        meta_key='bs_youtube_username'
                        order by meta_value;"
                    )
            );

            if ($user_metas) {
                foreach ($user_metas as $user_meta) {
                    
                    $import = 1;

                   //daycounter reset
                    if (get_usermeta($user_meta->user_id, 'bs_youtube_counterdate') != date('d-m-Y')) {
                        update_usermeta($user_meta->user_id, 'bs_youtube_daycounter', 1);
                        update_usermeta($user_meta->user_id, 'bs_youtube_counterdate', date('d-m-Y'));
                    }

                    //max photos per day
                    if (get_site_option(
                            'bs_youtube_user_settings_maximport'
                        ) != '') {

                        if (get_usermeta(
                                $user_meta->user_id,
                                'bs_youtube_daycounter'
                            ) <= get_site_option(
                                'bs_youtube_user_settings_maximport'
                            )
                        ) {

                            $import = 1;
                        } else {
                            $import = 0;
                        }
                    } else {
                        $import = 1;
                    }

                    if ($import == 1 && get_usermeta($user_meta->user_id, 'bs_youtube_username') != "") {

                        $youtube = new BuddyStreamYoutube();
                        $youtube->setUsername(get_usermeta($user_meta->user_id, 'bs_youtube_username'));
                        $videos = $youtube->getVideos();
                        $videos2 = $youtube->getFavorites();

                        if (is_object($videos)) {
                            foreach ($videos as $video) {

                                //max items
                                $max = 1;
                                if (get_site_option('bs_youtube_user_settings_maximport') != '') {
                                    if (get_usermeta($user_meta->user_id,'bs_youtube_daycounter') <= get_site_option('bs_youtube_user_settings_maximport')) {
                                        $max = 0;
                                    }
                                }else{
                                    $max = 0;
                                }

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $video->getVideoId()),'show_hidden' => true));
                                if (!$activity_info['activities'][0]->id) {
                                    //create new activity instance
                                    $activity = new BP_Activity_Activity ();
                                    $activity->user_id = $user_meta->user_id;
                                    $activity->component = "youtube";
                                    $activity->type = "youtube";

                                    if (!defined('BP_ENABLE_ROOT_PROFILES')) {
                                        $slug = BP_MEMBERS_SLUG. '/';
                                    }

                                    $activity->action = '<a href="' . $bp->root_domain . '/' . $slug . bp_core_get_username($user_meta->user_id) . '/" title="' . bp_core_get_username($user_meta->user_id) . '">' . bp_core_get_user_displayname($user_meta->user_id) . '</a> <a href="http://www.youtube.com/' .get_usermeta($user_meta->user_id, 'bs_youtube_username'). '" target="_blanc"><img src="' . plugins_url() . '/buddystream/images/youtube/icon-small.png"></a> ' . __('posted a', 'tweetstream_lang') . ' <a href="http://www.youtube.com/' .get_usermeta($user_meta->user_id, 'bs_youtube_username'). '" target="_blanc">' . __('video', 'buddystream_lang') . '</a>:';


                                    $videoThumbnails = $video->getVideoThumbnails();
                                    foreach($videoThumbnails as $videoThumbnail){
                                        $thumbnail = $videoThumbnail['url'];
                                        break;
                                    }

                                    $description = "";
                                    $description = $video->getVideoDescription();
                                    if(strlen($description) > 400){
                                       $description = substr($description,0,400)."... <br><br> <a href='http://www.youtube.com/watch/?v=".$video->getVideoId()."'>read more</a>";
                                    }

                                    $activity->content =
                                        '<div class="youtube_container">
                                           <div class="youtube_container_image">
                                              <a href="http://www.youtube.com/watch/?v='.$video->getVideoId().'" class="bs_lightbox" id="'.$video->getVideoId().'" title="'.$video->getVideoTitle().'"><img src="'.$thumbnail.'"></a>
                                           </div>
                                           <div class="youtube_container_message">
                                               <b>'.$video->getVideoTitle().'</b><br>
                                               '.$description.'
                                            </div>
                                        </div>';


                                    $activity->secondary_item_id = $video->getVideoId();
                                    $activity->date_recorded = gmdate('Y-m-d H:i:s',strtotime($video->getUpdated()));

                                    if(get_site_option('bs_youtube_hide_sitewide')){
                                        $activity->hide_sitewide = 1;
                                    }else{
                                        $activity->hide_sitewide = 0;
                                    }

                                    //check if item does not exist in the blacklist
                                    if(get_usermeta($user_meta->user_id, 'buddystream_blacklist_ids')){
                                        if (!preg_match("/".$video->getVideoId()."/i", get_usermeta($user_meta->user_id, 'buddystream_blacklist_ids'))) {
                                            $activity->save();
                                            update_usermeta($user_meta->user_id, 'bs_youtube_daycounter', get_usermeta($user_meta->user_id, 'bs_youtube_daycounter') + 1);
                                        }
                                    }else{
                                        $activity->save();
                                         update_usermeta($user_meta->user_id, 'bs_youtube_daycounter', get_usermeta($user_meta->user_id, 'bs_youtube_daycounter') + 1);
                                    }
                            }
                        }
                        }

                         if (is_object($videos2)) {
                            foreach ($videos2 as $video) {

                                 //max items
                                $max = 1;
                                if (get_site_option('bs_youtube_user_settings_maximport') != '') {
                                    if (get_usermeta($user_meta->user_id,'bs_youtube_daycounter') <= get_site_option('bs_youtube_user_settings_maximport')) {
                                        $max = 0;
                                    }
                                }else{
                                    $max = 0;
                                }

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $video->getVideoId())));
                                if (!$activity_info['activities'][0]->id && $max == 0) {
                                    //create new activity instance
                                    $activity = new BP_Activity_Activity ();
                                    $activity->user_id = $user_meta->user_id;
                                    $activity->component = "youtube";
                                    $activity->type = "youtube";
                                    
                                    if (!defined('BP_ENABLE_ROOT_PROFILES')) {
                                        $slug = BP_MEMBERS_SLUG. '/';
                                    }
                                    $activity->action = '<a href="' . $bp->root_domain . '/' . $slug . bp_core_get_username($user_meta->user_id) . '/" title="' . bp_core_get_username($user_meta->user_id) . '">' . bp_core_get_user_displayname($user_meta->user_id) . '</a> <a href="http://www.youtube.com/' .get_usermeta($user_meta->user_id, 'bs_youtube_username'). '" target="_blanc"><img src="' . plugins_url() . '/buddystream/images/youtube/icon-small.png"></a> ' . __('posted a', 'tweetstream_lang') . ' <a href="http://www.youtube.com/' .get_usermeta($user_meta->user_id, 'bs_youtube_username'). '" target="_blanc">' . __('video', 'buddystream_lang') . '</a>:';


                                    $videoThumbnails = $video->getVideoThumbnails();
                                    foreach($videoThumbnails as $videoThumbnail){
                                        $thumbnail = $videoThumbnail['url'];
                                        break;
                                    }

                                    $description = "";
                                    $description = $video->getVideoDescription();
                                    if(strlen($description) > 400){
                                       $description = substr($description,0,400)."... <br><br> <a href='http://www.youtube.com/watch/?v=".$video->getVideoId()."'>read more</a>";
                                    }

                                    $activity->content =
                                        '<div class="youtube_container">
                                           <div class="youtube_container_image">
                                              <a href="http://www.youtube.com/watch/?v='.$video->getVideoId().'" class="bs_lightbox" id="'.$video->getVideoId().'" title="'.$video->getVideoTitle().'"><img src="'.$thumbnail.'"></a>
                                           </div>
                                           <div class="youtube_container_message">
                                               <b>'.$video->getVideoTitle().'</b><br>
                                               '.$description.'
                                            </div>
                                        </div>';


                                    $activity->secondary_item_id = $video->getVideoId();
                                    $activity->date_recorded = gmdate('Y-m-d H:i:s',strtotime($video->getUpdated()));

                                    if(get_site_option('bs_youtube_hide_sitewide')){
                                        $activity->hide_sitewide = 1;
                                    }else{
                                        $activity->hide_sitewide = 0;
                                    }

                                     //check if item does not exist in the blacklist
                                    if(get_usermeta($user_meta->user_id, 'buddystream_blacklist_ids')){
                                        if (!preg_match("/".$tweet->id."/i", get_usermeta($user_meta->user_id, 'buddystream_blacklist_ids'))) {
                                            $activity->save();
                                            update_usermeta((int) $user_meta->user_id, 'bs_youtube_daycounter', get_usermeta($user_meta->user_id, 'bs_youtube_daycounter') + 1);
                                        }
                                    }else{
                                        $activity->save();
                                        update_usermeta((int) $user_meta->user_id, 'bs_youtube_daycounter', get_usermeta($user_meta->user_id, 'bs_youtube_daycounter') + 1);
                                    }                                    
                            }
                        }
                        }

                            }
                        }
                    }

        $time_end = microtime_float_import();
        return $time_end - $time_start;
    }
}