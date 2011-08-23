<?php
/**
 * Import starter
 */

function BuddystreamFlickrImportStart(){
    //check if api server is online
    if(buddystreamCheckNetwork("http://www.flickr.com")){
        $importer = new BuddyStreamFlickrImport();
        $importer->doImport();
    }else{
        buddystreamLog(__("Flickr API server offline at the moment.","buddystream_flickr"),"error");
    }   
}

/**
 * Flickr Import Class
 */

class BuddyStreamFlickrImport {

    public function doImport() {

        global $bp, $wpdb;
        $itemCounter = 0;
        
            $user_metas = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT user_id
                        FROM $wpdb->usermeta where
                        meta_key='bs_flickr_username'
                        order by meta_value;"
                    )
            );

            if ($user_metas) {
                foreach ($user_metas as $user_meta) {

                    $import = 1;

                   //daycounter reset
                    if (get_user_meta($user_meta->user_id, 'bs_flickr_counterdate', 1) != date('d-m-Y')) {
                        update_user_meta($user_meta->user_id, 'bs_flickr_daycounter', 1);
                        update_user_meta($user_meta->user_id, 'bs_flickr_counterdate', date('d-m-Y'));
                    }

                    //max tweets per day
                    if (get_site_option('bs_flickr_user_settings_maximport') != '') {
                        if (get_user_meta($user_meta->user_id, 'bs_flickr_daycounter',1) <= get_site_option('bs_flickr_user_settings_maximport')) {
                            $import = 1;
                        } else {
                            $import = 0;
                        }
                    } else {
                        $import = 1;
                    }
                   
                    if ($import == 1 && get_user_meta($user_meta->user_id, 'bs_flickr_username',1) != "") {

                        $flickr = new BuddyStreamFlickr();
                        $flickr->setConsumerKey(get_site_option("bs_flickr_api_key"));
                        $flickr->setUsername(get_user_meta($user_meta->user_id, 'bs_flickr_username',1));
                        $photos = $flickr->getPhotos();

                        if (is_object($photos)) {
                            foreach ($photos as $photo) {

                                //max items
                                $max = 1;
                                if (get_site_option('bs_flickr_user_settings_maximport') != '') {
                                    if (get_user_meta($user_meta->user_id,'bs_flickr_daycounter',1) <= get_site_option('bs_flickr_user_settings_maximport')) {
                                        $max = 0;
                                    }
                                }else{
                                    $max = 0;
                                }

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $photo->id),'show_hidden' => true));
                                if (!$activity_info['activities'][0]->id && $max == 0) {

                                    //photo get max size
                                    $bigPhoto = "";
                                    if ($photo->Large->uri !="") {
                                        $bigPhoto = $photo->Large->uri;
                                    } else if ($photo->Medium->uri !="") {
                                        $bigPhoto = $photo->Medium->uri;
                                    } else if ($photo->Small->uri !="") {
                                        $bigPhoto = $photo->Small->uri;
                                    }


                                    $content = '<a href="'.$bigPhoto.'" class="bs_lightbox" id="'.$photo->id.'" title="'.$photo->title.'"><div class="flickr_container_image"><img src="'.$photo->Thumbnail->uri.'" title="'.$photo->title.'"></div></a> '.$photo->description;
                                        
                                     buddystreamCreateActivity(array(
                                         'user_id'       => $user_meta->user_id,
                                         'extention'     => 'flickr',
                                         'type'          => 'photo',
                                         'content'       => $content,
                                         'item_id'       => $photo->id,
                                         'raw_date'      => gmdate('Y-m-d H:i:s', $photo->dateupload),
                                         'actionlink'    => 'http://www.flickr.com/photos/' .$photo->owner
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
    buddystreamLog("Flickr imported ".$itemCounter." photo's.");
    }
}
