<?php
/**
 * Import starter
 */

function BuddystreamSoundcloudImportStart()
{
    $importer = new BuddyStreamSoundcloudImport();
    return $importer->doImport();
}

/**
 * Soundcloud Import Class
 */
class BuddyStreamSoundcloudImport
{

    public function doImport()
    {

        global $bp, $wpdb;

        $buddyStreamFilter = new BuddyStreamFilters();
        $buddyStreamLog = new BuddyStreamLog();

        $itemCounter = 0;
        
        if (get_site_option("soundcloud_client_id")) {
            if (get_site_option('soundcloud_user_settings_syncbp') == 0) {

                $user_metas = $wpdb->get_results(
                        "SELECT user_id
                FROM $wpdb->usermeta WHERE
                meta_key='soundcloud_access_token'"
                );

                if ($user_metas) {
                    foreach ($user_metas as $user_meta) {

                        //daycounter reset
                        if (get_user_meta($user_meta->user_id, 'soundcloud_counterdate') != date('d-m-Y')) {
                            update_user_meta($user_meta->user_id, 'soundcloud_daycounter', 1);
                            update_user_meta($user_meta->user_id, 'soundcloud_counterdate', date('d-m-Y'));
                        }

                        //check for daylimit
                        $limitReached = $buddyStreamFilter->limitReached('soundcloud', $user_meta->user_id);
                        
                        if (!$limitReached) {

                            $soundcloud = new BuddystreamSoundcloud(
                                get_site_option("soundcloud_client_id"),
                                get_site_option("soundcloud_client_secret"),
                                $bp->root_domain . "/?buddystream_auth=soundcloud"
                            );

                            $soundcloud->setAccessToken(get_user_meta($user_meta->user_id, 'soundcloud_access_token', 1));

                            
                            //favs
                             
                            try {
                                $favs = json_decode($soundcloud->get('me/favorites',array(),array(CURLOPT_SSL_VERIFYPEER => false,CURLOPT_SSL_VERIFYHOST => false)), true);
        
                                if ($favs) {
                                    foreach ($favs as $track) {
                                        
                                        //check daylimit
                                        $limitReached = $buddyStreamFilter->limitReached('soundcloud', $user_meta->user_id);
                                        if (!buddyStreamCheckImportLog($user_meta->user_id, "soundcloud_" . $track['id'], 'soundcloud') && !$limitReached) {

                                            $returnCreate = buddystreamCreateActivity(array(
                                                    'user_id' => $user_meta->user_id,
                                                    'extension' => 'soundcloud',
                                                    'type' => 'Soundcloud track',
                                                    'content' => $track['title'] . '<br/>' . $track['uri'],
                                                    'item_id' => "soundcloud_" . $track['id'],
                                                    'raw_date' => gmdate('Y-m-d H:i:s', strtotime($track['created_at'])),
                                                    'actionlink' => $track['permalink_url']
                                                )
                                            );

                                            if ($returnCreate) {
                                                $itemCounter++;
                                            }

                                        }

                                    }
                                }

                            } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
                                //do nothing
                            }
                        
                            //tracks
                            try {
                                $tracks = json_decode($soundcloud->get('me/tracks',array(),array(CURLOPT_SSL_VERIFYPEER => false,CURLOPT_SSL_VERIFYHOST => false)), true);

                                if ($tracks) {
                                    foreach ($tracks as $track) {

                                        //check daylimit
                                        $limitReached = $buddyStreamFilter->limitReached('soundcloud', $user_meta->user_id);
                                        if (!buddyStreamCheckImportLog($user_meta->user_id, "soundcloud_" . $track['id'], 'soundcloud') && !$limitReached) {

                                            $returnCreate = buddystreamCreateActivity(array(
                                                    'user_id' => $user_meta->user_id,
                                                    'extension' => 'soundcloud',
                                                    'type' => 'Soundcloud track',
                                                    'content' => $track['title'] . '<br/>' . $track['uri'],
                                                    'item_id' => "soundcloud_" . $track['id'],
                                                    'raw_date' => gmdate('Y-m-d H:i:s', strtotime($track['created_at'])),
                                                    'actionlink' => $track['permalink_url']
                                                )
                                            );

                                            if ($returnCreate) {
                                                $itemCounter++;
                                            }

                                        }

                                    }
                                }
                            } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
                                //do nothing
                            }
                            
                            //user
                            try {
                                $user = json_decode($soundcloud->get('me',array(),array(CURLOPT_SSL_VERIFYPEER => false,CURLOPT_SSL_VERIFYHOST => false)), true);
                                update_user_meta($user_meta->user_id, 'soundcloud_id', $user['id']);
                                update_user_meta($user_meta->user_id, 'soundcloud_permalink', $user['permalink']);
                                update_user_meta($user_meta->user_id, 'gl_soundcloud_followers', $user['followers_count']);
                                update_user_meta($user_meta->user_id, 'gl_soundcloud_following', $user['followings_count']);
        

                            } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
                                //do nothing
                            }
                            
                            
                        }
                    }
                }
            }
        }

        //add record to the log
        $buddyStreamLog->log("Soundcloud imported " . $itemCounter . " tracks for " . count($user_metas) . " users.");

        //return number of items imported
        return $itemCounter;

    }
}