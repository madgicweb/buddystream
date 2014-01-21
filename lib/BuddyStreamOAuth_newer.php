<?php

/*
 * Including the OAuth class
 */

include_once('BuddyStreamOauthClass.php');

/**
 * BuddyStreamOAuth
 * This class handles all OAuth requests for multiple networks
 */

class BuddyStreamOAuth{

    protected $consumerKey;
    protected $consumerSecret;
    protected $requestTokenUrl;
    protected $authorizeUrl;
    protected $accessTokenUrl;
    protected $callbackUrl = NULL;
    protected $requestToken;
    protected $requestTokenSecret;
    protected $accessToken;
    protected $accessTokenSecret;
    protected $requestType = 'GET';
    protected $requestUrl;
    protected $postData = NULL;
    protected $paramaters = NULL;

    /*
     * Setter and getter for consumerKey
     * 
     */

    public function setConsumerKey($consumerKey){
        $this->consumerKey = $consumerKey;
    }

    public function getConsumerKey(){

        if(!$this->consumerKey){
            //throw new Exception("ConsumerKey is not set."); 
        }

        return $this->consumerKey;
    }

    /*
     * Setter and getter for consumerSecret
     * 
     */

    public function setConsumerSecret($consumerSecret){
        $this->consumerSecret = $consumerSecret;
    }

    public function getConsumerSecret(){

        if(!$this->consumerSecret){
            //throw new Exception("ConsumerSecret is not set."); 
        }

        return $this->consumerSecret;
    }

    /*
     * Setter and getters for api request urls
     * 
     */

    public function setRequestTokenUrl($requestTokenUrl){
        $this->requestTokenUrl = $requestTokenUrl;
    }

    public function getRequestTokenUrl(){

        if(!$this->requestTokenUrl){
//            throw new Exception("requestTokenUrl is not set.");
        }

        return $this->requestTokenUrl;
    }

    public function setAuthorizeUrl($authorizeUrl){
        $this->authorizeUrl = $authorizeUrl;
    }

    public function getAuthorizeUrl(){

        if(!$this->authorizeUrl){
//            throw new Exception("authorizeUrl is not set.");
        }

        return $this->authorizeUrl;
    }

    public function setAccessTokenUrl($accessTokenUrl){
        $this->accessTokenUrl = $accessTokenUrl;
    }

    public function getAccessTokenUrl(){

        if(!$this->accessTokenUrl){
//            throw new Exception("accessTokenUrl is not set.");
        }

        return $this->accessTokenUrl;
    }

    /*
     * Getter for redirect url
     * Return the redirect url where users have to authorize
     * 
     */

    public function getRedirectUrl(){
        return $this->getAuthorizeUrl()."?oauth_token=".urldecode($this->getRequestToken());
    }

    /*
     * Setter and getter for the request type (GET/POST)
     * 
     */

    public function setRequestType($requestType){
        $this->requestType = $requestType;
    }

    public function getRequestType(){
        return $this->requestType;
    }

    /*
     * Setter and getter for the parameters 
     * 
     */

    public function setParameters($parameters){
        $this->paramaters = $parameters;
    }

    public function getParameters(){
        return $this->paramaters;
    }

    /*
     * getter and setter for postdata
     * 
     */

    public function setPostData($postData){
        $this->postData = $postData;
    }

    public function getPostData()
    {
        if(!$this->postData){
//            throw new Exception("postData is not set.");
        }

        return $this->postData;
    }

    /*
     * Setter and getter for callbackUrl
     * 
     */

    public function setCallbackUrl($callbackUrl){
        $this->callbackUrl = $callbackUrl;
    }

    public function getCallbackUrl(){

        if(!$this->callbackUrl){
            //throw new Exception("callbackUrl is not set."); 
        }

        return $this->callbackUrl;
    }


    /*
     * Setter and getter for requestToken
     *  
     */

    public function setRequestToken($requestToken){
        $this->requestToken = $requestToken;
    }

    public function getRequestToken(){

        if(!$this->requestToken){
            // throw new Exception("requestToken is not set.");
        }

        return $this->requestToken;
    }

    /*
     * Setter and getter for requestTokenSecret
     *  
     */

    public function setRequestTokenSecret($requestTokenSecret){
        $this->requestTokenSecret = $requestTokenSecret;
    }

    public function getRequestTokenSecret(){

        if(!$this->requestTokenSecret){
             //throw new Exception("requestTokenSecret is not set.");
        }

        return $this->requestTokenSecret;
    }

    /*
    * Getter and setter for accessToken
    *
    */

    public function setAccessToken($accessToken){
        $this->accessToken = $accessToken;
    }

    public function getAccessToken(){

        if(!$this->accessToken){
            //throw new Exception("accessToken is not set.");
        }

        return $this->accessToken;
    }

    /*
     * Getter and setter for accessTokenSecret
     *  
     */

    public function setAccessTokenSecret($accessTokenSecret){
        $this->accessTokenSecret = $accessTokenSecret;
    }

    public function getAccessTokenSecret(){

        if(!$this->accessTokenSecret){
             //throw new Exception("accessTokenSecret is not set.");
        }

        return $this->accessTokenSecret;
    }

    /*
     * Getter for consumer
     * returns a validated OAuth consumer object.
     * If no paramters provided it will fallback on defaults.
     * 
     */

    public function getConsumer($consumerKey = null, $consumerSecret = null, $callbackUrl = null){

        if(is_null($consumerKey)){
            $consumerKey = $this->getConsumerKey();
        }

        if(is_null($consumerSecret)){
            $consumerSecret = $this->getConsumerSecret();
        }

        if(is_null($callbackUrl)){
            $callbackUrl = $this->getCallbackUrl();
        }

        $consumer = new BuddyStreamOAuthConsumer($consumerKey,$consumerSecret,$callbackUrl);

        return $consumer;
    }

    /*
     * Geter for the requestToken
     * Returns a temporary request token from provider to do oauth calls.
     * 
     */

    public function requestToken(){

        if($this->getParameters()){
            $parameters = $this->getParameters();
        }else{
            $parameters = null;
        }

        $consumer = $this->getConsumer();
        $req = BuddyStreamOAuthRequest::from_consumer_and_token($consumer, NULL, "GET", $this->getRequestTokenUrl(), $parameters);
        $req->sign_request(new BuddyStreamOAuthSignatureMethod_HMAC_SHA1(), $consumer, NULL);
        $req_url = $req->to_url();
        $output = $this->executeRequest($req_url);

        //create tokenarray from output
        $outputArray = explode("&",$output);
        $tokenArray = explode("=",$outputArray[0]);
        $tokenSecretArray = explode("=",$outputArray[1]);

        $token = array('oauth_token' => $tokenArray[1], 'oauth_token_secret' => $tokenSecretArray[1]);

        if(!$tokenArray[1]){
            echo "<hr><pre>".$output."</pre><hr>";
            return false;
        }

        return $token;
    }

    /*
     * Getter for accesToken
     * Trade the requestToken for a token that can be used until the user revokes it. (i say do it!)
     * 
     */
    public function accessToken($return = false){

        if($this->getParameters()){
            $parameters = $this->getParameters();
        }else{
            $parameters = null;
        }

        $consumer = $this->getConsumer();
        $token = $this->getConsumer($this->getRequestToken(), $this->getRequestTokenSecret(), $this->getCallbackUrl());

        $req = BuddyStreamOAuthRequest::from_consumer_and_token($consumer, $token, "GET", $this->getAccessTokenUrl(), $parameters);
        $req->sign_request(new BuddyStreamOAuthSignatureMethod_HMAC_SHA1(), $consumer, $token);
        $req_url = $req->to_url();

        $output = $this->executeRequest($req_url);

        if($return){
            return $output;
        }

        //create tokenarray from output
        $token = array('oauth_token' => $token->key, 'oauth_token_secret' => $token->secret);

        return $token;
    }

    /*
     * Make a oAuth validated request to a provider.
     * 
     */

    function oAuthRequest($url) {

        if($this->getParameters()){
            $parameters = $this->getParameters();
        }else{
            $parameters = null;
        }

        $consumer    = $this->getConsumer();
        $accessToken = $this->getConsumer($this->getAccessToken(), $this->getAccessTokenSecret(), $this->getCallbackUrl());

        $req = BuddyStreamOAuthRequest::from_consumer_and_token($consumer, $accessToken, $this->getRequestType(), $url, $parameters);
        $req->sign_request(new BuddyStreamOAuthSignatureMethod_HMAC_SHA1(), $consumer, $accessToken);

        if($this->getRequestType() == 'GET'){
            return $this->executeRequest($req->to_url());
        }else{
            $this->setPostData($req->to_postdata());
            return $this->executeRequest($req->get_normalized_http_url());
        }


    }

    /*
     * Curl to do the actual request.
     * Uses the provider url.
     * 
     */

    public function executeRequest($url){

        $ci = curl_init();
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ci, CURLOPT_HEADER, FALSE);

        if($this->getRequestType() == 'POST'){
            curl_setopt($ci, CURLOPT_POST, TRUE);

            if ($this->getPostData()) {
                curl_setopt($ci, CURLOPT_POSTFIELDS, $this->getPostData());
            }

        }else{
            curl_setopt($ci, CURLOPT_HTTPGET, TRUE);
        }

        curl_setopt($ci, CURLOPT_URL, $url);

        $response = curl_exec($ci);
        curl_close ($ci);

        return $response;
    }


    public function oAuthRequestPostXml($url){

        if($this->getParameters()){
            $parameters = $this->getParameters();
        }else{
            $parameters = null;
        }

        $consumer    = $this->getConsumer();
        $accessToken = $this->getConsumer($this->getAccessToken(), $this->getAccessTokenSecret(), $this->getCallbackUrl());

        $req = BuddyStreamOAuthRequest::from_consumer_and_token($consumer, $accessToken, 'POST', $url, $this->getParameters());
        $req->sign_request(new BuddyStreamOAuthSignatureMethod_HMAC_SHA1(), $consumer, $accessToken);

        $ci = curl_init();
        curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_VERBOSE, FALSE);

        $header = array($req->to_header('http://api.linkedin.com'));
        $header[] = 'Content-Type: text/xml; charset=UTF-8';

        curl_setopt($ci, CURLOPT_POSTFIELDS, $this->getPostData());
        curl_setopt($ci, CURLOPT_HTTPHEADER, $header);
        $response = curl_exec($ci);
        curl_close($ci);

        return $response;
    }

}