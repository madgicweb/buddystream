<?php
/* 
 * Upgrade precedure
 */

global $wpdb;

/**
 * reset facebook daycounters
 */

$user_metas = $wpdb->get_results(
                $wpdb->prepare(
                   "SELECT user_id FROM $wpdb->usermeta where
                    meta_key='facestream_session_key'
                    order by meta_value;"
                )
            );


 foreach ($user_metas as $user){
    update_usermeta($user_meta->user_id, 'facestream_daycounter', 1);
 }

 /**
  * reset flickr daycounters
  */

$user_metas = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT user_id
                FROM $wpdb->usermeta where
                meta_key='bs_flickr_username'
                order by meta_value;"
            )
    );

 foreach ($user_metas as $user){
    update_usermeta($user_meta->user_id, 'bs_flickr_daycounter', 1);
 }

 /**
  * reset twitter daycounter
  */

$user_metas = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT user_id
                FROM $wpdb->usermeta where
                meta_key='tweetstream_token'
                order by meta_value;"
                    )
    );

foreach ($user_metas as $user){
    update_usermeta($user_meta->user_id, 'tweetstream_daycounter', 1);
 }

 /**
  * reset youtube daycounters
  */
$user_metas = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT user_id
                FROM $wpdb->usermeta where
                meta_key='bs_youtube_username'
                order by meta_value;"
            )
);

foreach ($user_metas as $user){
    update_usermeta($user_meta->user_id, 'bs_youtube_daycounter', 1);
 }

  /**
  * reset youtube daycounters
  */
$user_metas = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT user_id
                FROM $wpdb->usermeta where
                meta_key='bs_lastfm_username'
                order by meta_value;"
            )
);

foreach ($user_metas as $user){
    update_usermeta($user_meta->user_id, 'bs_lastfm_daycounter', 1);
 }


?>
