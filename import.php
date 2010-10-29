<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//require "/home/admin/domains/buddystream.net/public_html/wp-load.php";


function microtime_float_import() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start_cron = microtime_float_import();
if (get_site_option('buddystream_server_cron')) {
    $limit = 100;
} else {
    $limit = 2;
}
  
if (get_site_option("buddystream_twitter_power")) {
    if (get_site_option("tweetstream_consumer_key")) {
        include_once "classes/twitter/BuddyStreamTwitterImport.php";
        $twitterImport = new BuddyStreamTwitterImport();
        $twitterImport->doImport($limit);
        echo "Twitter imported\n";
    }
}

if (get_site_option("buddystream_facebook_power")) {
    if (get_site_option("facestream_application_id")) {
        include_once "classes/facebook/BuddyStreamFacebookImport.php";
        $facebookImport= new BuddyStreamFacebookImport();
        $facebookImport->doImport($limit);
        echo "Facebook imported\n";
    }
}

if (get_site_option("buddystream_flickr_power")) {
    if (get_site_option("bs_flickr_api_key")) {
        include_once "classes/flickr/BuddyStreamFlickrImport.php";
        $flickrImport= new BuddyStreamFlickrImport();
        $flickrImport->doImport($limit);
        echo "Flickr imported\n";
    }
}

if (get_site_option("buddystream_lastfm_power")) {
    include_once "classes/lastfm/BuddyStreamLastfmImport.php";
    $lastfmImport= new BuddyStreamLastfmImport();
    $lastfmImport->doImport($limit);
    echo "Last.fm imported\n";
}

if (get_site_option("buddystream_youtube_power")) {
    include_once "classes/youtube/BuddyStreamYoutubeImport.php";
    $youtubeImport= new BuddyStreamYoutubeImport();
    $youtubeImport->doImport($limit);
    echo "Youtube imported\n";
}

$time_end_cron = microtime_float_import();
$time_cron = $time_end_cron - $time_start_cron;

update_site_option("buddystream_cron_stamp",current_time('mysql'));
update_site_option("buddystream_cron_runtime",$time_cron);

echo "\n".$time_cron." seconds\n";
die("Buddystream cron done loading");

?>
