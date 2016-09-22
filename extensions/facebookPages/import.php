<?php
/**
 * Import starter
 */

function BuddystreamFacebookPagesImportStart()
{
    $importer = new BuddyStreamFacebookPagesImport();
    return $importer->doImport();
}

/**
 * Facebook Pages Import Class
 */

class BuddyStreamFacebookPagesImport
{

    //do the import
    public function doImport()
    {

        global $wpdb;

        $buddyStreamLog = new BuddyStreamLog();
        $buddyStreamFilters = new BuddyStreamFilters();

        $itemCounter = 0;
        $facebookPagesFollowers = array ();

        $user_metas = $wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='facestream_session_key';");

        if ($user_metas) {

            foreach ($user_metas as $user_meta) {

                //get the privacy level of importing
                $privacyLevel = "EVERYONE";

                //admin setting for default privacy setting (by default on ALL_FRIENDS).
                if (get_site_option("buddystream_facebook_privacy_setting")) {
                    $privacyLevel = "ALL_FRIENDS";
                }

                //user setting for privacy settings
                if (get_user_meta($user_meta->user_id, 'buddystream_facebook_privacy_friends', 1)) {
                    $privacyLevel = "ALL_FRIENDS";
                }

                //check for daylimit
                $limitReached = $buddyStreamFilters->limitReached('facebook', $user_meta->user_id);

                if (!$limitReached && get_site_option('facestream_user_settings_syncpagesbp') && get_user_meta($user_meta->user_id, 'buddystream_facebook_syncpage', 1) && get_user_meta($user_meta->user_id, 'buddystream_facebook_pages', 1) && ! get_user_meta($user_meta->user_id, 'buddystream_facebook_reauth', 1)) {

                    //get the pages for this user
                    $facebookPages = get_user_meta($user_meta->user_id, 'buddystream_facebook_pages', 1);

                    //explode the pages
                    $facebookPagesArray = explode(',', $facebookPages);
                    foreach ($facebookPagesArray as $page) {

                        //explode page details
                        $pageArray = explode(':', $page);

                        //Handle the OAuth requests
                        $buddyStreamOAuth = new BuddyStreamOAuth();
                        $buddyStreamOAuth->setParameters(
                            array('client_id' => get_site_option("facestream_application_id"),
                                'client_secret' => get_site_option("facestream_application_secret"),
                                'access_token' => $pageArray[1]));

                        $items = $buddyStreamOAuth->oAuthRequest('https://graph.facebook.com/' . $pageArray[0] . '/feed?fields=picture,full_picture,type,message,description,name,application,from,id,created_time,privacy,link');
                        $items = json_decode($items);
                        
                        //update likes pages
                        $pageInfo = $buddyStreamOAuth->oAuthRequest('https://graph.facebook.com/' . $pageArray[0] . '?fields=likes,name,id');
                        $pageInfo = json_decode($pageInfo);
                        
                        $facebookPageFollowers = $pageInfo->id . ':' . $pageInfo->name . ':' . $pageInfo->likes; 
                        array_push($facebookPagesFollowers, $facebookPageFollowers);
                        
                        
                        //we have a error, set re-authenticate to true
                        if(strpos(" " . $items->error->message, "Error")){
                            //update_user_meta($user_meta->user_id,"buddystream_facebook_reauth", true);
                        }

                        if (isset($items->data)) {
                            foreach ($items->data as $item) {

							//print_r ($item);
							//echo '<br><br><br>';
							
                                //from me
                                $fromMe = false;
                                if ($item->from->id == $pageArray[0]) {
                                    $fromMe = true;
                                }

                                //check day limit
                                $limitReached = $buddyStreamFilters->limitReached('facebook', $user_meta->user_id);
                                if (!$limitReached && $fromMe && ($item->privacy->value == "EVERYONE" OR $item->privacy->value == $privacyLevel)) {

                                    if (!isset($item->message)) {
                                        $item->message = "";
                                    }

                                    if (!isset($item->description)) {
                                        $item->description = "";
                                    }

                                    if (!isset($item->picture)) {
                                        $item->picture = "";
                                    }
									
									if (!isset($item->full_picture)) {
                                        $item->full_picture = "";
                                    }

                                    if (!isset($item->name)) {
                                        $item->name = "";
                                    }

                                    //shorten the message/description
                                    $message = "";
                                    $message = $item->message;
                                    if (strlen($message) > 400) {
                                        $message = substr($message, 0, 400) . "...";
                                    }

                                    if (empty($message)) {
                                        $message = $item->description;
                                        if (strlen($message) > 400) {
                                            $message = substr($message, 0, 400) . "...";
                                        }
                                    }

                                    //reset the content
                                    $content = "";

                                    //are we allowed to import this type
                                    if ($item->type == "status" && get_site_option("facestream_user_settings_syncupdatesbp") == "on") {
                                        $content = $message;
                                    }

                                    if ($item->type == "photo" && get_site_option("facestream_user_settings_syncphotosbp") == "on") {

                                        $fullSize = $item->full_picture;
                                        //$fullSize = str_replace("_s", "_n", $fullSize);
                                        //$fullSize = str_replace("_z", "_n", $fullSize);
                                        $content = '<a href="' . $fullSize . '" rel="lightbox" class="bs_lightbox"><img src="' . $item->full_picture . '"></a>' . $message;
                                    }


                                    if ($item->type == "link" && get_site_option("facestream_user_settings_synclinksbp") == "on") {

                                        if ($item->full_picture) {
                                            $imgArray = explode("=", $item->full_picture);
                                            $imgArray = array_reverse($imgArray);
                                        }

                                        $content = '<a href="' . urldecode($imgArray[0]) . '" target="_blank"><img src="' . $item->full_picture . '"></a><strong><a href="' . $item->link . '" target="_new" rel="external">' . $item->name . '</a></strong><br/>' . $message;
                                    }

                                    if ($item->type == "video" && get_site_option("facestream_user_settings_syncvideosbp") == "on") {

                                        if ($item->attribution != "YouTube") {
                                            $content = '<a href="' . $item->link . '" target="_blank"><img src="' . $item->full_picture . '"></a>' . $message;
                                        }
                                    }

                                    //check if good filter passes
                                    $goodFilters = get_site_option('facestream_filter');
                                    $goodFilter = $buddyStreamFilters->searchFilter($content, $goodFilters, false, true, true);

                                    //check if bad filter passes
                                    $badFilters = get_site_option('facestream_filterexplicit');
                                    $badFilter = $buddyStreamFilters->searchFilter($content, $badFilters, true);

                                    //check if source filter passes
                                    $sourceFilter = false;
                                    if (isset($item->application->id)) {
                                        $sourceFilter = $buddyStreamFilters->searchFilter(get_site_option("facestream_application_id"), $item->application->id, true);
                                    }

                                    //no filters set so just import everything
                                    if (!get_site_option('facestream_filter')) {
                                        $goodFilter = true;
                                    }

                                    if (!get_site_option('facestream_filterexplicit')) {
                                        $badFilter = false;
                                    }

                                    //check of item does not exist.
                                    if ($goodFilter && !$badFilter && !$sourceFilter) {

                                        $returnCreate = buddystreamCreateActivity(array(
                                                'user_id' => $user_meta->user_id,
                                                'extension' => 'facebook',
                                                'type' => 'Facebook page ' . $item->type,
                                                'content' => $content,
                                                'item_id' => $item->id,
                                                'raw_date' => gmdate('Y-m-d H:i:s', strtotime($item->created_time)),
                                                'actionlink' => 'http://www.facebook.com/' . $pageArray[0] . '/',
                                            )
                                        );

                                        if ($returnCreate) {
                                            $itemCounter++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    update_user_meta($user_meta->user_id, 'gl_facebook_pages_followers', implode(',', $facebookPagesFollowers));
                }

                unset($buddyStreamOAuth, $items);
            }
        }

        //add record to the log
        $buddyStreamLog->log("Facebook Pages imported " . $itemCounter . " items for " . count($user_metas) . " users.");

        //return number of items imported
        return $itemCounter;
    }
}