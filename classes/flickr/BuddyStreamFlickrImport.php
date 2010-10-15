<?php

class BuddyStreamFlickrImport {

    public function doImport($limit) {

        global $bp, $wpdb;
        include_once "classes/flickr/BuddyStreamFlickr.php";

            $user_metas = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT user_id
                        FROM $wpdb->usermeta where
                        meta_key='bs_flickr_username'
                        order by meta_value LIMIT ".$limit.";"
                    )
            );

            if ($user_metas) {
                foreach ($user_metas as $user_meta) {

                    //max photos per day
                    if (get_site_option(
                            'bs_flickr_user_settings_maximport'
                        ) != '') {

                        if (get_usermeta(
                                $user_meta->user_id,
                                'bs_flickr_daycounter'
                            ) <= get_site_option(
                                'bs_flickr_user_settings_maximport'
                            )
                        ) {

                            $import = 1;
                        } else {
                            $import = 0;
                        }
                    } else {
                        $import = 1;
                    }

                   //timestamp must be older then 5 minutes!
                   if(!get_usermeta($user_meta->user_id, 'bs_flickr_stamp')){
                       update_usermeta($user_meta->user_id, 'bs_flickr_stamp',date('d-m-Y H:i:s'));
                   }

                    $tago = time() - strtotime(get_usermeta($user_meta->user_id, 'bs_flickr_stamp'));
                    if ($tago > 300) {
                        $import = 1;
                    }
                    //end time check

                    if ($import == 1 && get_usermeta($user_meta->user_id, 'bs_flickr_username') != "") {

                        $flickr = new BuddyStreamFlickr();
                        $flickr->setConsumerKey(get_site_option("bs_flickr_api_key"));
                        $flickr->setUsername(get_usermeta($user_meta->user_id, 'bs_flickr_username'));
                        $photos = $flickr->getPhotos();

                        if (is_object($photos)) {
                            foreach ($photos as $photo) {

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $photo->id),'show_hidden' => true));
                                if (!$activity_info['activities'][0]->id) {
                                    //create new activity instance
                                    $activity = new BP_Activity_Activity ();
                                    $activity->user_id = $user_meta->user_id;
                                    $activity->component = "flickr";
                                    $activity->type = "flickr";

                                    if (!defined('BP_ENABLE_ROOT_PROFILES')) {
                                        $slug = BP_MEMBERS_SLUG. '/';
                                    }

                                    //photo get max size
                                    $bigPhoto = "";
                                    if ($photo->Large->uri !="") {
                                        $bigPhoto = $photo->Large->uri;
                                    } else if ($photo->Medium->uri !="") {
                                        $bigPhoto = $photo->Medium->uri;
                                    } else if ($photo->Small->uri !="") {
                                        $bigPhoto = $photo->Small->uri;
                                    }

                                    $activity->action = '<a href="' . $bp->root_domain . '/' . $slug . bp_core_get_username($user_meta->user_id) . '/" title="' . bp_core_get_username($user_meta->user_id) . '">' . bp_core_get_user_displayname($user_meta->user_id) . '</a> <a href="http://www.flickr.com/photos/' .$photo->owner. '" target="_blanc"><img src="' . WP_PLUGIN_URL . '/buddystream/images/flickr/icon-small.png"></a> ' . __('posted a', 'tweetstream_lang') . ' <a href="http://www.flickr.com/photos/' .$photo->owner. '" target="_blanc">' . __('photo', 'buddystream_lang') . '</a>:';
                                    $activity->content = '<a href="'.$bigPhoto.'" class="bs_lightbox" id="'.$photo->id.'" title="'.$photo->title.'"><img src="'.$photo->Thumbnail->uri.'" title="'.$photo->title.'"></a>';
                                    $activity->secondary_item_id = $photo->id;
                                    $activity->date_recorded = gmdate('Y-m-d H:i:s',$photo->dateupload);


                                      if(get_site_option('bs_flickr_hide_sitewide')){
                                        $activity->hide_sitewide = 1;
                                    }else{
                                        $activity->hide_sitewide = 0;
                                    }

                                    //check if item does not exist in the blacklist
                                    if (!preg_match("/".$photo->id."/i", get_usermeta($user_meta->user_id, 'buddystream_blacklist_ids'))) {
                                        $activity->save();
                                    }

                                    if (get_usermeta($user_meta->user_id, 'bs_flickr_counterdate') != date('d-m-Y')) {
                                        update_user_meta($user_meta->user_id, 'bs_flickr_daycounter', 0);
                                        update_user_meta($user_meta->user_id, 'bs_flickr_counterdate', date('d-m-Y'));
                                    }

                                    update_user_meta((int) $user_meta->user_id, 'bs_flickr_daycounter', get_usermeta($user_meta->user_id, 'bs_flickr_daycounter') + 1);
                            }
                        }
                            
                        update_user_meta($user_meta->user_id, 'bs_flickr_stamp', date('d-m-Y H:i:s'));


                                }
                            }
                        }
                    }
    }
}