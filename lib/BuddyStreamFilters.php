<?php

/**
 * Class for filtering
 */

class BuddyStreamFilters{

    /**
    * Check if the user has reached the daylimit
    * 
    * @param string $extension
    * @param int $userId
    * @param int $maxItems
    * @return boolean 
    */
    
    public function limitReached($extension, $userId){

        //different day ot no day set, set the day and the counter to 0;
        if (get_user_meta($userId, 'buddystream_' . $extension . '_counterdate', 1) != date('dmy')) {
            update_user_meta($userId, 'buddystream_' . $extension . '_daycounter', '0');
            update_user_meta($userId, 'buddystream_' . $extension . '_counterdate', date('dmy'));
        }

        //max items per day
        if (get_site_option('buddystream_' . $extension . '_user_settings_maximport')) {
            if (get_user_meta($userId, 'buddystream_' . $extension . '_daycounter',1) <= get_site_option('buddystream_' . $extension . '_user_settings_maximport')) {
                return false;
            }
        }else{
            return false;
        }

        return true;
    }


    /**
    * Update the user daylimit for extension by one
    * 
    * @param string $extension
    * @param int $userId 
    */
    
    public function updateDayLimitByOne($extension, $userId){
        $currentDayCounter = get_user_meta($userId, 'buddystream_' . $extension . '_daycounter',1);
        $newDayCounter = $currentDayCounter+1;

        update_user_meta($userId, 'buddystream_' . $extension . '_daycounter', $newDayCounter);
    }


    /**
    * Create a well formated content to post to a network
    * 
    * @param string $content
    * @param init $limit
    * @return string 
    */
    
    public function filterPostContent($content, $shortLink = "", $limit = null){

        $content = stripslashes($content);
        $content = BuddyStreamFilters::removeHashTags($content);
        $content = strip_tags($content);

        if($limit){
            if(strlen($content . ' ' . $shortLink) > $limit){
                $maxChar = $limit-strlen($shortLink);
                $content = substr($content, 0, $maxChar).'...' . $shortLink;
            }else{
                $content =  $content . ' ' . $shortLink;
            }
        }

            return $content;
        }

        
    /**
    * Find filters in a string
    * 
    * @param string $content
    * @param string $filters (comma, seperated)
    * @return boolean 
    */  
        
    function filterPass($content, $filters = null){

        if(!$filters){
            return true;
        }

        $content = strip_tags($content);
        foreach(explode(",", $filters) as $filterValue){
            if($filterValue){
                $filterValue = trim($filterValue);
                $filterValue = str_replace('/','',$filterValue);
                
                if(preg_match('/'.$filterValue.'/', $content) == 0)
                {
                    return false;
                }
            }
        }

        return true;
    }     
    
    /**
    * Find filters in a string
    * 
    * @param string $content
    * @param string $filters (comma, seperated)
    * @return boolean 
    */  
    
    public function filterFail($content, $filters = null){

        if(!$filters){
            return false;
        }

        $content = strip_tags($content);
        foreach(explode(",", $filters) as $filtervalue){
            if($filtervalue){

                $filtervalue = trim($filtervalue);
                $filtervalue = str_replace('/','',$filtervalue);
                
                if(preg_match('/'.$filtervalue.'/', $content) > 0)
                {
                    return true;
                }
            }
        }

        return false;
    }     


   /**
    * Remove all hashtags from input
    * @param string $input
    * @return string $input
    */
    
    public function removeHashTags($input){
        
        foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extention){

            if($extention['hashtag']){
                $arrHashtags = explode(",",$extention['hashtag']);
                foreach ($arrHashtags as $hashtag){   
                    $input = str_replace($hashtag,"",$input);
                }
            }
        }
        return $input;
    }
    
    
    /**
     * Extract a string
     */
   
    public function extractString($str, $start, $end){

        $str_low = strtolower($str);
        $pos_start = strpos($str_low, $start);

        if($pos_start != false){
            $pos_end = strpos($str_low, $end, ($pos_start + strlen($start)));
            if ( ($pos_start !== false) && ($pos_end !== false) ){
                $pos1 = $pos_start + strlen($start);
                $pos2 = $pos_end - $pos1;
                return substr($str, $pos1, $pos2);
            }
        }else{
            return false;
        }
    }
}