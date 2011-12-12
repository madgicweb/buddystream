<?php
/*
 * Flickr Class
 */

class BuddystreamFlickr
{
    protected $_consumerKey;
    protected $_consumerSecret;
    protected $_username;

      public function setConsumerKey($consumerKey)
      {
        $this->_consumerKey = $consumerKey;
      }

      public function getConsumerKey()
      {
        return $this->_consumerKey;
      }

     public function setUsername($username)
     {
         $this->_username = $username;
     }


     public function getUsername()
     {
         return $this->_username;
     }

     public function getPhotos()
     {
        try {
            $flickr = new Zend_Service_Flickr($this->getConsumerKey());
            $photos = $flickr->userSearch($this->getUsername());
        }
        catch (Exception $e)
        {
            $photos = "";
        }

        return $photos;
    }
}