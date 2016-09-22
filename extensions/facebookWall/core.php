<?php

/**
 *
 * Page loader functions
 *
 */

function buddystream_facebookWall()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('facebookWall');
}