<link rel="stylesheet" href="<?php echo BP_BUDDYSTREAM_URL . '/extensions/default/admin.css';?>" type="text/css" />
<script src="<?php echo BP_BUDDYSTREAM_URL;?>//extensions/default/highcharts/highcharts.js" type="text/javascript"></script>

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

    <div style="width: 49%;" class="postbox-container">
        <div class="meta-box-sortables ui-sortable" id="normal-sortables">

            <div class="postbox">
                <div><h3 style="cursor:default;"><span><?php _e('Quick stats (activity items)','buddystream_lang');?></span></h3>
                    <div class="inside" style="padding:10px;">
                        <script>
                            
                        var chart;
                        
                        var $ = jQuery.noConflict();
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'statscontainer',
						plotBackgroundColor: '#f9f9f9',
						plotBorderWidth: 0,
                                                plotBorder: false,
						plotShadow: false
					},
					title: {
						text: ''
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
						}
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true,
								color: '#000000',
								connectorColor: '#000000',
								formatter: function() {
									return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
								}
							}
						}
					},
				    series: [{
						type: 'pie',
						name: 'Activityitems',
						data: [
							
                                                        
                                                        <?php
                        global $bp, $wpdb;
                        
                        //init
                        $excludeString = '';
                        
                        //count all activity items
                        $totalItems = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . ";")));
                        
                        //gather data from active extensions
                            $buddyStreamExtensions = new BuddyStreamExtensions();
                        foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {

                            if(get_site_option('buddystream_'.$extension['name'].'_power') == "on"){
                                $networkTotal = 0;    
                                $networkTotal = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='".$extension['name']."';")));
                                $networkPerc  = 0;
                                $networkPerc  = $networkTotal/$totalItems * 100;
                                
                                echo  "['".ucfirst($extension["displayname"])." (".$networkTotal.")"."',".$networkPerc."],";
                            
                                $excludeString .= "AND type !='".$extension["name"]."' ";
                                
                            }
                        }
                        
                        $userTotal = count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type !='' ".$excludeString.";"));
                        $userPerc  = $totalItems/$userTotal * 100;
                        
                        echo  "['User activities (".$userTotal.")"."',".$networkPerc."],";
                            
                        
                        ?>
                                                       
						]
					}]
				});
			});
				
		</script>


		
		<div id="statscontainer" style="width: 100%; height:280px;"></div>
                        
                    </div>
                </div>
            </div>
            
            
            
        </div>
    </div>
 </div>
<div class="clear"></div>
</div>