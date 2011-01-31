<?php

define('BP_BUDDYSTREAM_VERSION', '1.0.3.2');
define('BP_BUDDYSTREAM_IS_INSTALLED', 1);

##############################################
##                                          ##
##             UPGRADE STUFF                ##
##                                          ##
##############################################
if(!get_option("bp_buddystream_upgrade")){
    include "upgrade.php";
    update_option("bp_buddystream_upgrade","1.0.2.3");
}

##############################################
##                                          ##
##      NAVIGATION (user level)             ##
##                                          ##
##############################################

function buddystream_setup_nav()
{
    global $bp;

    if(!buddystreamCheckRequirements()){

        if (get_site_option("buddystream_twitter_power")) {
            if (get_site_option("tweetstream_consumer_key")) {
                bp_core_new_subnav_item(
                    array(
                        'name' => __('Twitter', 'buddystream_lang'),
                        'slug' => 'buddystream-twitter',
                        'parent_url' => $bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/',
                        'parent_slug' => BP_SETTINGS_SLUG,
                        'screen_function' => 'buddystream_twitter_user_settings',
                        'position' => 10,
                        'user_has_access' => bp_is_my_profile ()
                        )
                );
            }
        }

        if (get_site_option("buddystream_facebook_power")) {
            if (get_site_option("facestream_application_id")) {
                bp_core_new_subnav_item(
                    array(
                        'name' => __('Facebook', 'buddystream_lang'),
                        'slug' => 'buddystream-facebook',
                        'parent_url' => $bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/',
                        'parent_slug' => BP_SETTINGS_SLUG,
                        'screen_function' => 'buddystream_facebook_user_settings',
                        'position' => 20,
                        'user_has_access' => bp_is_my_profile ()
                        )
                );
            }
        }
        if (get_site_option("buddystream_flickr_power")) {
            if (get_site_option("bs_flickr_api_key")) {
                bp_core_new_subnav_item(
                    array(
                        'name' => __('Flickr', 'buddystream_lang'),
                        'slug' => 'buddystream-flickr',
                        'parent_url' => $bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/',
                        'parent_slug' => BP_SETTINGS_SLUG,
                        'screen_function' => 'buddystream_flickr_user_settings',
                        'position' => 30,
                        'user_has_access' => bp_is_my_profile ()
                        )
                );
            }
        }

        if (get_site_option("buddystream_lastfm_power")) {
            bp_core_new_subnav_item(
                array(
                    'name' => __('Last.fm', 'buddystream_lang'),
                    'slug' => 'buddystream-lastfm',
                    'parent_url' => $bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/',
                    'parent_slug' => BP_SETTINGS_SLUG,
                    'screen_function' => 'buddystream_lastfm_user_settings',
                    'position' => 40,
                    'user_has_access' => bp_is_my_profile ()
                    )
            );
        }

        if (get_site_option("buddystream_youtube_power")) {
            bp_core_new_subnav_item(
                array(
                    'name' => __('Youtube', 'buddystream_lang'),
                    'slug' => 'buddystream-youtube',
                    'parent_url' => $bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/',
                    'parent_slug' => BP_SETTINGS_SLUG,
                    'screen_function' => 'buddystream_youtube_user_settings',
                    'position' => 50,
                    'user_has_access' => bp_is_my_profile ()
                    )
            );
         }
    }
}

buddystream_setup_nav();

##############################################
##                                          ##
##               admin pages                ##
##                                          ##
##############################################

add_action('admin_menu', 'buddystream_admin');
function buddystream_admin()
{
    if ( !is_super_admin() )
		return false;

        bp_core_add_admin_menu_page(
              array(
                  'menu_title' => __('BuddyStream', 'buddystream_lang'),
                  'page_title' => __('Info', 'buddystream_lang'),
                  'access_level' => 10,
                  'position' => 100,
                  'file' => 'buddystream-admin',
                  'function' => 'buddystream_welcome',
                  'icon_url' => plugins_url(
                      'images/buddystream_icon.png',
                      __FILE__
                  )
                  )
          );
     
          if (!buddystreamCheckRequirements()) {

              if (get_site_option("buddystream_twitter_power")) {
                  add_submenu_page(
                      'buddystream-admin',
                      __('Twitter', 'buddystream_lang'),
                      __('Twitter', 'buddystream_lang'),
                      'manage_options',
                      'buddystream_twitter',
                      'buddystream_twitter'
                  );
              }

              if (get_site_option("buddystream_facebook_power")) {
                  add_submenu_page(
                      'buddystream-admin',
                      __('Facebook', 'buddystream_lang'),
                      __('Facebook', 'buddystream_lang'),
                      'manage_options',
                      'buddystream_facebook',
                      'buddystream_facebook'
                  );
              }

              if (get_site_option("buddystream_flickr_power")) {
                  add_submenu_page(
                      'buddystream-admin',
                      __('Flickr', 'buddystream_lang'),
                      __('Flickr', 'buddystream_lang'),
                      'manage_options',
                      'buddystream_flickr',
                      'buddystream_flickr'
                  );
              }

              if (get_site_option("buddystream_lastfm_power")) {
                  add_submenu_page(
                      'buddystream-admin',
                      __('Last.fm', 'buddystream_lang'),
                      __('Last.fm', 'buddystream_lang'),
                      'manage_options',
                      'buddystream_lastfm',
                      'buddystream_lastfm'
                  );
              }

              if (get_site_option("buddystream_youtube_power")) {
                  add_submenu_page(
                      'buddystream-admin',
                      __('Youtube', 'buddystream_lang'),
                      __('Youtube', 'buddystream_lang'),
                      'manage_options',
                      'buddystream_youtube',
                      'buddystream_youtube'
                  );
              }
        }
}


function buddystream_admin_add_settings_link( $links, $file ) {
	if ( 'buddystream/buddystream.php' != $file ) {
		return $links;
    } else {
        $settings_link = '<a href="' . admin_url( 'admin.php?page=buddystream-admin' ) . '">' . __( 'Settings', 'buddystream_lang' ) . '</a>';
        array_unshift( $links, $settings_link );
    }
    
	return $links;
}
add_filter( 'plugin_action_links', 'buddystream_admin_add_settings_link', 10, 2 );

##############################################
##                                          ##
##            admin buddystream             ##
##                                          ##
##############################################

function buddystream_welcome()
{
     if ($_GET["settings"] == "") {
        include "templates/default/Dashboard.php";
     } else if ($_GET["settings"] == "global") {
         include "templates/default/Global.php";
     }
}

##############################################
##                                          ##
##             twitter pages                ##
##                                          ##
##############################################

function buddystream_twitter()
{
     global $bp, $wpdb;

     if ($_GET["settings"] == "") {
        include "templates/twitter/AdminSettings.php";

     } else if ($_GET["settings"] == "filters") {
         include "templates/twitter/AdminFilters.php";
     

     } else if ($_GET["settings"] == "users") {
         include "templates/twitter/AdminUsers.php";


     } else if ($_GET["settings"] == "statitics") {
         include "templates/twitter/AdminStatitics.php";
     }
}

function buddystream_twitter_user_settings()
{
    global $bp;

    if ($bp->displayed_user->id != $bp->loggedin_user->id) {
        header('location:' . get_site_url());
    }

    add_action(
        'bp_template_title',
        'buddystream_twitter_settings_screen_title'
    );

    add_action(
        'bp_template_content',
        'buddystream_twitter_settings_screen_content'
    );

    bp_core_load_template(
        apply_filters(
            'bp_core_template_plugin',
            'members/single/plugins'
        )
    );
}

function buddystream_twitter_settings_screen_title()
{
    __('Twitter', 'tweetstream_lang');
}

function buddystream_twitter_settings_screen_content()
{
    global $bp;
    include "templates/twitter/UserSettings.php";
}


##############################################
##                                          ##
##             facebook pages               ##
##                                          ##
##############################################

function buddystream_facebook()
{
     global $bp, $wpdb;

     if ($_GET["settings"] == "") {
        include "templates/facebook/AdminSettings.php";

     } else if ($_GET["settings"] == "filters") {
         include "templates/facebook/AdminFilters.php";

     } else if ($_GET["settings"] == "users") {
         include "templates/facebook/AdminUsers.php";

     } else if ($_GET["settings"] == "statitics") {
         include "templates/facebook/AdminStatitics.php";
     }
}

function buddystream_facebook_user_settings()
{
    global $bp;

    if ($bp->displayed_user->id != $bp->loggedin_user->id) {
        header('location:' . get_site_url());
    }

    add_action(
        'bp_template_title',
        'buddystream_facebook_settings_screen_title'
    );

    add_action(
        'bp_template_content',
        'buddystream_facebook_settings_screen_content'
    );

    bp_core_load_template(
        apply_filters(
            'bp_core_template_plugin',
            'members/single/plugins'
        )
    );
}

function buddystream_facebook_settings_screen_title()
{
    __('Facebook', 'tweetstream_lang');
}

function buddystream_facebook_settings_screen_content()
{
    global $bp;
    include "templates/facebook/UserSettings.php";
}

##############################################
##                                          ##
##             flickr pages                 ##
##                                          ##
##############################################

function buddystream_flickr()
{
     global $bp, $wpdb;

     if ($_GET["settings"] == "") {
        include "templates/flickr/AdminSettings.php";

     } else if ($_GET["settings"] == "users") {
         include "templates/flickr/AdminUsers.php";

     } else if ($_GET["settings"] == "statitics") {
         include "templates/flickr/AdminStatitics.php";
     }
}

function buddystream_flickr_user_settings()
{
    global $bp;

    if ($bp->displayed_user->id != $bp->loggedin_user->id) {
        header('location:' . get_site_url());
    }

    add_action(
        'bp_template_title',
        'buddystream_flickr_settings_screen_title'
    );

    add_action(
        'bp_template_content',
        'buddystream_flickr_settings_screen_content'
    );

    bp_core_load_template(
        apply_filters(
            'bp_core_template_plugin',
            'members/single/plugins'
        )
    );
}

function buddystream_flickr_settings_screen_title()
{
    __('Flickr', 'tweetstream_lang');
}

function buddystream_flickr_settings_screen_content()
{
    global $bp;
    include "templates/flickr/UserSettings.php";
}

##############################################
##                                          ##
##            last.fm pages                 ##
##                                          ##
##############################################

function buddystream_lastfm()
{
     global $bp, $wpdb;

     if ($_GET["settings"] == "") {
        include "templates/lastfm/AdminSettings.php";

     } else if ($_GET["settings"] == "users") {
         include "templates/lastfm/AdminUsers.php";

     } else if ($_GET["settings"] == "statitics") {
         include "templates/lastfm/AdminStatitics.php";
     }
}

function buddystream_lastfm_user_settings()
{
    global $bp;

    if ($bp->displayed_user->id != $bp->loggedin_user->id) {
        header('location:' . get_site_url());
    }

    add_action(
        'bp_template_title',
        'buddystream_lastfm_settings_screen_title'
    );

    add_action(
        'bp_template_content',
        'buddystream_lastfm_settings_screen_content'
    );

    bp_core_load_template(
        apply_filters(
            'bp_core_template_plugin',
            'members/single/plugins'
        )
    );
}

function buddystream_lastfm_settings_screen_title()
{
    __('Last.fm', 'tweetstream_lang');
}

function buddystream_lastfm_settings_screen_content()
{
    global $bp;
    include "templates/lastfm/UserSettings.php";
}


##############################################
##                                          ##
##            youtube pages                 ##
##                                          ##
##############################################

function buddystream_youtube()
{
     global $bp, $wpdb;

     if ($_GET["settings"] == "") {
        include "templates/youtube/AdminSettings.php";

     } else if ($_GET["settings"] == "users") {
         include "templates/youtube/AdminUsers.php";

     } else if ($_GET["settings"] == "statitics") {
         include "templates/youtube/AdminStatitics.php";
     }
}

function buddystream_youtube_user_settings()
{
    global $bp;

    if ($bp->displayed_user->id != $bp->loggedin_user->id) {
        header('location:' . get_site_url());
    }

    add_action(
        'bp_template_title',
        'buddystream_youtube_settings_screen_title'
    );

    add_action(
        'bp_template_content',
        'buddystream_youtube_settings_screen_content'
    );

    bp_core_load_template(
        apply_filters(
            'bp_core_template_plugin',
            'members/single/plugins'
        )
    );
}

function buddystream_youtube_settings_screen_title()
{
    __('Youtube', 'tweetstream_lang');
}

function buddystream_youtube_settings_screen_content()
{
    global $bp;
    include "templates/youtube/UserSettings.php";
}

##############################################
##                                          ##
##     SEND MESSAGE TO SOCIAL NETWORK       ##
##                                          ##
##############################################
add_action('bp_activity_content_before_save', 'buddystream_socialIt',1);
function buddystream_SocialIt($content,$shortLink = null,$user_id = null)
{
    global $bp;

    //determine the user
    if(!$user_id){
        $user_id = $bp->loggedin_user->id;
    }

    //forum
    if ($bp->current_component == "groups" &&
       $bp->current_action == "forum") {

        if($_POST['topic_title']){
            $shortLink = bp_get_group_permalink($bp->groups->current_group) .
                        'forum/topic/' .
                         str_replace(
                             " ",
                             "-",
                             strtolower(
                                 $_POST['topic_title']
                             )
                         ) . '/';

            $shortLink = buddystream_getShortUrl($shortLink);
            $content = __('Just created a new topic:', 'buddystream_lang') .
                        " " . $_POST['topic_title'];
        }else{
            $shortLink = bp_get_group_permalink($bp->groups->current_group)
                        . 'forum/topic/' . $bp->action_variables[1] . '/';
            $shortLink = buddystream_getShortUrl($shortLink);

            $content = __('Just responded to:', 'buddystream_lang') .
                          " " . str_replace("-", " ", $bp->action_variables[1]);

            if (preg_match("/#twitter/i", $_POST['reply_text'])) {
                 $content .= "#twitter";
            }

            if (preg_match("/#facebook/i", $_POST['reply_text'])) {
                 $content .= "#facebook";
            }
        }
       }

    if (preg_match("/#twitter/i", $content)) {

        include_once "classes/twitter/BuddystreamTwitter.php";
        $twitter = new BuddystreamTwitter;
        
        $twitter->setConsumerKey(
            get_site_option("tweetstream_consumer_key")
        );
        
        $twitter->setConsumerSecret(
            get_site_option("tweetstream_consumer_secret")
        );

        $twitter->setAccessToken(
            get_usermeta($user_id, 'tweetstream_token')
        );

        $twitter->setAccessTokenSecret(
            get_usermeta($user_id, 'tweetstream_tokensecret')
        );

        $twitter->setShortLink($shortLink);
        $twitter->setPostContent(
            str_replace("#facebook", "", $content)
        );

        $twitter->postUpdate();
    }
    
    if (preg_match("/#facebook/i", $content)) {

        include_once "classes/facebook/BuddystreamFacebook.php";
        
        $facebook = new BuddystreamFacebook();

        $facebook->setCallbackUrl(
            get_site_url().'/?social=facebook'
        );
        
        $facebook->setApplicationKey(
            get_site_option("facestream_application_id")
        );

        $facebook->setApplicationId(
            get_site_option("facestream_application_id")
        );

        $facebook->setApplicationSecret(
            get_site_option("facestream_application_secret")
        );

        $facebook->setAccessToken(
            get_usermeta($user_id, 'facestream_session_key')
        );

        $facebook->setUserId(
            get_usermeta($user_id, 'facestream_user_id')
        );

        $facebook->setPostContent(
            str_replace("#twitter", "", $content)
        );

        $facebook->setShortLink($shortLink);
        $facebook->postUpdate();
    }

    $return = str_replace("#twitter", "", $content);
    $return = str_replace("#facebook", "", $return);

    return $return;
}

add_action('bp_get_activity_latest_update','removetags', 9);
function removetags($content)
{
    $content = str_replace('#facebook','',$content);
    $content = str_replace('#twitter','',$content);
    return $content;
}

add_filter ( 'group_forum_topic_title_before_save', 'tweetstream_topic' );
function tweetstream_topic() {
	$title = $_POST['topic_title'];
    $title = str_replace("#twitter", "", $title);
    $title = str_replace("#facebook", "", $title);
	return $title;
}

add_filter ( 'group_forum_post_text_before_save', 'buddystream_filtertags_fp', 9 );
function buddystream_filtertags_fp() {
	$content = $_POST ['reply_text'];
	if ($content == "") {
		$content = $_POST ['post_text'];
	}

    $content = str_replace("#twitter", "", $content);
    $content = str_replace("#facebook", "", $content);
	return $content;
}

################################################
##                                            ##
##   add extra filters option in dropdown     ##
##                                            ##
################################################

add_action('bp_activity_filter_options', 'buddystream_addFilter', 1);
add_action('bp_member_activity_filter_options', 'buddystream_addFilter', 1);

function buddystream_addFilter()
{
    if (get_site_option("buddystream_twitter_power")) {
        if (get_site_option("tweetstream_consumer_key")) {
            echo'<option value="tweet">' .
              __('Show Tweets', 'buddystream_lang') .
              '</option>';
        }
    }

    if (get_site_option("buddystream_facebook_power")) {
        if (get_site_option("facestream_application_id")) {
            echo'<option value="facebook">' .
                __('Show Facebook', 'buddystream_lang') .
                 '</option>';
        }
    }

    if (get_site_option("buddystream_flickr_power")) {
        if (get_site_option("bs_flickr_api_key")) {
            echo'<option value="flickr">' .
                __('Show Flickr', 'buddystream_lang') .
                 '</option>';
        }
    }

    if (get_site_option("buddystream_lastfm_power")) {
        echo'<option value="lastfm">' .
            __('Show Last.fm', 'buddystream_lang') .
             '</option>';
   }

   if (get_site_option("buddystream_youtube_power")) {
        echo'<option value="youtube">' .
            __('Show Youtube', 'buddystream_lang') .
             '</option>';
   }
}

################################################
##                                            ##
##        add sharing to update form          ##
##                                            ##
################################################

add_action('bp_activity_post_form_options', 'buddystream_addSharing');
add_action('groups_forum_new_topic_after', 'buddystream_addSharing');
add_action('bp_after_group_forum_post_new', 'buddystream_addSharing');
add_action('groups_forum_new_reply_after', 'buddystream_addSharing');

function buddystream_addSharing()
{
    global $bp;

    //twitter sharing
    if (get_site_option("buddystream_twitter_power")) {
        if (get_site_option("tweetstream_consumer_key")) {
            if (get_usermeta($bp->loggedin_user->id, 'tweetstream_token')) {
                echo'<div class="bs_share_button"
                        onclick="buddystream_addTag(\'#twitter\')">
                        <img src="'.plugins_url().'/buddystream/images/twitter/icon-small.png" title="'.__('to twitter', 'buddystream_lang').'"> '.__('to twitter', 'buddystream_lang').'
                     </div>';
            }
        }
    }

    //facebook sharing
    if (get_site_option("buddystream_facebook_power")) {
        if (get_site_option("facestream_application_id")) {
            if (get_usermeta($bp->loggedin_user->id, 'facestream_session_key')) {
                echo '<div class="bs_share_button"
                        onclick="buddystream_addTag(\'#facebook\')">
                      <img src="'.plugins_url().'/buddystream/images/facebook/icon-small.png" title="'.__('to facebook', 'buddystream_lang').'"> '.__('to facebook', 'buddystream_lang').'
                      </div>';
            }
        }
    }

    $max_message = __("You\'ve reached the max. amount of characters for a Tweet.  The Message will appear truncated on Twitter.", "buddystream_lang");

    echo '<div class="bs_share_counter">140</div>';

    echo "
        <script>
            function buddystream_addTag(tag)
            {
                if(jQuery('#whats-new').length){
                    var field = '#whats-new';
                }

                else if(jQuery('#topic_title').length){
                    var field = '#topic_title';
                }

                else if(jQuery('#reply_text').length){
                    var field = '#reply_text';
                }

                var content = jQuery(field).val();
                content = content.replace(tag,'');
                content  = tag+' '+content;

                if(tag == '#twitter'){
                    jQuery('.bs_share_counter').show();
                    countMessage(field);
                }

                jQuery(field).val(content);
            }


            if(jQuery('#whats-new').length){
                    var field = '#whats-new';
            }

            else if(jQuery('#topic_title').length){
                var field = '#topic_title';
            }

            else if(jQuery('#reply_text').length){
                var field = '#reply_text';
            }


        jQuery(field).keyup(function(){
              var text = jQuery(field).val();
              text = text.replace('#twitter','');
              text = text.replace('#facebook','');
              var textlength = parseInt(text.length);

              var patt1=/#twitter/gi;
              if(jQuery(field).val().match(patt1)){
                  jQuery('.bs_share_counter').show();

                  if(textlength > 140){
                      jQuery('.bs_share_counter').html('".$max_message."');
                  }else{
                      jQuery('.bs_share_counter').html('<b>'+(140-textlength)+'</b>');
                      return true;
                  }
                }else{
                   jQuery('.bs_share_counter').html('<b>140</b>');
                   jQuery('.bs_share_counter').hide();
                }
             })


        function countMessage(field){

              var text = jQuery(field).val();
              text = text.replace('#twitter','');
              text = text.replace('#facebook','');
              var textlength = parseInt(text.length);
                  if(textlength > 140){
                      jQuery('.bs_share_counter').html('".$max_message."');
                  }else{
                      jQuery('.bs_share_counter').html('<b>'+(140-textlength)+'</b>');
                      return true;
                  }
        }

        </script>
    ";
}

##############################################
##                                          ##
##             TRANSLATIONS                 ##
##                                          ##
##############################################

add_action('plugins_loaded', 'buddystream_textdomain', 9);
function buddystream_textdomain()
{
    $locale = apply_filters('buddystream_textdomain', get_locale());
    $mofile = WP_PLUGIN_DIR . "/buddystream/languages/$locale.mo";

    if (file_exists($mofile)) {
        load_textdomain('buddystream_lang', $mofile);
    }

    unset($locale);
    unset($mofile);
}

##############################################
##                                          ##
##             URL SHORTING                 ##
##                                          ##
##############################################
add_action('wp', 'buddystream_resolveShortUrl',1);
function buddystream_getShortUrl($url)
{
   global $bp;

   if ($url) {
       $input = date('dmyhis');
       $index ="abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
       $base = strlen($index);

       for ($t = floor(log($input, $base)); $t >= 0; $t--) {
           $bcp = pow($base, $t);
           $a = floor($input / $bcp) % $base;
           $out = $out . substr($index, $a, 1);
           $input = $input - ($a * $bcp);
       }
       $shortId = strrev($out);

       update_usermeta($bp->loggedin_user->id, 'buddystream_' . $shortId, $url);
       $url = get_site_url() . '/' . $shortId;

       return $url;
   } else {
       return false;
   }
}

add_action('wp', 'buddystream_resolveShortUrl',1);
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
                            
           $url = $usermeta->meta_value;
           if ($url) {
               header('location:' . $url);
           }
       }

   }
}

##############################################
##                                          ##
##             styling stuff                ##
##                                          ##
##############################################

add_action('wp_print_styles', 'buddystream_style');
function buddystream_style()
{
    wp_register_style(
       'buddystream_css',
       plugins_url() . '/buddystream/css/buddystream.css'
   );
   wp_enqueue_style('buddystream_css');

    wp_register_style(
       'buddystream_twitter_css',
       plugins_url() . '/buddystream/css/twitter.css'
   );
   wp_enqueue_style('buddystream_twitter_css');

   wp_register_style(
       'buddystream_facebook_css',
       plugins_url() . '/buddystream/css/facebook.css'
   );
   wp_enqueue_style('buddystream_facebook_css');


      wp_register_style(
       'buddystream_youtube_css',
       plugins_url() . '/buddystream/css/youtube.css'
   );
   wp_enqueue_style('buddystream_youtube_css');

   
   wp_register_style(
       'prettyphoto_css',
       WP_PLUGIN_URL . '/buddystream/js/prettyphoto/css/prettyPhoto.css'
   );
 
  wp_enqueue_style('prettyphoto_css');
  wp_enqueue_style('buddystream_prettyphoto_css');

  wp_enqueue_script('buddystream', plugins_url() . '/buddystream/js/main.js');
  wp_enqueue_script('prettyphoto', plugins_url() . '/buddystream/js/prettyphoto/jquery.prettyPhoto.js');

}

//filter to show last fm items on main activity, or user activity
add_filter( 'bp_ajax_querystring', 'bs_filter_query', 999, 2 );
function bs_filter_query( $qs, $object ) {

    global $bp;
    if (preg_match("/lastfm/i",$qs)) {
        return $qs."&show_hidden=true&scope=lastfm";
    }
   
    return $qs;
}

//delete item of social network (note delete but hide it!)
add_action('bp_activity_action_delete_activity', 'buddystream_delete_activity',2,999);
function buddystream_delete_activity($activity_id,$user_id)
{
    //get the activity information to see if it is a imported one
    $activity = new BP_Activity_Activity( $activity_id );
    if ($activity->type == "tweet" ||
        $activity->type == "facebook" ||
        $activity->type == "flickr" ||
        $activity->type == "lastfm" ||
        $activity->type == "youtube"
       ) {

           $blacklist_ids = get_usermeta($user_id, 'buddystream_blacklist_ids');
           if($blacklist_ids){
               $blacklist_ids .= ",".$activity->secondary_item_id;
           }else{
               $blacklist_ids = $activity->secondary_item_id;
           }
           update_usermeta($user_id,'buddystream_blacklist_ids', $blacklist_ids);
    }
}

function buddystreamCheckRequirements() {
    
    $error = false;
    
    if (phpversion() < '5.2.11') {
        $error .= "You got a older version of PHP installed (PHP ".phpversion()."), please upgrade to minimal version 5.2.11.<br>";
    }

    if (!extension_loaded("json")) {
        $error .= "The plugin needs JSON to be installed.<br>";
    }

    if (!extension_loaded("curl")) {
        $error .= "The plugin needs CURL to be installed.<br>";
    }

    return $error;
}

##############################################
##                                          ##
##             OTHER PLUGINS                ##
##                                          ##
##############################################


//achievements plugin hooks
if (defined('ACHIEVEMENTS_IS_INSTALLED')) {
    add_action('dpa_achievement_unlocked','buddystream_achievement_unlocked', 10, 2);
    function buddystream_achievement_unlocked($achievement_id,$user_id)
    {
        $content = __('I just unlocked the', 'buddystream_lang')." ".dpa_get_achievement_name()." ".__('achievement!', 'buddystream_lang');

        if(get_usermeta($user_id, 'tweetstream_achievements')){
            $content = "#twitter ".$content;
        }

        if(get_usermeta($user_id, 'facestream_achievements')){
            $content = "#facebook ".$content;
        }
     
        $shortLink = buddystream_getShortUrl(dpa_get_achievement_slug_permalink());
        buddystream_SocialIt($content,$shortLink,$user_id);
    }
}