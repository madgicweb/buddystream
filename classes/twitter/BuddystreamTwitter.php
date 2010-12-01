<?php

class BuddystreamTwitter
{
    protected $_callBackUrl;
    protected $_consumerKey;
    protected $_consumerSecret;
    protected $_postContent;
    protected $_shortLink;
    protected $_username;
    protected $_accessToken;
    protected $_accessTokenSecret;
    protected $_source;
    protected $_badFilters;
    protected $_goodFilters;

      public function setCallbackUrl($callBackUrl)
      {
        $this->_callBackUrl = $callBackUrl;
      }

      public function getCallbackUrl()
      {
        return $this->_callBackUrl;
      }

      public function setConsumerKey($consumerKey)
      {
        $this->_consumerKey = $consumerKey;
      }

      public function getConsumerKey()
      {
        return $this->_consumerKey;
      }

      public function setConsumerSecret($consumerSecret)
      {
        $this->_consumerSecret = $consumerSecret;
      }

      public function getConsumerSecret()
      {
        return $this->_consumerSecret;
      }

     public function setUsername($username)
     {
         $this->_username = $username;
     }


     public function getUsername()
     {
         return $this->_username;
     }

     public function setAccessToken($accessToken)
     {
        $this->_accessToken = $accessToken;
     }

     public function getAccessToken()
     {
         return $this->_accessToken;
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

      public function getConsumer()
      {
         $consumer = new Zend_Oauth_Consumer(
             array(
               'callbackUrl' => $this->getCallbackUrl(),
               'siteUrl' => 'http://twitter.com/oauth',
               'consumerKey' => $this->getConsumerKey(),
               'consumerSecret' => $this->getConsumerSecret()
             )
         );

         return $consumer;
      }
      
     public function getRedirectUrl()
     {
         global $bp;

         $consumer = $this->getConsumer();
         $token = $consumer->getRequestToken();
         update_usermeta($bp->loggedin_user->id,"bs_twitter_oauth_token",$token->oauth_token);
         update_usermeta($bp->loggedin_user->id,"bs_twitter_oauth_token_secret",$token->oauth_token_secret);

         return $consumer->getRedirectUrl(null, $token);
     }


     public function getTwitterToken(){

         global $bp;
         $oauthTokenRequest = new Zend_Oauth_Token_Request();
         $oauthTokenRequest->setToken(get_usermeta($bp->loggedin_user->id,"bs_twitter_oauth_token"));
         $oauthTokenRequest->setTokenSecret(get_usermeta($bp->loggedin_user->id,"bs_twitter_oauth_token_secret"));

         return $oauthTokenRequest;
     }

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

     public function postUpdate()
     {
         $access = new Zend_Oauth_Token_Access();
         $access->setToken($this->getAccessToken())->setTokenSecret($this->getAccessTokenSecret());

         $params = array(
             'accessToken' => $access,
             'consumerKey' => $this->getConsumerKey(),
             'consumerSecret' => $this->getConsumerSecret()
         );

        $twitter = new Zend_Service_Twitter($params);
        $repsonse = $twitter->statusUpdate($this->_postContent);
     }


     public function getRateLimit($twitterLimit)
     {
        foreach($twitterLimit as $key => $value)
        {
            if ($key=="remaining-hits") {
                return $value;
            }
        }
     }

     public function setSource($source)
     {
         $this->_source = $source;
     }

     public function getSource()
     {
         return $this->_source;
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

     public function getTweets()
     {
         try {
         $access = new Zend_Oauth_Token_Access();
         $access->setToken($this->getAccessToken())->setTokenSecret($this->getAccessTokenSecret());

         $params = array(
             'accessToken' => $access,
             'consumerKey' => $this->getConsumerKey(),
             'consumerSecret' => $this->getConsumerSecret()
         );

        $twitter = new Zend_Service_Twitter($params);

        //enought ratelimits left to get tweets
        if ($this->getRateLimit($twitter->account->rateLimitStatus())>0) {
            foreach($twitter->status->userTimeline() as $tweet)
                {
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
        }
         }
          catch (Exception $e)
        {
            $tweets = "";
        }

        return $tweets;
     }



     public function checkAuth()
     {
        try {
         $access = new Zend_Oauth_Token_Access();
         $access->setToken($this->getAccessToken())->setTokenSecret($this->getAccessTokenSecret());

         $params = array(
             'accessToken' => $access,
             'consumerKey' => $this->getConsumerKey(),
             'consumerSecret' => $this->getConsumerSecret()
         );

        $twitter = new Zend_Service_Twitter($params);

        if($twitter->accountVerifyCredentials()->error){
            return false;
        }else{
            return true;
        }  
     }  catch (Exception $e){

     }
     }
}