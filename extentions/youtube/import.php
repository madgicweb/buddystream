<?php
/**
 * Import starter
 */

function BuddystreamYoutubeImportStart(){
    $importer = new BuddyStreamYoutubeImport();
    return $importer->doImport();
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
                   
                     //always start with import = true
                    $import = true;
                    
                    //check for daylimit
                    $import = BuddyStreamFilters::limitReached('youtube', $user_meta->user_id);
                    
                    if ($import  && get_user_meta($user_meta->user_id, 'bs_youtube_username', 1)) {
                   
                        //get these urls for import
                        $importUrls = array('https://gdata.youtube.com/feeds/api/users/'.get_user_meta($user_meta->user_id, 'bs_youtube_username', 1) . '/uploads',
                                            'https://gdata.youtube.com/feeds/api/users/'.get_user_meta($user_meta->user_id, 'bs_youtube_username', 1) . '/favorites');
                        
                        foreach ($importUrls as $importUrl){
                            
                            $sxml = @simplexml_load_file($importUrl);

                            if (isset($sxml->entry)) {
                                foreach ($sxml->entry as $item) {

                                    $max = BuddyStreamFilters::limitReached('youtube', $user_meta->user_id);

                                    // get nodes in media: namespace for media information
                                    $media = $item->children('http://search.yahoo.com/mrss/');

                                    // get video player URL
                                    $attrs = $media->group->player->attributes();
                                    $link = $attrs['url']; 

                                    // get video thumbnail
                                    $attrs = $media->group->thumbnail[0]->attributes();
                                    $thumbnail = $attrs['url']; 

                                    //get the video id from player url
                                    $videoIdArray = explode("=",$link);
                                    $videoId = $videoIdArray[1];
                                    $videoId = str_replace("&feature","",$videoId);

                                    $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $videoId),'show_hidden' => true));
                                    if (!$activity_info['activities'][0]->id && !$max) {

                                        $description = "";
                                        $description = $media->group->description;
                                        if(strlen($description) > 400){
                                           $description = substr($description,0,400)."... <a href='http://www.youtube.com/watch/?v=".$videoId."'>read more</a>";
                                        }

                                        $content = '<a href="http://www.youtube.com/watch/?v='.$videoId.'" class="bs_lightbox" id="'.$videoId.'" title="'.$media->group->title.'"><img src="'.$thumbnail.'"></a><b>'.$media->group->title.'</b> '.$description;

                                        //pre convert date
                                        $ts = strtotime($item->published);

                                         buddystreamCreateActivity(array(
                                             'user_id'       => $user_meta->user_id,
                                             'extention'     => 'youtube',
                                             'type'          => 'Youtube video',
                                             'content'       => $content,
                                             'item_id'       => $videoId,
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
            }
            
            //add record to the log
            BuddyStreamLog::log("Youtube imported ".$itemCounter." video's.");
            
            //return number of items imported
            return $itemCounter;
    }
}