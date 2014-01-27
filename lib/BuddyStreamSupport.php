<?php

/**
 * Support for other plugins
 */

if (defined('ACHIEVEMENTS_IS_INSTALLED')) {
    add_action('dpa_achievement_unlocked','buddystream_achievement_unlocked', 10, 2);
    function buddystream_achievement_unlocked($achievement_id,$user_id)
    {
        $content = __('I just unlocked the', 'buddystream_lang')." ".dpa_get_achievement_name()." ".__('achievement!', 'buddystream');

        if(get_user_meta($user_id, 'tweetstream_achievements',1)){
            $content = "#twitter ".$content;
        }

        if(get_user_meta($user_id, 'facestream_achievements',1)){
            $content = "#facebook ".$content;
        }

        $shortLink = buddystream_getShortUrl(dpa_get_achievement_slug_permalink());
        buddystream_SocialIt($content,$shortLink,$user_id);
    }
}

/**
 * URL Shorting
 */

add_action('bp_init', 'buddystream_resolveShortUrl',4);
function buddystream_getShortUrl($url)
{
    global $bp;

    if ($url) {

        $out = "";
        $url   = str_replace("#", "",$url);
        $input = date('dmyhis');
        $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $base  = strlen($index);

        for ($t = floor(log($input, $base)); $t >= 0; $t--) {
            $bcp = pow($base, $t);
            $a = floor($input / $bcp) % $base;
            $out = $out . substr($index, $a, 1);
            $input = $input - ($a * $bcp);
        }
        $shortId = strrev($out);

        update_user_meta($bp->loggedin_user->id, 'buddystream_' . $shortId, $url);
        $url = get_site_url() . '/' . $shortId;

        return $url;
    } else {
        return false;
    }
}

add_action('bp_init', 'buddystream_resolveShortUrl',4);
function buddystream_resolveShortUrl($url)
{
    global $wpdb;
    if (is_404()) {
        $short_id = str_replace("/", "", $_SERVER ['REQUEST_URI']);
        if ($short_id) {

            $usermeta = $wpdb->get_row(
                "SELECT * FROM {$wpdb->usermeta}
                WHERE meta_key='buddystream_" .$short_id ."'"
            );

            if($usermeta != NULL){
                $url = $usermeta->meta_value;
                if ($url) {
                    header('location:' . $url);
                }
            }
        }
    }
}



/**
 * Pointers from WordPress 3.3 (BuddyStream Tour!)
 *
 */

if(get_bloginfo('version') > '3.2'){

    add_action( 'admin_enqueue_scripts','enqueue');

    function enqueue() {

        if(isset($_GET['action']) && $_GET['action'] == 'stoptour'){
            update_site_option('buddystream_tour',1);
        }

        if(isset($_GET['action']) && $_GET['action'] == 'starttour'){
            delete_site_option('buddystream_tour');
        }

        if(!get_site_option('buddystream_tour')){

            wp_enqueue_style( 'wp-pointer' );
            wp_enqueue_script( 'jquery-ui' );
            wp_enqueue_script( 'wp-pointer' );
            wp_enqueue_script( 'utils' );
            add_action( 'admin_head', 'print_scripts');
        }
    }

    function print_scripts() {
        global $pagenow, $wp_version;

        $position_at  = '';
        $index        = '';
        $action       = '';

        $page = '';
        if ( isset($_GET['page']) )
            $page = $_GET['page'];

        if ($page  ==  'buddystream_admin' && !isset($_GET['settings'])) {

            $id 			= 'buddystream_admin';
            $content 		= '<h3>Dashboard</h3><p>This is the <strong>dashboard</strong> here you find some quick details and news about BuddyStream.</p>';
            $button2 		= 'Next';
            $function 		= 'window.location="'.admin_url('admin.php?page=buddystream_admin&settings=powercentral').'";';
        }

        if ($page  ==  'buddystream_admin' && isset($_GET['settings']) && $_GET['settings'] == 'powercentral') {

            $id 			= 'buddystream_powercentral';
            $content 		= '<h3>Powercentral</h3><p>This is the <strong>powercentral</strong> here you can turn on/off networks you would like to use.</p>';
            $button2 		= 'Next';
            $function 		= 'window.location="'.admin_url('admin.php?page=buddystream_admin&settings=general').'";';
        }

        if ($page  ==  'buddystream_admin' && isset($_GET['settings']) && $_GET['settings'] == 'general') {

            $id 			= 'buddystream_general';
            $content 		= '<h3>General setting</h3><p>These are <strong>general settings</strong> here you can turn on/off BuddyStream options.</p>';
            $button2 		= 'Next';
            $function 		= 'window.location="'.admin_url('admin.php?page=buddystream_admin&settings=cronjob').'";';
        }

        if ($page  ==  'buddystream_admin' && isset($_GET['settings']) && $_GET['settings'] == 'cronjob') {

            $id 			= 'buddystream_cronjob';
            $content 		= '<h3>Cronjob</h3><p>Here you find the <strong>cronjob settings</strong>, add them to your server or enter your license key to let us handle the cronjob.</p>';
            $button2 		= 'Next';
            $function 		= 'window.location="'.admin_url('admin.php?page=buddystream_admin&settings=log').'";';
        }

        if ($page  ==  'buddystream_admin' && isset($_GET['settings']) && $_GET['settings'] == 'log') {

            $id 			= 'buddystream_log';
            $content 		= '<h3>Logs</h3><p>Here you can see the <strong>logs</strong> of BuddyStream, if something went wrong you can see it appear over here.</p>';
            $button2 		= '';
            $function 		= 'window.location="'.admin_url('admin.php?page=buddystream_admin&settings=cronjob').'";';
        }



        print_buttons( $id, $content, __( "Close", "buddystream_lang" ), $position_at, $button2, $function );
    }



    function print_buttons( $id, $content, $button1, $position_at, $button2 = false, $button2_function = '' ) {
        ?>
    <script type="text/javascript">
        //<![CDATA[
        jQuery(document).ready( function() {
            jQuery('#<?php echo $id; ?>').pointer({
                content: '<?php echo addslashes( $content ); ?>',
                buttons: function( event, t ) {
                    button = jQuery('<a id="pointer-close" href="admin.php?page=buddystream_admin&settings=<?php echo str_replace("buddystream_","",$id); ?>&action=stoptour" class="button-<?php if ($button2) { echo "secondary"; } else { echo "primary"; } ?>">' + '<?php echo $button1; ?>' + '</a>');
                    button.bind( 'click.pointer', function() {
                        t.element.pointer('close');
                    });
                    return button;
                },
                position: {
                    offset: '90 100'

                },
                arrow: {
                    edge: 'left',
                    align: 'top',
                    offset: 10
                },
                close: function() { }
            }).pointer('open');
            <?php if ( $button2 ) { ?>
                jQuery('#pointer-close').after('<a id="pointer-primary" class="button-primary">' + '<?php echo $button2; ?>' + '</a>');
                jQuery('#pointer-primary').click( function() {
                    <?php echo $button2_function; ?>
                });

                <?php } ?>
        });
        //]]>
    </script>
    <?php
    }
}