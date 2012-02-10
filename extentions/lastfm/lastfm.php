<?php
/**
 * Last.fm Class
 */

class BuddystreamLastfm
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

     public function getRecentTracks()
     {
        try {
            $lastfm = new Zend_Service_Audioscrobbler();
            $lastfm->setUser($this->getUsername());
            $songs = $lastfm->userGetRecentTracks();

        } catch (Exception $e){
            $songs = "";
        }
        return $songs;
     }
}