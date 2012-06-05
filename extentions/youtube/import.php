<?php
/**
 * Import starter
 */

function BuddystreamYoutubeImportStart()
{
    $importer = new BuddyStreamYoutubeImport();
    return $importer->doImport();
}

/**
 * Youtube Import Class
 */

class BuddyStreamYoutubeImport
{

    public function doImport()
    {

        global $bp, $wpdb;

        require_once (ABSPATH . WPINC . '/class-feed.php');

        $itemCounter = 0;

        $user_metas = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT user_id
                        FROM $wpdb->usermeta where
                        meta_key='bs_youtube_username'
                        order by meta_value;"
            )
        );

        if ($user_metas) {
            foreach ($user_metas as $user_meta) {

                //check for daylimit
                $limitReached = BuddyStreamFilters::limitReached('youtube', $user_meta->user_id);

                if (!$limitReached && get_user_meta($user_meta->user_id, 'bs_youtube_username', 1)) {

                    //get these urls for import
                    $importUrls = array('https://gdata.youtube.com/feeds/api/users/' . get_user_meta($user_meta->user_id, 'bs_youtube_username', 1) . '/uploads',
                        'https://gdata.youtube.com/feeds/api/users/' . get_user_meta($user_meta->user_id, 'bs_youtube_username', 1) . '/favorites');

                    foreach ($importUrls as $importUrl) {

                        $items = null;
                        $feed = new SimplePie();
                        $feed->set_feed_url($importUrl);
                        $feed->set_cache_class('WP_Feed_Cache');
                        $feed->set_file_class('WP_SimplePie_File');
                        $feed->set_cache_duration(0);
                        do_action_ref_array('wp_feed_options', array(&$feed, $importUrl));
                        $feed->init();
                        $feed->handle_content_type();

                        if ( ! $feed->errors) {
                            $items = $feed->get_items();
                        }

                        if ($items) {
                            foreach ($items as $item) {

                                $limitReached = BuddyStreamFilters::limitReached('youtube', $user_meta->user_id);

                                // get video player URL
                                $link = $item->get_permalink();

                                //get the video id from player url
                                $videoIdArray = explode("=", $link);
                                $videoId = $videoIdArray[1];
                                $videoId = str_replace("&feature", "", $videoId);
                                $videoId = str_replace("&amp;feature", "", $videoId);

                                //get the thumbnail
                                $thumbnail = "http://i.ytimg.com/vi/" . $videoId . "/0.jpg";

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $user_meta->user_id . "_" . $videoId), 'show_hidden' => true));
                                if (!$activity_info['activities'][0]->id && !$limitReached) {

                                    $description = "";
                                    $description = $item->get_content();
                                    if (strlen($description) > 400) {
                                        $description = substr($description, 0, 400) . "... <a href='http://www.youtube.com/watch/?v=" . $videoId . "'>read more</a>";
                                    }

                                    $content = '<a href="http://www.youtube.com/watch/?v=' . $videoId . '" class="bs_lightbox" id="' . $videoId . '" title="' . $item->get_title() . '"><img src="' . $thumbnail . '"></a><b>' . $item->get_title() . '</b> ' . $description;

                                    //pre convert date
                                    $ts = strtotime($item->get_date());

                                    $returnCreate = buddystreamCreateActivity(array(
                                            'user_id' => $user_meta->user_id,
                                            'extention' => 'youtube',
                                            'type' => 'Youtube video',
                                            'content' => $content,
                                            'item_id' => $videoId,
                                            'raw_date' => date("Y-m-d H:i:s", $ts),
                                            'actionlink' => 'http://www.youtube.com/' . get_user_meta($user_meta->user_id, 'bs_youtube_username', 1)
                                        )
                                    );

                                    if($returnCreate){
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
        BuddyStreamLog::log("Youtube imported " . $itemCounter . " video's.");

        //return number of items imported
        return $itemCounter;
    }
}