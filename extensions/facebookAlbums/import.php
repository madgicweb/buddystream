<?php
/**
 * Import starter
 */

function BuddystreamFacebookAlbumsImportStart()
{
    $importer = new BuddyStreamFacebookAlbumsImport();
    return $importer->doImport();
}

/**
 * Facebook Pages Import Class
 */

class BuddyStreamFacebookAlbumsImport
{

    public function doImport()
    {

        global $wpdb;

        $buddyStreamLog = new BuddyStreamLog();
        $buddyStreamFilters = new BuddyStreamFilters();

        $itemCounter = 0;

        $user_metas = $wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='facestream_session_key';");

        if ($user_metas) {

            foreach ($user_metas as $user_meta) {

                //get the privacy level of importing
                $privacyLevelAlbums = "everyone";

                //admin setting for default privacy setting (by default on ALL_FRIENDS).
                if (get_site_option("buddystream_facebook_privacy_setting")) {
                    $privacyLevelAlbums = "friends";
                }

                //user setting for privacy settings
                if (get_user_meta($user_meta->user_id, 'buddystream_facebook_privacy_friends', 1)) {
                    $privacyLevelAlbums = "friends";
                }

                //check for daylimit
                $limitReached = $buddyStreamFilters->limitReached('facebook', $user_meta->user_id);

                if (!$limitReached && get_site_option('facestream_user_settings_syncalbumsbp') && get_user_meta($user_meta->user_id, 'buddystream_facebook_syncalbum', 1) && get_user_meta($user_meta->user_id, 'buddystream_facebook_albums', 1) && ! get_user_meta($user_meta->user_id, 'buddystream_facebook_reauth', 1)) {

                    //get the pages for this user
                    $facebookAlbums = get_user_meta($user_meta->user_id, 'buddystream_facebook_albums', 1);

                    //explode the pages
                    $facebookAlbumsArray = explode(',', $facebookAlbums);

                    foreach ($facebookAlbumsArray as $album) {

                        //get all photos in this album.
                        $buddyStreamOAuth = new BuddyStreamOAuth();
                        $buddyStreamOAuth->setParameters(
                            array('client_id' => get_site_option("facestream_application_id"),
                                'client_secret' => get_site_option("facestream_application_secret"),
                                'access_token' => str_replace("&expires", "", get_user_meta($user_meta->user_id, 'facestream_session_key', 1))));


                        $fbAlbum = $buddyStreamOAuth->oAuthRequest('https://graph.facebook.com/' . $album);
                        $fbAlbum = json_decode($fbAlbum);

                        $photos = $buddyStreamOAuth->oAuthRequest('https://graph.facebook.com/' . $album . '/photos');
                        $photos = json_decode($photos);

                        //we have a error, set re-authenticate to true
                        if(strpos(" " . $photos->error->message, "Error")){
                            //update_user_meta($user_meta->user_id,"buddystream_facebook_reauth", true);
                        }

                        if ($photos->data) {
                            foreach ($photos->data as $photo) {

                                //check daylimit
                                $limitReached = $buddyStreamFilters->limitReached('facebook', $user_meta->user_id);

                                if (!$limitReached && ($fbAlbum->privacy == "everyone" OR $fbAlbum->privacy == $privacyLevelAlbums)) {

                                    //set the content
                                    $content = '';
                                    $content = '<a href="' . $photo->source . '" rel="lightbox" class="bs_lightbox"><img src="' . $photo->picture . '"></a>' . $photo->name;

                                    //check if good filter passes
                                    $goodFilters = get_site_option('facestream_filter');
                                    $goodFilter = $buddyStreamFilters->searchFilter($content, $goodFilters, false, true, true);

                                    //check if bad filter passes
                                    $badFilters = get_site_option('facestream_filterexplicit');
                                    $badFilter = $buddyStreamFilters->searchFilter($content, $badFilters, true);

                                    //no filters set so just import everything
                                    if (!get_site_option('facestream_filter')) {
                                        $goodFilter = true;
                                    }

                                    if (!get_site_option('facestream_filterexplicit')) {
                                        $badFilter = false;
                                    }

                                    //check of item does not exist.
                                    if ($goodFilter && !$badFilter) {

                                        $returnCreate = buddystreamCreateActivity(array(
                                                'user_id' => $user_meta->user_id,
                                                'extension' => 'facebook',
                                                'type' => 'Facebook photo',
                                                'content' => $content,
                                                'item_id' => $photo->id,
                                                'raw_date' => gmdate('Y-m-d H:i:s', strtotime($photo->created_time)),
                                                'actionlink' => $photo->link,
                                            )
                                        );

                                        if ($returnCreate) {
                                            $itemCounter++;
                                        }
                                    }
                                }
                            }
                        }

                        unset($buddyStreamOAuth, $photos);
                    }
                }
            }
        }

        //add record to the log
        $buddyStreamLog->log("Facebook Albums imported " . $itemCounter . "  items for " . count($user_metas) . " users.");

        //return number of items imported
        return $itemCounter;

    }
}