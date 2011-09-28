<?php
/**
 * Import starter
 */

function BuddystreamYoutubeImportStart(){
    //check if api server is online
    if(buddystreamCheckNetwork("http://www.youtube.com")) {
        $importer = new BuddyStreamYoutubeImport();
        $importer->doImport();
     }else{
        buddystreamLog(__("Youtube API server offline at the moment.","buddystream_youtube"),"error");
    }  
}

/**
 * Youtube Import Class
 */

class BuddyStreamYoutubeImport {

    public function doImport() {

        global $bp, $wpdb;
        $itemCounter = 0;
        
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
                    if (get_user_meta($user_meta->user_id, 'bs_youtube_counterdate',1) != date('d-m-Y')) {
                        update_user_meta($user_meta->user_id, 'bs_youtube_daycounter', 1);
                        update_user_meta($user_meta->user_id, 'bs_youtube_counterdate', date('d-m-Y'));
                    }

                    //max photos per day
                    if (get_site_option(
                            'bs_youtube_user_settings_maximport'
                        ) != '') {

                        if (get_user_meta(
                                $user_meta->user_id,
                                'bs_youtube_daycounter',1
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

                    if ($import == 1 && get_user_meta($user_meta->user_id, 'bs_youtube_username',1) != "") {

                        $youtube = new BuddyStreamYoutube();
                        $youtube->setUsername(get_user_meta($user_meta->user_id, 'bs_youtube_username',1));
                        $videos = $youtube->getVideos();
                        $videos2 = $youtube->getFavorites();

                        if (is_object($videos)) {
                            foreach ($videos as $video) {
                                
                                //max items
                                $max = 1;
                                if (get_site_option('bs_youtube_user_settings_maximport') != '') {
                                    if (get_user_meta($user_meta->user_id,'bs_youtube_daycounter',1) <= get_site_option('bs_youtube_user_settings_maximport')) {
                                        $max = 0;
                                    }
                                }else{
                                    $max = 0;
                                }

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $video->getVideoId()),'show_hidden' => true));
                                if (!$activity_info['activities'][0]->id) {
                               

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

                                    $content =
                                        '<div class="youtube_container_image">
                                              <a href="http://www.youtube.com/watch/?v='.$video->getVideoId().'" class="bs_lightbox" id="'.$video->getVideoId().'" title="'.$video->getVideoTitle().'"><img src="'.$thumbnail.'"></a>
                                           </div>
                                           <div class="youtube_container_message">
                                               <b>'.$video->getVideoTitle().'</b><br>
                                               '.$description.'
                                            </div>';
                                    
                                    //pre convert date
                                    $ts = strtotime($video->getUpdated());
                                    
                                     buddystreamCreateActivity(array(
                                         'user_id'       => $user_meta->user_id,
                                         'extention'     => 'youtube',
                                         'type'          => 'video',
                                         'content'       => $content,
                                         'item_id'       => $video->getVideoId(),
                                         'raw_date'      => date("Y-m-d H:i:s", $ts),
                                         'actionlink'    => 'http://www.youtube.com/' .get_user_meta($user_meta->user_id, 'bs_youtube_username',1)
                                        )
                                     );
                                     $itemCounter++;
                                    
                            }
                        }
                        }

                         if (is_object($videos2)) {
                            foreach ($videos2 as $video) {

                                 //max items
                                $max = 1;
                                if (get_site_option('bs_youtube_user_settings_maximport') != '') {
                                    if (get_user_meta($user_meta->user_id,'bs_youtube_daycounter',1) <= get_site_option('bs_youtube_user_settings_maximport')) {
                                        $max = 0;
                                    }
                                }else{
                                    $max = 0;
                                }

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $video->getVideoId())));
                                if (!$activity_info['activities'][0]->id && $max == 0) {
                                    
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

                                    $content =
                                        '<div class="youtube_container_image">
                                              <a href="http://www.youtube.com/watch/?v='.$video->getVideoId().'" class="bs_lightbox" id="'.$video->getVideoId().'" title="'.$video->getVideoTitle().'"><img src="'.$thumbnail.'"></a>
                                           </div>
                                           <div class="youtube_container_message">
                                               <b>'.$video->getVideoTitle().'</b><br>
                                               '.$description.'
                                            </div>';

                                    //pre convert date
                                    $ts = strtotime($video->getUpdated());
                                    
                                    echo date("Y-m-d H:i:s", $ts)."<br>";
                                    
                                    buddystreamCreateActivity(array(
                                         'user_id'       => $user_meta->user_id,
                                         'extention'     => 'youtube',
                                         'content'       => $content,
                                         'item_id'       => $video->getVideoId(),
                                         'raw_date'      => date("Y-m-d H:i:s", $ts),
                                         'actionlink'    => 'http://www.youtube.com/' .get_user_meta($user_meta->user_id, 'bs_youtube_username',1)
                                        )
                                     );         
                                    $itemCounter++;
                            }
                        }
                        }

                            }
                        }
                    }
                    //add record to the log
                    buddystreamLog("Youtube imported ".$itemCounter." video's.");
    }
}