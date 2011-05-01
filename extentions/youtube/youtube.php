<?php
/**
 * Youtube class
 */

class BuddystreamYoutube
{
    protected $_username;

     public function setUsername($username)
     {
         $this->_username = $username;
     }


     public function getUsername()
     {
         return $this->_username;
     }

     public function getVideos()
     {
      try {
         $youtube = new Zend_Gdata_YouTube();
         $videos = $youtube->getUserUploads($this->getUsername());
      }  catch (Exception $e)
      {
            $videos = "";
      }
         return $videos;
     }

     public function getFavorites()
     {
         try {
         $youtube = new Zend_Gdata_YouTube();
         $videos = $youtube->getUserFavorites($this->getUsername());
         }  catch (Exception $e)
      {
            $videos = "";
      }
         return $videos;
     }
}