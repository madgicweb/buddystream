    <?php
/**
 * Import starter
 */

function BuddystreamTwitterImportStart(){
    $importer = new BuddyStreamTwitterImport();
    return $importer->doImport();
}

/**
 * Twitter Import Class
 */

class BuddyStreamTwitterImport{

   //do the import 
   public function doImport() {
        
        global $bp, $wpdb;
        
        //item counter for in the logs
        $itemCounter = 0;
 
        if (get_site_option("tweetstream_consumer_key")) {
            if (get_site_option('tweetstream_user_settings_syncbp') == 0) {
            
                $user_metas = $wpdb->get_results(
                        $wpdb->prepare("SELECT user_id FROM $wpdb->usermeta where meta_key='tweetstream_token'")
                );

                if ($user_metas) {
                 foreach ($user_metas as $user_meta) {
                     
                    //always start with import = true
                    $import = true;
                    
                    //check for daylimit
                    $import = BuddyStreamFilters::limitReached('twitter', $user_meta->user_id);
                    
                    if (!$import && get_user_meta($user_meta->user_id, 'tweetstream_synctoac', 1)) {
                        
                        //Handle the OAuth requests
                        $buddystreamOAuth = new BuddyStreamOAuth();
                        $buddystreamOAuth->setCallbackUrl($bp->root_domain);
                        $buddystreamOAuth->setConsumerKey(get_site_option("tweetstream_consumer_key"));
                        $buddystreamOAuth->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));
                        $buddystreamOAuth->setAccessToken(get_user_meta($user_meta->user_id,'tweetstream_token', 1));
                        $buddystreamOAuth->setAccessTokenSecret(get_user_meta($user_meta->user_id,'tweetstream_tokensecret', 1));
                        
                        $items = $buddystreamOAuth->oAuthRequest('http://api.twitter.com/1/statuses/user_timeline.xml');
                        $items = simplexml_load_string($items);
                        
                        if ($items && !$items->error) {
                        
                            //update the user screen_name
                            $screenName = '';
                            $screenName = ''.$items->status->user->screen_name[0];
                            update_user_meta($user_meta->user_id,'tweetstream_screenname', $screenName);
                            
                            //go through tweets
                            foreach ($items as $tweet) {
                        
                                //check daylimit
                                $max = BuddyStreamFilters::limitReached('twitter', $user_meta->user_id);

                                //check if good filter passes
                                $goodFilterPass = BuddyStreamFilters::filterPass($tweet->text, get_site_option('tweetstream_filter').get_user_meta($user_meta->user_id,'tweetstream_filtergood', 1));

                                //check if bad filter passes
                                $badFilterPass = BuddyStreamFilters::filterFail($tweet->text, get_site_option('tweetstream_filterexplicit').get_user_meta($user_meta->user_id,'tweetstream_filterbad', 1));    

                                //check if source filter passes
                                $sourceFilterPass = BuddyStreamFilters::filterPass($bp->root_domain, $tweet->source);
                                
                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $tweet->id),'show_hidden' => true));
                                
                                if ($activity_info['activities'][0] == null && !$max&& $goodFilterPass && !$badFilterPass && !$sourceFilterPass) {
                                     
                                    buddystreamCreateActivity(array(
                                         'user_id'       => $user_meta->user_id,
                                         'extention'     => 'twitter',
                                         'type'          => 'tweet',
                                         'content'       => $tweet->text,
                                         'item_id'       => $tweet->id,
                                         'raw_date'      => gmdate('Y-m-d H:i:s', strtotime($tweet->created_at)),
                                         'actionlink'    => 'http://www.twitter.com/' . str_replace("@", "", get_user_meta($user_meta->user_id, 'tweetstream_mention',1)) . '/status/'.$tweet->id
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
        }
        
    //add record to the log
    BuddyStreamLog::log("Twitter imported ".$itemCounter." tweets.");
    
    //return number of items imported
    return $itemCounter;
    
    }
}