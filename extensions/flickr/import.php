<?php
/**
 * Import starter
 */

function BuddystreamFlickrImportStart()
{
    $importer = new BuddyStreamFlickrImport();
    return $importer->doImport();
}

/**
 * Flickr Import Class
 */

class BuddyStreamFlickrImport
{

    public function doImport()
    {

        global $bp, $wpdb;

        $buddyStreamLog = new BuddyStreamLog();
        $buddyStreamFilters = new BuddyStreamFilters();

        $itemCounter = 0;

        $user_metas = $wpdb->get_results("SELECT user_id
                        FROM $wpdb->usermeta WHERE
                        meta_key='bs_flickr_username'
                        ORDER BY meta_value;");

        if ($user_metas) {
            foreach ($user_metas as $user_meta) {

                //check for daylimit
                $limitReached = $buddyStreamFilters->limitReached('flickr', $user_meta->user_id);

                if (!$limitReached && get_user_meta($user_meta->user_id, 'bs_flickr_username', 1)) {

                    $items = null;

                    //get the user id
                    $url = 'http://api.flickr.com/services/rest/?method=flickr.urls.lookupuser&api_key=' . get_site_option("bs_flickr_api_key") . '&url=' . urlencode('http://www.flickr.com/photos/' . get_user_meta($user_meta->user_id, 'bs_flickr_username', 1));
                    $buddystreamCurl = new BuddyStreamCurl();
                    $curlContent = $buddystreamCurl->getContentFromUrl($url);
                    $response = simplexml_load_string($curlContent);

                    //get the photos
                    $photosUrl = 'http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=' . get_site_option("bs_flickr_api_key") . '&user_id=' . $response->user['id'] . "&extras=date_upload,url_m,url_t,description";
                    $curlContent = $buddystreamCurl->getContentFromUrl($photosUrl);
                    $items = simplexml_load_string($curlContent);

                    if ($items->photos->photo) {
                        foreach ($items->photos->photo as $item) {

                            //check daylimit
                            $limitReached = $buddyStreamFilters->limitReached('flickr', $user_meta->user_id);

                            if (!$limitReached) {

                                if (!$item['title'] or empty($item['title'])) {
                                    $item['title'] = "";
                                }

                                if (!$item['description'] or empty($item['description'])) {
                                    $item['description'] = "";
                                }

                                $content = '<a href="' . $item["url_m"] . '" class="bs_lightbox" id="' . $item['id'] . '" title="' . $item['title'] . '"><img src="' . $item["url_t"] . '" title="' . $item["title"] . '"></a> ' . $item["title"] . " " . $item["description"];

                                $returnCreate = buddystreamCreateActivity(array(
                                        'user_id' => $user_meta->user_id,
                                        'extension' => 'flickr',
                                        'type' => 'Flickr photo',
                                        'content' => $content,
                                        'item_id' => $item['id'],
                                        'raw_date' => gmdate('Y-m-d H:i:s', (int)$item["dateupload"]),
                                        'actionlink' => 'http://www.flickr.com/photos/' . $item["owner"]
                                    )
                                );

                                if ($returnCreate) {
                                    $itemCounter++;
                                }

                            }
                        }
                    }else{
                        if($items->err){
                            delete_user_meta($user_meta->user_id, 'bs_flickr_username');
                        }
                    }
                }
            }
        }
        //add record to the log
        $buddyStreamLog->log("Flickr imported " . $itemCounter . " photo's for " . count($user_metas) . " users.");

        //return number of items imported
        return $itemCounter;
    }
}
