<?php
/**
 * Import starter
 */

function BuddystreamLastfmImportStart()
{
    $importer = new BuddyStreamLastfmImport();
    return $importer->doImport();
}

/**
 * Lastfm Import Class
 */

class BuddyStreamLastfmImport
{

    public function doImport()
    {

        global $bp, $wpdb;

        $buddyStreamLog = new BuddyStreamLog();
        $buddyStreamFilters = new BuddyStreamFilters();

        $itemCounter = 0;

        $user_metas = $wpdb->get_results(
                "SELECT user_id
                        FROM $wpdb->usermeta WHERE
                        meta_key='bs_lastfm_username'
                        ORDER BY meta_value;"
        );

        if ($user_metas) {
            foreach ($user_metas as $user_meta) {

                //always start with import = true
                $import = true;

                //check daylimit
                $max = $buddyStreamFilters->limitReached('lastfm', $user_meta->user_id);

                if (!$max && $import && get_user_meta($user_meta->user_id, 'bs_lastfm_username', 1)) {

                    $url = 'http://ws.audioscrobbler.com/2.0/user/' . get_user_meta($user_meta->user_id, 'bs_lastfm_username', 1) . '/recenttracks.xml';
                    $buddystreamCurl = new BuddyStreamCurl();
                    $curlContent = $buddystreamCurl->getContentFromUrl($url);

                    $items = null;
                    if (strpos($curlContent, "ERROR:") > 0) {
                        $items = simplexml_load_string($curlContent);
                    }

                    if ($items) {
                        foreach ($items as $song) {

                            //only import song that are older than 10 minutes!!!
                            $exist = 0;
                            if (date("d-m-Y", strtotime($song->date)) == date('d-m-Y')) {
                                $arrDate = explode(",", $song->date);
                                $time = trim($arrDate[1]);
                                $time = str_replace(":", "", $time);
                                if ((date('Hi') - $time) <= 10) {
                                    $exist = 1;
                                }
                            }

                            $sid = str_replace(" ", "", $song->date);
                            $sid = str_replace(":", "", $sid);
                            $sid = str_replace(",", "", $sid);
                            $sid = str_replace("-", "", $sid);
                            $sid = strtoupper($sid);
                            $sid = str_replace("JAN", "01", $sid);
                            $sid = str_replace("FEB", "02", $sid);
                            $sid = str_replace("MAR", "03", $sid);
                            $sid = str_replace("APR", "04", $sid);
                            $sid = str_replace("MAY", "05", $sid);
                            $sid = str_replace("JUN", "06", $sid);
                            $sid = str_replace("JUL", "07", $sid);
                            $sid = str_replace("AUG", "08", $sid);
                            $sid = str_replace("SEP", "09", $sid);
                            $sid = str_replace("OCT", "10", $sid);
                            $sid = str_replace("NOV", "11", $sid);
                            $sid = str_replace("DEC", "12", $sid);

                            if (!$max) {

                                $returnCreate = buddystreamCreateActivity(array(
                                        'user_id' => $user_meta->user_id,
                                        'extension' => 'lastfm',
                                        'type' => 'last.fm track',
                                        'content' => 'Just listened to: <a href="' . $song->url . '" target="_new">' . $song->artist . " - " . $song->name . "</a>",
                                        'item_id' => $sid,
                                        'raw_date' => gmdate('Y-m-d H:i:s', strtotime($song->date)),
                                        'actionlink' => 'http://www.lastfm.com/user/' . get_user_meta($user_meta->user_id, 'bs_lastfm_username', 1)
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

        //add record to the log
        $buddyStreamLog->log("Last.fm imported " . $itemCounter . " songs        for " . count($user_metas) . " users.");

        //return number of items imported
        return $itemCounter;
    }
}