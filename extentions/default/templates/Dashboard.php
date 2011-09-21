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
                    <div class="inside">
                        <ul class="buddystream_news">
                        <?php
                        
                            $class = "even";
                            $feedItems = fetch_feed('http://buddystream.net/feed/');
                            if( is_wp_error( $feedItems ) ) {
                                echo "No newsitems found.";
                            } else {
                                foreach ($feedItems->get_items() as $feedItem) {
                                    echo '<li class="'.$class.'"><a href="'.$feedItem->get_permalink().'" title="'.$feedItem->get_title().'" target="_blanc">'.$feedItem->get_date('j-m-Y').' - '.$feedItem->get_title().'</a></li>';
                                    if($class == "even") { $class= "odd"; }else{ $class = "even"; }
                                }
                            }
                        ?>
                        </ul>
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
                <div><h3 style="cursor:default;"><?php _e('Did you know?','buddystream_lang');?></h3>
                    <div class="inside" style="padding:10px;">
                        The Purple Emperor is a magnificent and elusive insect that is actively sought out by the many subjects of "His Majesty", as the male butterfly is affectionately known.<br/>
                        This butterfly spends most of its time in the woodland canopy where it feeds on aphid honeydew, with the occasional close encounter when it comes down to feed on sap runs or, in the case of the male, animal droppings, carrion or moist ground that provide much-needed salts and minerals. <br/>
                        Those that make pilgrimages to see this spectacular creature will often try and lure the males down from the canopy using all manner of temptations - including banana skins and shrimp paste.<br/>
                        <br/>
                        The male butterfly is one of the most beautiful of all of the butterflies found in the British Isles.<br/>
                        From certain angles it appears to have black wings intersected with white bands.<br/>
                        However, when the wings are at a certain angle to the sun, the most beautiful purple sheen is displayed, a result of light being refracted from the structures of the wing scales. The female, on the other hand, is a deep brown and does not possess the purple sheen found in the male.<br/>
                        This is one of the most-widely studied and written about butterflies in the British Isles.
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
<div class="clear"></div>
</div>