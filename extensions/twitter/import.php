    <?php
    /**
     * Import starter
     */

    function BuddystreamTwitterImportStart()
    {
        $importer = new BuddyStreamTwitterImport();
        return $importer->doImport();
    }

    /**
     * Twitter Import Class
     */

    class BuddyStreamTwitterImport
    {

        //do the import
        public function doImport()
        {

            global $bp, $wpdb;

            $buddyStreamLog = new BuddyStreamLog();
            $buddyStreamFilters = new BuddyStreamFilters();

            //item counter for in the logs
            $itemCounter = 0;

            if (get_site_option("tweetstream_consumer_key")) {
                if (get_site_option('tweetstream_user_settings_syncbp') == 0) {

                    $user_metas = $wpdb->get_results(
                        "SELECT user_id FROM $wpdb->usermeta WHERE meta_key='tweetstream_token'"
                    );

                    if ($user_metas) {
                        foreach ($user_metas as $user_meta) {

                            //check for
                            $limitReached = $buddyStreamFilters->limitReached('twitter', $user_meta->user_id);

                            if (!$limitReached && get_user_meta($user_meta->user_id, 'tweetstream_synctoac', 1) && ! get_user_meta($user_meta->user_id, 'buddystream_linkedin_reauth', 1)) {

                                //Handle the OAuth requests
                                $buddyStreamOAuth = new BuddyStreamOAuth();
                                $buddyStreamOAuth->setCallbackUrl($bp->root_domain);
                                $buddyStreamOAuth->setConsumerKey(get_site_option("tweetstream_consumer_key"));
                                $buddyStreamOAuth->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));
                                $buddyStreamOAuth->setAccessToken(get_user_meta($user_meta->user_id, 'tweetstream_token', 1));
                                $buddyStreamOAuth->setAccessTokenSecret(get_user_meta($user_meta->user_id, 'tweetstream_tokensecret', 1));

                                $items = $buddyStreamOAuth->oAuthRequest('https://api.twitter.com/1.1/statuses/user_timeline.json');
                                $items = json_decode($items);

                                if($items->error){
                                    update_user_meta($user_meta->user_id,"buddystream_twitter_reauth", true);
                                }

                                if ($items && !$items->error) {

                                    //update the user screen_name
                                    $screenName = '' . $items->status->user->screen_name[0];
                                    update_user_meta($user_meta->user_id, 'tweetstream_screenname', $screenName);

                                    //go through tweets
                                    foreach ($items as $tweet) {

                                        //check daylimit
                                        $limitReached = $buddyStreamFilters->limitReached('twitter', $user_meta->user_id);

                                        //check if good filter passes
                                        $goodFilters = get_site_option('tweetstream_filter');
                                        $goodFilter = $buddyStreamFilters->searchFilter($tweet->text, $goodFilters, false, true, true);

                                        //check if bad filter passes
                                        $badFilters = get_site_option('tweetstream_filterexplicit');
                                        $badFilter = $buddyStreamFilters->searchFilter($tweet->text, $badFilters, true);

                                        //no filters set so just import everything
                                        if (!get_site_option('tweetstream_filter')) {
                                            $goodFilter = true;
                                        }

                                        if (!get_site_option('tweetstream_filterexplicit')) {
                                            $badFilter = false;
                                        }

                                        //check if source filter passes
                                        $sourceFilter = $buddyStreamFilters->searchFilter($bp->root_domain, $tweet->source, true);
                                        if (!$limitReached && $goodFilter && !$badFilter && !$sourceFilter) {

                                            $content  = '';
                                            if(isset($tweet->entities->media)){
                                                foreach($tweet->entities->media as $media){
                                                    $content .= '<a href="' . urldecode($media->media_url) . '" class="bs_lightbox"><img src="' . $media->media_url . '"></a>';
                                                }
                                            }

                                            $content .= $tweet->text;
                                            $returnCreate = buddystreamCreateActivity(array(
                                                    'user_id' => $user_meta->user_id,
                                                    'extension' => 'twitter',
                                                    'type' => 'tweet',
                                                    'content' => $content,
                                                    'item_id' => $tweet->id,
                                                    'raw_date' => gmdate('Y-m-d H:i:s', strtotime($tweet->created_at)),
                                                    'actionlink' => 'http://www.twitter.com/' . $screenName . '/status/' . $tweet->id
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
                    }
                }
            }

            //add record to the log
            $buddyStreamLog->log("Twitter imported " . $itemCounter . " tweets for " . count($user_metas) . " users.");

            //return number of items imported
            return $itemCounter;

        }
    }