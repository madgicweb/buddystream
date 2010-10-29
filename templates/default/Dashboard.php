<link rel="stylesheet" href="<?= WP_PLUGIN_URL . '/buddystream/css/buddystream.css';?>" type="text/css" />
<br>
<?php
include "AdminMenu.php";
global $bp,$wpdb;

//totals
$totalMembers  = count($wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users")));
$totalTwitter  = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='tweetstream_token';")));
$totalFacebook = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='facestream_session_key';")));
$totalFlickr   = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='bs_flickr_username';")));
$totalLastfm   = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='bs_lastfm_username';")));
$totalYoutube  = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='bs_youtube_username';")));

$totalItems           = count($wpdb->get_results($wpdb->prepare("SELECT id FROM " . $bp->activity->table_name)));
$totalTwitterItems    = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='tweet';")));
$totalFacebookItems   = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='facebook';")));
$totalFlickrItems     = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='flickr';")));
$totalLastfmItems     = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='lastfm';")));
$totalYoutubeItems    = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='youtube';")));

//percentages
$percentageTwitter = round(($totalTwitter / $totalMembers) * 100);
$percentageFacebook = round(($totalFacebook / $totalMembers) * 100);
$percentageFlickr = round(($totalFlickr / $totalMembers) * 100);
$percentageLastfm = round(($totalLastfm / $totalMembers) * 100);
$percentageYoutube = round(($totalYoutube / $totalMembers) * 100);
$percentageTotal = 100 -($percentageTwitter+$percentageFacebook+$percentageFlickr+$percentageLastfm+$percentageYoutube);

$percentageTwitterItems = round(($totalTwitterItems / $totalItems) * 100);
$percentageFacebookItems = round(($totalFacebookItems / $totalItems) * 100);
$percentageFlickrItems = round(($totalFlickrItems / $totalItems) * 100);
$percentageLastfmItems = round(($totalLastfmItems / $totalItems) * 100);
$percentageYoutubeItems = round(($totalYoutubeItems / $totalItems) * 100);
$percentageTotalItems = 100 -($percentageTwitterItems+$percentageFacebookItems+$percentageFlickrItems+$percentageLastfmItems+$percentageYoutubeItems);

?>



<div class="wrap"><br />

<h2 style="float: left; line-height: 5px; padding-left: 5px;">
    <?php echo __('BuddyStream Dashboard'); ?>
</h2>

<br/><br/><br/>

<div id="dashboard-widgets-wrap">

<div class="metabox-holder" id="dashboard-widgets">
	<div style="width: 49%;" class="postbox-container">
        <div class="meta-box-sortables ui-sortable" id="normal-sortables">

            <div class="postbox " id="dashboard_right_now">
                <div><h3 class="hndle"><span><?php echo __('Quick statistics'); ?></span></h3>
                    <div class="inside" style="padding:10px;">

                <img src="
                     <?php
                     echo 'http://chart.apis.google.com/chart?chs=350x200&cht=p&chd=t:'.$percentageTotal.','.$percentageTwitter.','.$percentageFacebook.','.$percentageFlickr.','.$percentageLastfm.','.$percentageYoutube.'&chp=2&chl=Members ('.$percentageTotal.'%)|Twitter ('.$percentageTwitter.'%)|Facebook ('.$percentageFacebook.'%)|Flickr ('.$percentageFlickr.'%)|Last.fm ('.$percentageLastfm.'%)|Youtube ('.$percentageYoutube.'%)&chtt=Intergration+statistics';?>" width="350" height="200" alt="<? echo  __('Intergration statistics','buddystream_lang');?> " />
                    
                    <img src="
                     <?php
                     echo 'http://chart.apis.google.com/chart?chs=350x200&cht=p&chd=t:'.$percentageTotalItems.','.$percentageTwitterItems.','.$percentageFacebookItems.','.$percentageFlickrItems.','.$percentageLastfmItems.','.$percentageYoutubeItems.'&chp=2&chl=Items ('.$percentageTotalItems.'%)|Twitter ('.$percentageTwitterItems.'%)|Facebook ('.$percentageFacebookItems.'%)|Flickr ('.$percentageFlickrItems.'%)|Last.fm ('.$percentageLastfmItems.'%)|Youtube ('.$percentageYoutubeItems.'%)&chtt=Import+statistics';?>" width="350" height="200" alt="<? echo  __('Import statistics','buddystream_lang');?> "/>
                    </div>
                </div>
            </div>

             <div class="postbox " id="dashboard_right_now">
                <div><h3 class="hndle"><span><? echo __('Support','buddystream_lang');?></span></h3>
                    <div class="inside" style="padding:10px;">
                          <? echo __('Did you found a bug? Or do you have a feature request? <br> Please report it in our support system.','buddystream_lang'); ?><br><br>
                            <a href="http://http://buddystream.net/support/"><? echo __('Click here to report a bug/feature request!','buddystream_lang');?></a>
                    </div>
                </div>
            </div>


           
            
        </div>
    </div>

    <div style="width: 49%;" class="postbox-container">
        <div class="meta-box-sortables ui-sortable" id="normal-sortables">

            <div class="postbox " id="dashboard_right_now">
                <div><h3 class="hndle"><span><?= __('Latest news','buddystream_lang');?></span></h3>
                    <div class="inside" style="padding:10px;">
                        <?php
                            $feedItems = fetch_feed('http://buddystream.net/feed/');
                            foreach ($feedItems->get_items() as $feedItem) {
                                echo '<a href="'.$feedItem->get_permalink().'" title="'.$feedItem->get_title().'" target="_blanc">'.$feedItem->get_date('j-m-Y').' - '.$feedItem->get_title().'</a><br>';
                            }
                        ?>
                    </div>
                </div>
            </div>

            <div class="postbox " id="dashboard_right_now">
                <div><h3 class="hndle"><span><? echo __('Donate','buddystream_lang');?></span></h3>
                    <div class="inside" style="padding:10px;">
                        <? echo __('If you like this plugin and want to see it supported, continued, and extended please take a minute and consider donating a few dollars for our expenses (even juice or coffee!).  It only takes a couple minutes of your time!  Seriously, what is this plugin worth to you?  Think about it…','buddystream_lang'); ?><br><br>
                        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Z7RK6XPG9K8UQ" target="_blanc"><? echo __('Click here to donate!','buddystream_lang');?></a>
                    </div>
                </div>
            </div>


             <div class="postbox " id="dashboard_right_now">
                <div><h3 class="hndle"><span><? echo __('Advertisement','buddystream_lang');?></span></h3>
                    <div class="inside" style="padding:10px;">
                        <script type="text/javascript"><!--
                        google_ad_client = "pub-9463596301344154";
                        /* 234x60, gemaakt 28-10-10 */
                        google_ad_slot = "5541908398";
                        google_ad_width = 234;
                        google_ad_height = 60;
                        //-->
                        </script>
                        <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script>
                    </div>
                </div>
            </div>

        </div>
    </div>

 </div>

<div class="clear"></div>
</div>


</div>