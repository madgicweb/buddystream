<?php
/**
 * Import for BuddyStream
 */
$incPath = str_replace("/wp-content/plugins/buddystream","",getcwd());

ini_set('include_path', $incPath);
include('wp-load.php');

function microtime_float_import() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

if(!get_option("bs_cronjob")){
    update_option("bs_cronjob","youtube");
    update_option("bs_cronjob_status","ready");
}

$time_start_cron = microtime_float_import();
update_option("buddystream_cron_stamp",current_time('mysql'));

    //import youtube
    if (get_option("bs_cronjob") == "youtube") {
        if (get_option("buddystream_twitter_power")) {
            if (get_option("tweetstream_consumer_key")) {
                include_once "classes/twitter/BuddyStreamTwitterImport.php";
                $twitterImport = new BuddyStreamTwitterImport();
                $timeTwitter = $twitterImport->doImport();
                echo "Twitter imported in ".$timeTwitter." seconds";
            }
        }
        update_option("bs_cronjob","twitter");
        die();
    }

    //import facebook
    if (get_option("bs_cronjob") == "twitter") {
        if (get_option("buddystream_facebook_power")) {
            if (get_option("facestream_application_id")) {
                include_once "classes/facebook/BuddyStreamFacebookImport.php";
                $facebookImport= new BuddyStreamFacebookImport();
                $timeFacebook = $facebookImport->doImport();
                echo "Facebook imported in ".$timeFacebook." seconds";
            }
        }
        update_option("bs_cronjob","facebook");
        die();
    }

    //import flickr
    if (get_option("bs_cronjob") == "facebook") {
        if (get_option("buddystream_flickr_power")) {
            if (get_option("bs_flickr_api_key")) {
                include_once "classes/flickr/BuddyStreamFlickrImport.php";
                $flickrImport= new BuddyStreamFlickrImport();
                $timeFlickr = $flickrImport->doImport();
                echo "Flickr imported in ".$timeFlickr." seconds";
            }
        }
        update_option("bs_cronjob","flickr");
        die();
    }

    //import lastfm
    if (get_option("bs_cronjob") == "flickr") {
        if (get_option("buddystream_lastfm_power")) {
            include_once "classes/lastfm/BuddyStreamLastfmImport.php";
            $lastfmImport= new BuddyStreamLastfmImport();
            $timeLastfm = $lastfmImport->doImport();
            echo "Last.fm imported in ".$timeLastfm." seconds";
        }
        update_option("bs_cronjob","lastfm");
        die();
    }

    //import youtube
    if (get_option("bs_cronjob") == "lastfm") {
        if (get_option("buddystream_youtube_power")) {
            include_once "classes/youtube/BuddyStreamYoutubeImport.php";
            $youtubeImport= new BuddyStreamYoutubeImport();
            $timeYoutube = $youtubeImport->doImport();
            echo "Youtube imported in ".$timeYoutube." seconds";
        }
        update_option("bs_cronjob","youtube");
        die();
    }