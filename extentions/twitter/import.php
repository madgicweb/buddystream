<?php
/**
 * Import starter
 */

function BuddystreamTwitterImportStart(){
    //check if api server is online
    if(buddystreamCheckNetwork("http://twitter.com")) {
        $importer = new BuddyStreamTwitterImport();
        $importer->doImport();
        
     }else{
        buddystreamLog(__("Twitter API server offline at the moment.","buddystream_twitter"),"error");
    }  
}

/**
 * Twitter Import Class
 */

class BuddyStreamTwitterImport{

    //enable or disable geo
   protected $_geoEnabled = false;
   
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
                     
                    //always start with import = 1
                    $import = 1;

                    //daycounter reset
                    if (get_user_meta($user_meta->user_id, 'tweetstream_counterdate', 1) != date('d-m-Y')) {
                        update_user_meta($user_meta->user_id, 'tweetstream_daycounter', 1);
                        update_user_meta($user_meta->user_id, 'tweetstream_counterdate', date('d-m-Y'));
                    }

                    //max tweets per day
                    if (get_site_option('tweetstream_user_settings_maximport') != '') {
                        if (get_user_meta($user_meta->user_id, 'tweetstream_daycounter',1) <= get_site_option('tweetstream_user_settings_maximport')) {
                            $import = 1;
                        } else {
                            $import = 0;
                        }
                    } else {
                        $import = 1;
                    }
                    
                    if ($import == 1  && get_user_meta($user_meta->user_id, 'tweetstream_synctoac', 1) == "1") {
                        
                        //Handle the OAuth requests
                        $buddystreamOAuth = new BuddyStreamOAuth();
                        $buddystreamOAuth->setCallbackUrl($bp->root_domain);
                        $buddystreamOAuth->setConsumerKey(get_site_option("tweetstream_consumer_key"));
                        $buddystreamOAuth->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));
                        $buddystreamOAuth->setAccessToken(get_user_meta($user_meta->user_id,'tweetstream_token', 1));
                        $buddystreamOAuth->setAccessTokenSecret(get_user_meta($user_meta->user_id,'tweetstream_tokensecret', 1));
                        
                        $items = $buddystreamOAuth->oAuthRequest('http://api.twitter.com/1/statuses/user_timeline.xml');
                        
                        //Handle the Twitter filtering
                        $twitter = new BuddystreamTwitter();
                        $twitter->setUsername(get_userdata($user_meta->user_id)->user_login);
                        $twitter->setSource($bp->root_domain);
                        $twitter->setGoodFilters(get_site_option('tweetstream_filter') . get_user_meta($user_meta->user_id, 'tweetstream_filtergood', 1));
                        $twitter->setBadFilters(get_site_option('tweetstream_filterexplicit') . get_user_meta($user_meta->user_id, 'tweettream_filterbad', 1));
                        $tweets = $twitter->filterTweets($items);
                       
                        if (is_array($tweets)) {
                            
                            //save geodata
                            if($this->_geoEnabled == true){
                                $geoData = $twitter->getGeoData();
                                if(is_array($geoData)){
                                    foreach($geoData as $geo){
                                        update_user_meta($user_meta->user_id, 'tweet_'.$geo["id"], str_replace(" ", ",", $geo["coordinates"]));
                                    }
                                }
                            }
                            
                            //go through tweets
                            foreach ($tweets as $tweet) {
                                
                                //max items
                                $max = 1;
                                if (get_site_option('tweetstream_user_settings_maximport') != '') {
                                    if (get_user_meta($user_meta->user_id,'tweetstream_daycounter', 1) <= get_site_option('tweetstream_user_settings_maximport')) {
                                        $max = 0;
                                    }
                                }else{
                                    $max = 0;
                                }

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $tweet->id),'show_hidden' => true));
                                if (!$activity_info ['activities'][0]->id && $max == 0) {

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
    buddystreamLog("Twitter imported ".$itemCounter." tweets.");
    
    }
}