<?php

/**
 * filter to show last fm items on main activity, or user activity
 */

add_filter( 'bp_ajax_querystring', 'bs_filter_query', 999, 2 );
function bs_filter_query( $qs, $object ) {

    global $bp;
    if (preg_match("/lastfm/i",$qs)) {
        return $qs."&show_hidden=true&scope=lastfm";
    }

    return $qs;
}

/**
 * 
 * Page loader functions 
 *
 */

function buddystream_lastfm()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('lastfm');
}