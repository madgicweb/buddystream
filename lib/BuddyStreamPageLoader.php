<?php


/**
 * Setup user navigation for the extentions
 *
 */

function buddystream_setup_nav()
{
    global $bp;

   
        bp_core_new_subnav_item(
            array(
                'name' => __('Social networks', 'buddystream_lang'),
                'slug' => 'buddystream-networks',
                'parent_url' => $bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/',
                'parent_slug' => BP_SETTINGS_SLUG,
                'screen_function' => 'buddystream_default_user_settings',
                'position' => 10,
                'user_has_access' => bp_is_my_profile ()
                )
        );
   
}
buddystream_setup_nav();

/**
 * User settings
 */

function buddystream_default_user_settings()
{
   BuddyStreamExtentions::userPageLoader('default','settings');
}

function buddystream_default_settings_screen_title()
{
    __('Social networks', 'buddystream_facebook');
}

function buddystream_default_settings_screen_content()
{
    global $bp;
    include WP_PLUGIN_DIR . "/buddystream/extentions/default/templates/UserSettings.php";
}

/**
 * Setup admin navigation for the extentions
 *
 */

add_action('admin_menu', 'buddystreamAdmin');
add_action('network_admin_menu', 'buddystreamAdmin');

function buddystreamAdmin() {

    if (!is_super_admin()) {
	return false;
    }

    /**
     * Load the BuddyStream menu into the admin
     */
    
    add_menu_page(
        __('Info', 'buddystream'), 
        __('BuddyStream', 'buddystream'), 
        'manage_options',
        'buddystream_admin', 
        'buddystream_welcome',
         WP_PLUGIN_URL . "/buddystream/images/buddystream_icon.png"
     );
    

    /**
     * Load the extentions into the BuddyStream admin menu.
     */

    

        foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extention){
            if (get_site_option("buddystream_".$extention['name']."_power")) {
                
                add_submenu_page(
                'buddystream_admin',
                __(ucfirst($extention['displayname']), 'buddystream_'.$extention['name']),
                __(ucfirst($extention['displayname']), 'buddystream_'.$extention['name']),
                'manage_options',
                'buddystream_'.$extention['name'],
                'buddystream_'.$extention['name']
                );
                
            }
        }
    
}

/**
 * Add albums page for network albums (grouped)
 */

function buddystream_profile_navigation(){
    
     global $bp;
      bp_core_new_nav_item( 
            array(
                'name' => __( 'Social albums', 'buddystream_lang' ),
                'slug' => 'social-album',
                'position' => 80,
                'screen_function' => 'buddystream_default_album'
            )
      );    
}

buddystream_profile_navigation();

function buddystream_default_album(){
    BuddyStreamExtentions::userPageLoader('default','album');
}

function buddystream_default_album_screen_title()
{
    __('Social albums', 'buddystream_lang');
}

function buddystream_default_album_screen_content()
{
    global $bp;
    include WP_PLUGIN_DIR . "/buddystream/extentions/default/templates/SocialAlbum.php";
}



/**
 * User settings
 */

function buddystream_facebook_user_settings()
{
   BuddyStreamExtentions::userPageLoader('facebook','settings');
}

function buddystream_facebook_settings_screen_title()
{
    __('Facebook', 'buddystream_facebook');
}

function buddystream_facebook_settings_screen_content()
{
    global $bp;
    include "templates/UserSettings.php";
}

/**
 * Add a dashboard widget
 */

function buddystream_dashboard_widget_function() {
	
	 ?>
            <script src="<?php echo plugins_url();?>/buddystream/extentions/default/highcharts/highcharts.js" type="text/javascript"></script>
            <script>
                            
                var chart;

                var $ = jQuery.noConflict();
                $(document).ready(function() {
                        chart = new Highcharts.Chart({
                                chart: {
                                        renderTo: 'statscontainer',
                                        plotBackgroundColor: '#f9f9f9',
                                        plotBorderWidth: 0,
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
                        
                        //gather data from active extentions
                        foreach (BuddyStreamExtentions::getExtentionsConfigs() as $extention) {

                            if(get_site_option('buddystream_'.$extention['name'].'_power') == "on"){
                                $networkTotal = 0;    
                                $networkTotal = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='".$extention['name']."';")));
                                $networkPerc  = 0;
                                $networkPerc  = $networkTotal/$totalItems * 100;
                                
                                echo  "['".ucfirst($extention["displayname"])." (".$networkTotal.")"."',".$networkPerc."],";
                            
                                $excludeString .= "AND type !='".$extention["name"]."' ";
                                
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
                <div id="statscontainer" style="width: 100%; height: 280px; background: #f9f9f9;"></div>
                 <?php

} 

function buddystream_add_dashboard_widgets() {
	wp_add_dashboard_widget('buddystream_dashboard_widget', __('Quick stats (activity items)','buddystream_lang'), 'buddystream_dashboard_widget_function');	
} 

add_action('wp_dashboard_setup', 'buddystream_add_dashboard_widgets' );


/**
 * Global BuddyStream pages
 *
 */

function buddystream_welcome()
{
     if (!isset($_GET["settings"])) {
        include WP_PLUGIN_DIR . "/buddystream/extentions/default/templates/Dashboard.php";
     } else if ($_GET["settings"] == "admin") {
        include WP_PLUGIN_DIR . "/buddystream/extentions/default/templates/Dashboard.php";
     } else if ($_GET["settings"] == "cronjob") {
         include WP_PLUGIN_DIR . "/buddystream/extentions/default/templates/Cronjob.php";
     } else if ($_GET["settings"] == "powercentral") {
         include WP_PLUGIN_DIR . "/buddystream/extentions/default/templates/Powercentral.php";
     } else if ($_GET["settings"] == "general") {
         include WP_PLUGIN_DIR . "/buddystream/extentions/default/templates/General.php";
     } else if ($_GET["settings"] == "log") {
         include WP_PLUGIN_DIR . "/buddystream/extentions/default/templates/Log.php";
     } else if ($_GET["settings"] == "version2") {
         include WP_PLUGIN_DIR . "/buddystream/extentions/default/templates/Version2.php";
     }    
}