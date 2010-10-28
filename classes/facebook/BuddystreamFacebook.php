<?php

class BuddystreamFacebook
{
    protected $_callBackUrl;
    protected $_applicationKey;
    protected $_applicationId;
    protected $_applicationSecret;
    protected $_postContent;
    protected $_shortLink;
    protected $_username;
    protected $_accessToken;
    protected $_accessTokenSecret;
    protected $_source;
    protected $_badFilters;
    protected $_goodFilters;
    protected $_code;
    protected $_userId;
    
      public function setCallbackUrl($callBackUrl)
      {
        $this->_callBackUrl = $callBackUrl;
      }

      public function getCallbackUrl()
      {
        return $this->_callBackUrl;
      }

      public function setApplicationKey($applicationKey)
      {
        $this->_applicationKey = $applicationKey;
      }

      public function getApplicationKey()
      {
        return $this->_applicationKey;
      }

      public function setApplicationId($applicationId)
      {
        $this->_applicationId = $applicationId;
      }

      public function getApplicationId()
      {
        return $this->_applicationId;
      }

      public function setApplicationSecret($applicationSecret)
      {
        $this->_applicationSecret = $applicationSecret;
      }

      public function getApplicationSecret()
      {
        return $this->_applicationSecret;
      }

     public function setUserId($userId)
     {
         $this->_userId = $userId;
     }

     public function getUserId()
     {
         return $this->_userId;
     }

     public function setAccessToken($accessToken)
     {
        $this->_accessToken = $accessToken;
     }

     public function setAccessTokenSecret($accessTokenSecret)
     {
        $this->_accessTokenSecret = $accessTokenSecret;
     }

     public function getAccessTokenSecret()
     {
         return $this->_accessTokenSecret;
     }

     public function setShortLink($shortLink)
     {
        $this->_shortLink = $shortLink;
     }

     public function getShortLink()
     {
         return $this->_shortLink;
     }

     public function setSource($source)
     {
         $source = str_replace("www.","",$source);
         $source = str_replace("http://","",$source);
         $source = str_replace("/","",$source);
         $source = strtolower($source);
         $sourceArray = explode(".",$source);
         $source = $sourceArray[0];

         $this->_source = $source;
     }

     public function getSource()
     {
         return $this->_source;
     }

     public function setCode($code)
     {
         $this->_code = $code;
     }

     public function getCode()
     {
         return $this->_code;
     }

     public function setBadFilters($badfilters){
        $this->_badFilters = $badfilters;
     }

     public function getBadFilters(){
         return $this->_badFilters;
     }

     public function setGoodFilters($goodfilters){
         $this->_goodFilters = $goodfilters;
     }

     public function getGoodFilters(){
         return $this->_goodFilters;
     }

     public function getRedirectUrl()
     {
         return "https://graph.facebook.com/oauth/authorize?client_id=".$this->getApplicationId()."&redirect_uri=".$this->getCallbackUrl()."&scope=offline_access,publish_stream";
     }


     public function getAccessToken(){
         return $this->_accessToken;
     }

    public function requestAccessToken()
     {
        $httpClient = new Zend_Http_Client();
        $httpClient->setUri("https://graph.facebook.com/oauth/access_token");
        $httpClient->setParameterGet(
            array(
                'client_id'     => $this->getApplicationId(),
                'client_secret' => $this->getApplicationSecret(),
                'code'          => $this->getCode(),
                'redirect_uri'  => $this->getCallbackUrl(),
            )
        );

        $response = $httpClient->request('GET');
        if ($response->isSuccessful()) {
           return str_replace("access_token=", "", $response->getBody());
        }
     }


     public function requestUser()
     {
        $httpClient = new Zend_Http_Client();
        $httpClient->setUri("https://graph.facebook.com/me");
        $httpClient->setParameterGet(
            array(
                'access_token'     => $this->getAccessToken()
            )
        );

        $response = $httpClient->request('GET');
       
        if ($response->isSuccessful()) {
           return json_decode($response->getBody());
        }
     }

     public function setPostContent($content)
     {
         $content = stripslashes($content);
         $content = str_replace("#facebook", "", $content);
         $content = strip_tags($content);
         $content =  $content.' '.$this->getShortLink();
         $this->_postContent = $content;
     }

     public function getPostContent()
     {
         return $this->_postContent;
     }

     public function postUpdate()
     {
        $httpClient = new Zend_Http_Client();
        $httpClient->setUri("https://graph.facebook.com/".$this->getUserId()."/feed");
        $httpClient->setParameterPost(
            array(
                'access_token' => $this->getAccessToken(),
                'message' => $this->getPostContent()
            )
        );

        $response = $httpClient->request('POST');
        if ($response->isSuccessful()) {
           return $response->getBody();
        }
     }

     public function requestWall()
     {
         try{

        $httpClient = new Zend_Http_Client();
        $httpClient->setUri("https://graph.facebook.com/me/feed");
        $httpClient->setParameterGet(
            array(
                'access_token'     => $this->getAccessToken()
            )
        );

        $response = $httpClient->request('GET');
        if ($response->isSuccessful()) {
           $wall = json_decode($response->getBody());
        }


        if($wall->data){
            foreach($wall->data as $item) {

                $item = (array) $item;

                       //checkvar
                       $filter1  = 1;
                       $filter2  = 1;

                       //not from same source
                            if($item["type"] == "status"){
                               if($this->getGoodFilters()){
                                   foreach(explode(",",$this->getGoodFilters()) as $filter){
                                        if(preg_match("/".$filter."/i",strtolower($item["message"]))){
                                            $filter1 = 1;
                                        }else{
                                            $filter1 = 0;
                                        }
                                   }
                                }
                                //deny when having one of the badfilters in it
                                if($this->getBadFilters() && $filter1==1){
                                    foreach(explode(",",$this->getBadFilters()) as $filter){
                                        if(preg_match("/".$filter."/i",strtolower($item["message"]))){
                                            $filter2 = 0;
                                        }
                                    }
                                }
                            }
                       if(preg_match("/".$this->getSource()."/i",strtolower($item["attribution"]))){
                           $filter2 = 0;
                       }

                        if($filter1== 1 && $filter2== 1){
                            $items[] = $item;
                        }


            }

            
         }

     }
          catch (Exception $e)
        {
            $items = "";
        }
        return $items;
    }

}