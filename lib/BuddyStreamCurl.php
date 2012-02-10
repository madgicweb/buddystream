<?php

/*
 * Class to handle curl requests
 */

class BuddyStreamCurl {
    
    /**
     * Return decoded json content form url
     * @param string $url
     * @return string 
     */
    public function getJsonDecodedContent($url){
        return json_decode(BuddyStreamCurl::getContentFromUrl($url));
    }
    
    
    /**
     * Get the content form a url
     * @param string $url 
     */
    
    public function getContentFromUrl($url){
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ci, CURLOPT_HEADER, FALSE);
        curl_setopt($ci, CURLOPT_HTTPGET, TRUE);
        curl_setopt($ci, CURLOPT_URL, $url);
        
        $response = curl_exec($ci);
        curl_close ($ci);
        
        return $response;
    }
}