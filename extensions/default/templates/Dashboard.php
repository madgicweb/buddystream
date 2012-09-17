<link rel="stylesheet" href="<?php echo BP_BUDDYSTREAM_URL . '/extensions/default/admin.css';?>" type="text/css" />
<?php include "AdminMenu.php"; ?>

<div class="buddystream_info_box_dashboard">
    <img src="<?php echo BP_BUDDYSTREAM_URL . '/images/buddystream.png';?>" width="100" align="left" style="padding-right:15px; padding-bottom:15px;"/><br/>
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


 </div>
<div class="clear"></div>
</div>