<?php
/**
 * Twitter class
 */

class BuddystreamTwitter {

    protected $_geoEnabled = false;
    protected $_callBackUrl;
    protected $_consumerKey;
    protected $_consumerSecret;
    protected $_postContent;
    protected $_shortLink;
    protected $_username;
    protected $_accessToken;
    protected $_accessTokenSecret;
    protected $_badFilters;
    protected $_goodFilters;
    protected $_source;
    protected $_geoData;
    
    
     public function setSource($source)
     {
         $this->_source = $source;
     }

     public function getSource()
     {
         return $this->_source;
     }
    
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

     public function setGeoData($geoData){
         $this->_geoData = $geoData;
     }

     public function getGeoData(){
         return $this->_geoData;
     }
     
      public function getConsumer()
      {
         $consumer = new Zend_Oauth_Consumer(
             array(
                 'version' => '1.0',
                 'callbackUrl' => $this->getCallbackUrl(),
                 'requestTokenUrl' => 'http://api.twitter.com/oauth/request_token',
                 'userAuthorizationUrl' => 'https://api.twitter.com/oauth/authorize',
                 'accessTokenUrl' => 'http://api.twitter.com/oauth/access_token',
                 'consumerKey' => $this->getConsumerKey(),
                 'consumerSecret' => $this->getConsumerSecret()
             )
         );
         
         return $consumer;
      }
      
      
      public function getClient(){
          $options = array('version' => '1.0',
		'localUrl' => $this->getCallbackUrl(),
		'callbackUrl' => $this->getCallbackUrl(),
		'requestTokenUrl' => 'http://api.twitter.com/oauth/request_token',
		'userAuthorisationUrl' => 'https://api.twitter.com/oauth/authorizee',
		'accessTokenUrl' => 'http://api.twitter.com/oauth/access_token',
		'consumerKey' => $this->getConsumerKey(),
		'consumerSecret' => $this->getConsumerSecret());
         
               $access = new Zend_Oauth_Token_Access();
               $access->setToken($this->getAccessToken());
               $access->setTokenSecret($this->getAccessTokenSecret());
               
               return $access->getHttpClient($options);
      }
      
     public function getRedirectUrl()
     {
         global $bp;

         try {
             $consumer = $this->getConsumer();
             $token    = $consumer->getRequestToken();
             
             update_user_meta($bp->loggedin_user->id,"bs_twitter_oauth_token",trim($token->oauth_token));
             update_user_meta($bp->loggedin_user->id,"bs_twitter_oauth_token_secret",trim($token->oauth_token_secret));

             return $consumer->getRedirectUrl(null, $token);
             
          }  catch (Exception $e){
              buddystreamLog('Twitter configuration error, try to re-enter the API keys, also make sure your twitter application has read/write permissions and is set to a web application!','error');
              return false;
          }
     }


     public function getTwitterToken(){

         global $bp;
         $oauthTokenRequest = new Zend_Oauth_Token_Request();
         $oauthTokenRequest->setToken(get_user_meta($bp->loggedin_user->id,"bs_twitter_oauth_token",1));
         $oauthTokenRequest->setTokenSecret(get_user_meta($bp->loggedin_user->id,"bs_twitter_oauth_token_secret",1));

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
         $client = $this->getClient();
         $client->setUri('http://api.twitter.com/1/statuses/update.json');         
         $client->setMethod(Zend_Http_Client::POST);
         $client->setParameterPost('status', $this->_postContent);
         $response = $client->request();
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
         $client = $this->getClient();
         $client->setUri('http://api.twitter.com/1/statuses/user_timeline.xml');  
         $client->setMethod(Zend_Http_Client::GET); 
         $request = $client->request();
         $response = $request->getBody();
         $response = simplexml_load_string($response);
         
         foreach($response as $tweet){

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