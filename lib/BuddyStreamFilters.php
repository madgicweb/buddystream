<?php

/**
 * Class for filtering
 */

class BuddyStreamFilters{

    /**
     * Check if the user has reached the day limit
     *
     * @param string $extension
     * @param int $userId
     * @internal param int $maxItems
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
    * Update the user day limit for extension by one
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
     * Create a well formatted content to post to a network
     *
     * @param string $content
     * @param string $shortLink
     * @param init $limit
     * @return string
     */
    
    public function filterPostContent($content, $shortLink = "", $limit = null){

        $content = stripslashes($content);
        $content = $this->removeHashTags($content);
        $content = strip_tags($content);

        if($limit){
            if(strlen($content . ' ' . $shortLink) > $limit){
                $maxChar = $limit-strlen($shortLink);
                $content = substr($content, 0, $maxChar).'...' . $shortLink;
            }else{
                $content =  $content . ' ' . $shortLink;
            }
        }

        //strip out acivity plus tags
        $content = str_replace("[bpfb_link]", "" , $content);
        $content = str_replace("[/bpfb_link]", "" , $content);
        $content = str_replace("[bpfb_video]", "" , $content);
        $content = str_replace("[/bpfb_video]", "" , $content);
        $content = str_replace("[/bpfb_images]", "" , $content);
        $content = str_replace("[bpfb_images]", "" , $content);
        $content = str_replace("[BPFB_LINK]", "" , $content);
        $content = str_replace("[/BPFB_LINK]", "" , $content);
        $content = str_replace("[BPFB_VIDEO]", "" , $content);
        $content = str_replace("[/BPFB_VIDEO]", "" , $content);
        $content = str_replace("[/BPFB_IMAGES]", "" , $content);
        $content = str_replace("[BPFB_IMAGES]", "" , $content);

        return $content;
    }


    /**
     * Find filter in content
     *
     * @param $content
     * @param null $filters
     * @param bool $returnOnFirst
     * @param bool $findAll
     * @param bool $returnDefault
     * @return bool
     */
    function searchFilter($content, $filters = null, $returnOnFirst = false, $findAll = false, $returnDefault = false){

        if( ! $filters){
            return $returnDefault;
        }

        $content = strip_tags($content);

        foreach(explode(",", $filters) as $filterValue){
            if($filterValue){
                $filterValue = trim($filterValue);
                $filterValue = str_replace('/','',$filterValue);

                if(preg_match('/'.$filterValue.'/', $content) > 0)
                {
                   if($returnOnFirst){
                      return true;
                   }

                   if($findAll){
                        $returnValue = true;
                   }

                }else{
                    if($findAll){
                        $returnValue = false;
                    }
                }
            }
        }

        return $returnValue;
    }


   /**
    * Remove all hash tags from input
    * @param string $input
    * @return string $input
    */
    
    public function removeHashTags($input){

        $buddyStreamExtensions = new BuddyStreamExtensions();
        foreach($buddyStreamExtensions->getExtensionsConfigs() as $extension){

            if($extension['hashtag']){
                $arrHashtags = explode(",",$extension['hashtag']);
                foreach ($arrHashtags as $hashtag){   
                    $input = str_replace($hashtag,"",$input);
                }
            }
        }

        return $input;
    }


    /**
     * Extract a string
     * @param $str
     * @param $start
     * @param $end
     * @return bool|string
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

        return false;
    }

}