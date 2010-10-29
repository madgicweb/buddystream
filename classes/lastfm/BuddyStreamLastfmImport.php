<?php

class BuddyStreamLastfmImport {

    public function doImport($limit) {

        global $bp, $wpdb;
        include_once "classes/lastfm/BuddyStreamLastfm.php";

            $user_metas = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT user_id
                        FROM $wpdb->usermeta where
                        meta_key='bs_lastfm_username'
                        order by meta_value LIMIT ".$limit.";"
                    )
            );

            if ($user_metas) {
                foreach ($user_metas as $user_meta) {

                    //daycounter date
                    if (get_usermeta($user_meta->user_id, 'bs_lastfm_counterdate') != date('d-m-Y')) {
                        update_user_meta($user_meta->user_id, 'bs_lastfm_daycounter', '0');
                        update_user_meta($user_meta->user_id, 'bs_lastfm_counterdate', date('d-m-Y'));
                    }

                    //max songs per day
                    if (get_site_option(
                            'bs_lastfm_user_settings_maximport'
                        ) != '') {
 
                        if (get_usermeta(
                                $user_meta->user_id,
                                'bs_lastfm_daycounter'
                            ) <= get_site_option(
                                'bs_lastfm_user_settings_maximport'
                            )
                        ) {
                            $import = 1;
                        } else {
                            $import = 0;
                        }
                    } else {
                        $import = 1;
                    }

                   //timestamp must be older then 5 minutes!
                   if(!get_usermeta($user_meta->user_id, 'bs_lastfm_stamp')){
                       update_usermeta($user_meta->user_id, 'bs_lastfm_stamp',date('d-m-Y H:i:s'));
                   }

                   $stamp = get_usermeta($user_meta->user_id, 'bs_lastfm_stamp')."";
                   $tago = time() - @strtotime($stamp);
                    if ($tago > 300) {
                        $import = 1;
                    }


                    echo $user_meta->user_id."|".$import."<hr>";

                    //end time check
                    if ($import == 1 && get_usermeta($user_meta->user_id, 'bs_lastfm_username') != "") {

                        $lastfm = new BuddyStreamLastfm();
                        $lastfm->setUsername(get_usermeta($user_meta->user_id, 'bs_lastfm_username'));
                        $songs = $lastfm->getRecentTracks();

                        if (is_object($songs)) {
                            foreach ($songs as $song) {

                                $sid = str_replace(" ","",$song->date);
                                $sid = str_replace(":","",$sid);
                                $sid = str_replace(",","",$sid);
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
                                
                                //extra check 8 minutes in the past and future do not import song!
                                $rangeStart = $sid-8;
                                $rangeEnd   = $sid+8;
                                $i          = $rangeStart;
                                $exist      = 0;

                                
                                while ($i <= $rangeEnd) {
                                    
                                    $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $i),'show_hidden' => true));
                                    if($activity_info['activities'][0]->id){

                                        $content = $activity_info['activities'][0]->content;
                                        $content = strip_tags($content);
                                        $content = strtolower($content);
                                        $content = str_replace("(","",$content);
                                        $content = str_replace(")","",$content);
                                        $content = str_replace("-","",$content);

                                        if (preg_match("/".$song->name."/i",$content)) {
                                            $exist = 1;
                                        }
                                    }

                                    $i++;
                                }


                                if ($exist == 0) {

                                    //create new activity instance
                                    $activity = new BP_Activity_Activity ();
                                    $activity->user_id = $user_meta->user_id;
                                    $activity->secondary_item_id = $sid;
                                    $activity->component = "lastfm";
                                    $activity->type = "lastfm";

                                    if (!defined('BP_ENABLE_ROOT_PROFILES')) {
                                        $slug = BP_MEMBERS_SLUG. '/';
                                    }

                                    $activity->action = '<a href="' . $bp->root_domain . '/' . $slug . bp_core_get_username($user_meta->user_id) . '/" title="'
                                        . bp_core_get_username($user_meta->user_id) . '">'
                                        . bp_core_get_user_displayname($user_meta->user_id)
                                        . '</a> <a href="http://www.lastfm.com/user/' .get_usermeta($user_meta->user_id,'bs_lastfm_username')
                                        . '" target="_blanc"><img src="' . WP_PLUGIN_URL . '/buddystream/images/lastfm/icon-small.png"></a> '
                                        . __('posted a', 'buddystream_lang') . ' <a href="http://www.lastfm.com/user/' .get_usermeta($user_meta->user_id,'bs_lastfm_username')
                                        . '" target="_blanc">' . __('recent song', 'buddystream_lang') . '</a>:';


                                    $activity->content = 'Just listened to: <a href="'.$song->url.'" target="_new">'.$song->artist." - ".$song->name. "</a>";
                                    $activity->date_recorded = gmdate('Y-m-d H:i:s',  strtotime($song->date));
                                    
                                    if(get_site_option('bs_lastfm_hide_sitewide')){
                                        $activity->hide_sitewide = 1;
                                    }else{
                                        $activity->hide_sitewide = 0;
                                    }
                                    
                                    //check if item does not exist in the blacklist
                                    if (!preg_match("/".$sid."/i", get_usermeta($user_meta->user_id, 'buddystream_blacklist_ids'))) {
                                        $activity->save();
                                    }
                                 
                                    update_user_meta($user_meta->user_id, 'bs_lastfm_daycounter', get_usermeta($user_meta->user_id, 'bs_lastfm_daycounter') + 1);
                                }
                        }
                            
                                }
                                update_user_meta($user_meta->user_id, 'bs_lastfm_stamp', date('d-m-Y H:i:s'));
                            }

                        }
                    }
    }
}