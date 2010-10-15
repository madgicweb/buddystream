<?php


class BuddyStreamTwitterImport {

    public function doImport($limit) {

        global $bp, $wpdb;

        if (get_site_option('tweetstream_user_settings_syncbp') == 0) {
            include_once "classes/twitter/BuddystreamTwitter.php";

            $user_metas = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT user_id
                FROM $wpdb->usermeta where
                meta_key='tweetstream_token'
                order by meta_value LIMIT ".$limit.";"
                    )
            );

            if ($user_metas) {
                foreach ($user_metas as $user_meta) {

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

                   //timestamp must be older then 5 minutes!
                   if(!get_usermeta($user_meta->user_id, 'tweetstream_stamp')){
                       update_usermeta($user_meta->user_id, 'tweetstream_stamp',date('d-m-Y H:i:s'));
                   }

                    $tago = time() - strtotime(get_usermeta($user_meta->user_id, 'tweetstream_stamp'));
                    if ($tago > 300) {
                        $import = 1;
                    }
                    //end time check
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
                        $tweets = $twitter->getTweets();

                        if (is_array($tweets)) {
                            foreach ($tweets as $tweet) {

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $tweet->id),'show_hidden' => true));
                                if (!$activity_info ['activities'][0]->id) {
                                    //create new activity instance
                                    $activity = new BP_Activity_Activity ();
                                    $activity->user_id = $user_meta->user_id;
                                    $activity->component = "tweetstream";
                                    $activity->type = "tweet";

                                    //slugstuff
                                    if (!defined('BP_ENABLE_ROOT_PROFILES')) {
                                        $slug = BP_MEMBERS_SLUG;
                                    }
                                    
                                   $activity->action = '<a href="' . $bp->root_domain . '/' . $slug . '/' . bp_core_get_username($user_meta->user_id) . '/" title="' . bp_core_get_username($user_meta->user_id) . '">' . bp_core_get_user_displayname($user_meta->user_id) . '</a> <a href="http://www.twitter.com/' . str_replace("@", "", get_usermeta($user_meta->user_id, 'tweetstream_mention')) . '"><img src="' . WP_PLUGIN_URL . '/buddystream/images/twitter/icon-small.png"></a> ' . __('posted a', 'tweetstream_lang') . ' <a href="http://www.twitter.com/' . str_replace("@", "", get_usermeta($user_meta->user_id, 'tweetstream_mention')) . '">' . __('tweet', 'buddystream_lang') . '</a>:';
                                   

                                    $activity->content = $tweet->text;
                                    $activity->secondary_item_id = $tweet->id;
                                    $activity->date_recorded = gmdate('Y-m-d H:i:s', strtotime($tweet->created_at));


                                    if(get_site_option('bs_twitter_hide_sitewide')){
                                        $activity->hide_sitewide = 1;
                                    }else{
                                        $activity->hide_sitewide = 0;
                                    }

                                    //check if item does not exist in the blacklist
                                    if (!preg_match("/".$tweet->id."/i", get_usermeta($user_meta->user_id, 'buddystream_blacklist_ids'))) {
                                        $activity->save();
                                    }

                                    if (get_usermeta($user_meta->user_id, 'tweetstream_counterdate') != date('d-m-Y')) {
                                        update_usermeta($user_meta->user_id, 'tweetstream_daycounter', '0');
                                        update_usermeta($user_meta->user_id, 'tweetstream_counterdate', date('d-m-Y'));
                                    }
                                    update_usermeta((int) $user_meta->user_id, 'tweetstream_daycounter', get_usermeta($user_meta->user_id, 'tweetstream_daycounter') + 1);
                                }
                            }
                        }
                    }
                }
            }
            update_usermeta($user_meta->user_id, 'tweetstream_stamp', date('d-m-Y H:i:s'));
        }
    }

}