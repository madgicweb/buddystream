<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />

<?php include "AdminMenu.php"; ?>

<div class="buddystream_info_box_dashboard">
    <img src="<?php echo plugins_url() . '/buddystream/images/buddystream.png';?>" width="100" align="left" style="padding-right:15px; padding-bottom:15px;"/><br/>
    <?php _e('dashboard description','buddystream_lang'); ?>
</div>

<div id="dashboard-widgets-wrap">

<div class="metabox-holder" id="dashboard-widgets">
	<div style="width: 49%;" class="postbox-container">
        <div class="meta-box-sortables ui-sortable" id="normal-sortables">

          
            <div class="postbox">
                <div><h3 style="cursor:default;"><span><?php _e('Latest news','buddystream_lang');?></span></h3>
                    <div class="inside" style="padding:10px;">
                        <?php
                            $feedItems = fetch_feed('http://buddystream.net/feed/');
                            if( is_wp_error( $feedItems ) ) {
                                echo $feedItems->get_error_message();
                            } else {
                                foreach ($feedItems->get_items() as $feedItem) {
                                    echo '<a href="'.$feedItem->get_permalink().'" title="'.$feedItem->get_title().'" target="_blanc">'.$feedItem->get_date('j-m-Y').' - '.$feedItem->get_title().'</a><br>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            
             <div class="postbox">
                <div><h3 style="cursor:default;"><?php _e('Support','buddystream_lang');?></h3>
                    <div class="inside" style="padding:10px;">
                        <?php _e('support description','buddystream_lang'); ?>          
                    </div>
                </div>
            </div>
            
             <div class="postbox">
                <div><h3 style="cursor:default;"><?php _e('Did you know?','buddystream_lang');?></h3>
                    <div class="inside" style="padding:10px;">
                        <?php //_e('goliath','buddystream_lang'); ?>         
                        The Goliath Birdwing (Ornithoptera goliath) is the second-largest butterfly in the world. This brightly-colored butterfly is poisonous and has a wingspan up to 11 inches (28 cm) wide. It has black, yellow and green wings and a yellow and black body. This butterfly in found in tropical forests in Indonesia. Family Papilionidae.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="width: 49%;" class="postbox-container">
        <div class="meta-box-sortables ui-sortable" id="normal-sortables">

            <div class="postbox">
                <div><h3 style="cursor:default;"><span><?php _e('Donate','buddystream_lang');?></span></h3>
                    <div class="inside" style="padding:10px;">
                   <?php _e('donate description','buddystream_lang'); ?>        
                    </div>
                </div>
            </div>

             <div class="postbox">
                <div><h3 style="cursor:default;"><span><?php echo __('Advertisement','buddystream_lang');?></span></h3>
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