<?php

/**
 * Class to provide logging
 */

class BuddyStreamLog{

   /**
    * Log a message to the BuddyStream log table
    * 
    * @global type $wpdb
    * @param string $message
    * @param string $type
    * @return boolean true
    */
    function log($message = "", $type="info"){
        global $wpdb;

        $results = $wpdb->get_results("SELECT count(*) as count FROM ".$wpdb->prefix."buddystream_log");

        if($results[0]->count >= 200){
            $wpdb->query("DELETE FROM ".$wpdb->prefix."buddystream_log ORDER BY id ASC LIMIT 1");            
        }
        
        $wpdb->insert($wpdb->prefix."buddystream_log", array('message' => $message , 'type' => $type));
        
        return true;
    }
}