<?php
/**
 * Twitter class
 *
 */

class BuddystreamTwitter {
    
    protected $_geoEnabled = false;
    protected $_postContent;
    protected $_shortLink;
    protected $_username;
    protected $_badFilters;
    protected $_goodFilters;
    protected $_source;
    protected $_geoData;
    
    /*
     * Setter and getter for source
     * 
     */
   
     public function setSource($source)
     {
         $this->_source = $source;
     }

     public function getSource()
     {
         return $this->_source;
     }
    
     /*
      * Setter and getter for username
      * 
      */
     
     public function setUsername($username)
     {
         $this->_username = $username;
     }


     public function getUsername()
     {
         return $this->_username;
     }
     
     /*
      * Setter and getter for shortlinking.
      * 
      */

     public function setShortLink($shortLink)
     {
        $this->_shortLink = $shortLink;
     }

     public function getShortLink()
     {
         return $this->_shortLink;
     }
     
     /*
      * Setter and getter for geoData (not used for now)
      * 
      */

     public function setGeoData($geoData){
         $this->_geoData = $geoData;
     }

     public function getGeoData(){
         return $this->_geoData;
     }
     
     
     /*
      * Setter and getter for post content (never more due 140 char restriction Twitter)
      * 
      */
     
     public function setPostContent($content)
     {
         $content = stripslashes($content);
         $content = str_replace("#twitter", "", $content);
         $content = strip_tags($content);

         //shorten message to max 140
         if(strlen($content.' '.$this->getShortLink()) > 140){
             $maxChar = 137-strlen($this->getShortLink());
             $content = substr($content,0,$maxChar).'...'.$this->getShortLink();
         }else{
             $content =  $content.' '.$this->getShortLink();
         }

         $this->_postContent = $content;
     }
     
     public function getPostContent(){
         return $this->_postContent;
     }
     
     
     /*
      * Setter and getter for bad filters
      * 
      */
     
     public function setBadFilters($badfilters){
        $this->_badFilters = $badfilters;
     }

     public function getBadFilters(){
         return $this->_badFilters;
     }

     
     /*
      * Setter and getter for good filters
      * 
      */
     
     public function setGoodFilters($goodfilters){
         $this->_goodFilters = $goodfilters;
     }

     public function getGoodFilters(){
         return $this->_goodFilters;
     }

     
     /*
      * Filter out unwanted tweets
      * 
      */
     public function filterTweets($items)
     {
         $items = simplexml_load_string($items);
         foreach($items as $tweet){

            if($_geoEnabled){
                if($tweet->place->id){
                    $geoCounter++;
                }
                 $xml = $tweet;
                 foreach ($xml->getNamespaces(true) as $prefix => $ns) {
                    $xml->registerXPathNamespace($prefix, $ns);
                    $geo = $xml->xpath('//georss:point');
                 }

                 if($geo[$geoCounter] != ""){
                     $geoData[] =  array(
                            "id" => $tweet->id,
                            "coordinates" => $geo[$geoCounter]
                         );
                     $geoCounter++;
                  }

                  $geo = "";
              }

                //checkvar
                $filter1  = 1;
                $filter2  = 1;

                //not from same source
               if(!strpos($tweet->source,$this->getSource())){

                 //only allow if filter is in it
                   if($this->getGoodFilters()){
                       foreach(explode(",",$this->getGoodFilters()) as $filter){
                            if(preg_match("/".$filter."/i",strtolower($tweet->text))){
                                $filter1 = 1;
                            }else{
                                $filter1 = 0;
                            }
                        }
                   }

                //deny when having one of the badfilters in it
                   if($this->getBadFilters() && $filter1 == 1){
                        foreach(explode(",",$this->getBadFilters()) as $filter){
                            if(preg_match("/".$filter."/i",strtolower($tweet->text))){
                                $filter2 = 0;
                            }
                        }
                   }

                if($filter1== 1 && $filter2== 1){
                    $tweets[] = $tweet;
                }
               }
            }
                
            $this->setGeoData($geoData);
            return $tweets;
        }       
}