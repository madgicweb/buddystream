<?php
/**
 * Import starter
 */

function BuddystreamInstagramImportStart()
{
    $importer = new BuddyStreamInstagramImport();
    return $importer->doImport();
}

/**
 * Instagram Import Class
 */

class BuddyStreamInstagramImport
{

    //do the import
    public function doImport()
    {
        global $bp, $wpdb;

        $buddyStreamLog = new BuddyStreamLog();
        $buddyStreamFilters = new BuddyStreamFilters();

        //item counter for in the logs
        $itemCounter = 0;

        if (get_site_option("buddystream_instagram_consumer_key")) {

                $user_metas = $wpdb->get_results("SELECT user_id FROM ".$wpdb->usermeta." WHERE meta_key='buddystream_instagram_token'");

                if ($user_metas) {
                    foreach ($user_metas as $user_meta) {

                        //check for
                        $limitReached = $buddyStreamFilters->limitReached('instagram', $user_meta->user_id);

                        if (!$limitReached && get_user_meta($user_meta->user_id, 'buddystream_instagram_synctoac', 1)) {

                            //Handle the OAuth requests
                            $buddyStreamOAuth = new BuddyStreamOAuth();
                            $items = $buddyStreamOAuth->executeRequest('https://api.instagram.com/v1/users/'.get_user_meta($user_meta->user_id, 'buddystream_instagram_id', 1).'/media/recent/?&access_token='.get_user_meta($user_meta->user_id, 'buddystream_instagram_token', 1));
                            $items = json_decode($items);
                            $items = $items->data;

                            if ($items) {

                                //go through tweets
                                foreach ($items as $item) {

                                    //check daylimit
                                    $limitReached = $buddyStreamFilters->limitReached('instagram', $user_meta->user_id);

                                    //check if source filter passes
                                    if ( ! $limitReached) {

                                        $returnCreate = false;

                                        $content = '<a href="' . $item->images->standard_resolution->url . '" class="bs_lightbox" id="' . $item->id . '"><img src="' . $item->images->thumbnail->url . '"></a> '. $item->caption->text;

                                        $returnCreate = buddystreamCreateActivity(array(
                                                'user_id' => $user_meta->user_id,
                                                'extension' => 'instagram',
                                                'type' => 'Instagram',
                                                'content' => $content,
                                                'item_id' => $item->id,
                                                'raw_date' => gmdate('Y-m-d H:i:s', (int)$item->created_time),
                                                'actionlink' => $item->link
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

        //add record to the log
        $buddyStreamLog->log("Instagram imported " . $itemCounter . " images for " . count($user_metas) . " users.");

        //return number of items imported
        return $itemCounter;

    }
}