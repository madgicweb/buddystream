<?php

/**
 *
 * Page loader functions
 *
 */

function buddystream_facebookAlbums()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('facebookAlbums');
}