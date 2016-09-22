<?php

/**
 *
 * Page loader functions
 *
 */

function buddystream_facebookPages()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('facebookPages');
}