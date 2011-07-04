<?php
/**
 * Import starter
 */

function BuddystreamLastfmImportStart(){
    //check if api server is online
    if(buddystreamCheckNetwork("http://www.last.fm")){
        $importer = new BuddyStreamLastfmImport();
        $importer->doImport();
    }else{
        buddystreamLog(__("Last.fm API server offline at the moment.","buddystream_lastfm"),"error");
    }  
}

/**
 * Lastfm Import Class
 */

class BuddyStreamLastfmImport {

    public function doImport() {

        global $bp, $wpdb;
        $itemCounter = 0;
        
            $user_metas = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT user_id
                        FROM $wpdb->usermeta where
                        meta_key='bs_lastfm_username'
                        order by meta_value;"
                    )
            );

            if ($user_metas) {
                foreach ($user_metas as $user_meta) {

                    //daycounter reset
                    if (get_user_meta($user_meta->user_id, 'bs_lastfm_counterdate',1) != date('d-m-Y')) {
                        update_user_meta($user_meta->user_id, 'bs_lastfm_daycounter',1);
                        update_user_meta($user_meta->user_id, 'bs_lastfm_counterdate', date('d-m-Y'));
                    }

                    //max songs per day
                    if (get_site_option('bs_lastfm_user_settings_maximport') != '') {
                        if (get_user_meta($user_meta->user_id,'bs_lastfm_daycounter',1) <= get_site_option('bs_lastfm_user_settings_maximport')) {
                            $import = 1;
                        } else {
                            $import = 0;
                        }
                    } else {
                        $import = 1;
                    }

                    if ($import == 1 && get_user_meta($user_meta->user_id, 'bs_lastfm_username',1) != "") {

                        $lastfm = new BuddyStreamLastfm();
                        $lastfm->setUsername(get_user_meta($user_meta->user_id, 'bs_lastfm_username',1));
                        $songs = $lastfm->getRecentTracks();

                        if (is_object($songs)) {
                            foreach ($songs as $song) {

                                //max items
                                $max = 1;
                                if (get_site_option('bs_lastfm_user_settings_maximport') != '') {
                                    if (get_user_meta($user_meta->user_id,'bs_lastfm_daycounter',1) <= get_site_option('bs_lastfm_user_settings_maximport')) {
                                        $max = 0;
                                    }
                                }else{
                                    $max = 0;
                                }

                                //only import song that are older than 10 minutes!!!
                                $exist = 0;
                                if(date("d-m-Y", strtotime($song->date)) == date('d-m-Y')){
                                    $arrDate = explode(",",$song->date);
                                    $time = trim($arrDate[1]);
                                    $time = str_replace(":","",$time);
                                    if((date('Hi')-$time) <= 10){
                                        $exist = 1;
                                    }
                                }

                                $sid = str_replace(" ","",$song->date);
                                $sid = str_replace(":","",$sid);
                                $sid = str_replace(",","",$sid);
                                $sid = str_replace("-","",$sid);
                                $sid = strtoupper($sid);
                                $sid = str_replace("JAN","01",$sid);
                                $sid = str_replace("FEB","02",$sid);
                                $sid = str_replace("MAR","03",$sid);
                                $sid = str_replace("APR","04",$sid);
                                $sid = str_replace("MAY","05",$sid);
                                $sid = str_replace("JUN","06",$sid);
                                $sid = str_replace("JUL","07",$sid);
                                $sid = str_replace("AUG","08",$sid);
                                $sid = str_replace("SEP","09",$sid);
                                $sid = str_replace("OCT","10",$sid);
                                $sid = str_replace("NOV","11",$sid);
                                $sid = str_replace("DEC","12",$sid);

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $sid),'show_hidden' => true));
                                if($activity_info['activities'][0]->id){
                                    $exist = 1;
                                }

                                if ($exist == 0 && $max == 0) {
                                  
                                    buddystreamCreateActivity(array(
                                         'user_id'       => $user_meta->user_id,
                                         'extention'     => 'lastfm',
                                         'type'          => 'song',
                                         'content'       => 'Just listened to: <a href="'.$song->url.'" target="_new">'.$song->artist." - ".$song->name. "</a>",
                                         'item_id'       => $sid,
                                         'raw_date'      => gmdate('Y-m-d H:i:s', strtotime($song->date)),
                                         'actionlink'    => 'http://www.lastfm.com/user/' .get_user_meta($user_meta->user_id,'bs_lastfm_username',1)
                                        )
                                     );
                                    $itemCounter++;
                                    
                                }
                            }
                        }
                     }
                 }
              }
              //add record to the log
            buddystreamLog("Last.fm imported ".$itemCounter." songs.");
    }
}