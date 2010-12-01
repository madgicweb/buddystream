<?php

class BuddyStreamTwitterImport {

    public function doImport() {

        global $bp, $wpdb;
        $time_start = microtime_float_import();

        if (get_site_option('tweetstream_user_settings_syncbp') == 0) {
            include_once "classes/twitter/BuddystreamTwitter.php";

            $user_metas = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT user_id
                FROM $wpdb->usermeta where
                meta_key='tweetstream_token'
                order by meta_value;"
                    )
            );

            if ($user_metas) {
                foreach ($user_metas as $user_meta) {

                    $import = 1;

                    //daycounter reset
                    if (get_usermeta($user_meta->user_id, 'tweetstream_counterdate') != date('d-m-Y')) {
                        update_usermeta($user_meta->user_id, 'tweetstream_daycounter', 1);
                        update_usermeta($user_meta->user_id, 'tweetstream_counterdate', date('d-m-Y'));
                    }

                    //max tweets per day
                    if (get_site_option(
                            'tweetstream_user_settings_maximport'
                        ) != '') {

                        if (get_usermeta(
                                $user_meta->user_id,
                                'tweetstream_daycounter'
                            ) <= get_site_option(
                                'tweetstream_user_settings_maximport'
                            )
                        ) {
                            $import = 1;
                        } else {
                            $import = 0;
                        }
                    } else {
                        $import = 1;
                    }

                    if ($import == 1 && get_usermeta($user_meta->user_id, 'tweetstream_token') != ""  && get_usermeta($user_meta->user_id, 'tweetstream_synctoac') == "1") {

                        //TWITTER
                        $twitter = new BuddystreamTwitter;
                        $twitter->setConsumerKey(get_site_option("tweetstream_consumer_key"));
                        $twitter->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));
                        $twitter->setAccessToken(get_usermeta($user_meta->user_id, 'tweetstream_token'));
                        $twitter->setAccessTokenSecret(get_usermeta($user_meta->user_id, 'tweetstream_tokensecret'));
                        $twitter->setSource($bp->root_domain);
                        $twitter->setGoodFilters(get_site_option('tweetstream_filter') . get_usermeta($user_meta->user_id, 'tweetstream_filtergood'));
                        $twitter->setBadFilters(get_site_option('tweetstream_filterexplicit') . get_usermeta($user_meta->user_id, 'tweettream_filterbad'));

                        //auth check for user is false reset user else get tweets
                        $tweets = $twitter->getTweets();
                        
                        if (is_array($tweets)) {
                            foreach ($tweets as $tweet) {

                                //max items
                                $max = 1;
                                if (get_site_option('tweetstream_user_settings_maximport') != '') {
                                    if (get_usermeta($user_meta->user_id,'tweetstream_daycounter') <= get_site_option('tweetstream_user_settings_maximport')) {
                                        $max = 0;
                                    }
                                }else{
                                    $max = 0;
                                }

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $tweet->id),'show_hidden' => true));
                                if (!$activity_info ['activities'][0]->id && $max == 0) {

                                    //create new activity instance
                                    $activity = new BP_Activity_Activity ();
                                    $activity->user_id = $user_meta->user_id;
                                    $activity->component = "tweetstream";
                                    $activity->type = "tweet";

                                    //slugstuff
                                    if (!defined('BP_ENABLE_ROOT_PROFILES')) {
                                        $slug = BP_MEMBERS_SLUG;
                                    }
                                    
                                   $activity->action = '<a href="' . $bp->root_domain . '/' . $slug . '/' . bp_core_get_username($user_meta->user_id) . '/" title="' . bp_core_get_username($user_meta->user_id) . '">' . bp_core_get_user_displayname($user_meta->user_id) . '</a> <a href="http://www.twitter.com/' . str_replace("@", "", get_usermeta($user_meta->user_id, 'tweetstream_mention')) . '"><img src="' . plugins_url() . '/buddystream/images/twitter/icon-small.png"></a> ' . __('posted a', 'tweetstream_lang') . ' <a href="http://www.twitter.com/' . str_replace("@", "", get_usermeta($user_meta->user_id, 'tweetstream_mention')) . '/status/'.$tweet->id.'">' . __('tweet', 'buddystream_lang') . '</a>:';
                                   $activity->content = $tweet->text;
                                   $activity->secondary_item_id = $tweet->id;
                                   $activity->date_recorded = gmdate('Y-m-d H:i:s', strtotime($tweet->created_at));

                                    if(get_site_option('bs_twitter_hide_sitewide')){
                                        $activity->hide_sitewide = 1;
                                    }else{
                                        $activity->hide_sitewide = 0;
                                    }

                                    //check if item does not exist in the blacklist
                                    if(get_usermeta($user_meta->user_id, 'buddystream_blacklist_ids')){
                                        if (!preg_match("/".$tweet->id."/i", get_usermeta($user_meta->user_id, 'buddystream_blacklist_ids'))) {
                                            $activity->save();
                                            update_usermeta($user_meta->user_id, 'tweetstream_daycounter', get_usermeta($user_meta->user_id, 'tweetstream_daycounter') + 1);
                                        }
                                    }else{
                                        $activity->save();
                                        update_usermeta($user_meta->user_id, 'tweetstream_daycounter', get_usermeta($user_meta->user_id, 'tweetstream_daycounter') + 1);
                                    }

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