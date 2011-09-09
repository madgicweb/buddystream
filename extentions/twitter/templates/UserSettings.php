<?php

global $bp;

if($_GET['reset'] == 'true'){
    delete_user_meta($bp->loggedin_user->id,'tweetstream_token');
    delete_user_meta($bp->loggedin_user->id,'tweetstream_tokensecret');
    delete_user_meta($bp->loggedin_user->id,'tweetstream_mention');
    delete_user_meta($bp->loggedin_user->id,'tweetstream_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'tweetstream_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'tweetstream_filtermentions');
    delete_user_meta($bp->loggedin_user->id, 'tweetstream_filtergood');
    delete_user_meta($bp->loggedin_user->id, 'tweetstream_filterbad');
}

if (isset($_GET['oauth_token'])) {

      $twitter = new BuddystreamTwitter();
      $twitter->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-twitter');
      $twitter->setConsumerKey(get_site_option("tweetstream_consumer_key"));
      $twitter->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));

      $consumer = $twitter->getConsumer();
      $token    = $consumer->getAccessToken($_GET, $twitter->getTwitterToken());
      
      update_user_meta($bp->loggedin_user->id,'tweetstream_token',''.$token->oauth_token.'');
      update_user_meta($bp->loggedin_user->id,'tweetstream_tokensecret',''.$token->oauth_token_secret.'');
      update_user_meta($bp->loggedin_user->id,'tweetstream_mention', $token->screen_name);
      update_user_meta($bp->loggedin_user->id,'tweetstream_synctoac', 1);

      //for other plugins
      do_action('buddystream_twitter_activated');

  }

if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'tweetstream_synctoac', $_POST['tweetstream_synctoac']);
    update_user_meta($bp->loggedin_user->id, 'tweetstream_filtermentions', $_POST['tweetstream_filtermentions']);
    update_user_meta($bp->loggedin_user->id, 'tweetstream_filtergood', $_POST['tweetstream_filtergood']);
    update_user_meta($bp->loggedin_user->id, 'tweetstream_filterbad', $_POST['tweetstream_filterbad']);

    //achievements plugins
    update_user_meta($bp->loggedin_user->id, 'tweetstream_achievements', $_POST['tweetstream_achievements']);

    echo '<div id="message" class="updated fade">
            <p>' . __('Settings saved', 'buddystream_twitter') . '</p>
        </div>';
    }

    //put some options into variables
    $tweetstream_synctoac       = get_user_meta($bp->loggedin_user->id, 'tweetstream_synctoac',1);
    $tweetstream_filtermentions = get_user_meta($bp->loggedin_user->id, 'tweetstream_filtermentions',1);
    $tweetstream_filtergood     = get_user_meta($bp->loggedin_user->id, 'tweetstream_filtergood',1);
    $tweetstream_filterbad      = get_user_meta($bp->loggedin_user->id, 'tweetstream_filterbad',1);

    //achievements plugin
    $tweetstream_achievements   = get_user_meta($bp->loggedin_user->id, 'tweetstream_achievements',1);

    if (get_user_meta($bp->loggedin_user->id, 'tweetstream_token',1)) {
        echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'settings/buddystream-twitter/" method="post">
        <h3>' . __('Twitter Settings', 'buddystream_twitter') . '</h3>';
        ?>
   
        <?php if (get_site_option('tweetstream_user_settings_syncbp') == 0) { ?>

        <br/><h5><?php echo __('Synchronize Tweets to my activity stream?', 'buddystream_twitter'); ?></h5>
        <input type="radio" name="tweetstream_synctoac" id="tweetstream_synctoac" value="1" <?php if ($tweetstream_synctoac == 1) { echo 'checked'; } ?> />
        <label for="yes"><?php echo __('Yes','buddystream_twitter'); ?></label>
        
        <input type="radio" name="tweetstream_synctoac" id="tweetstream_synctoac" value="0" <?php if ($tweetstream_synctoac == 0) { echo 'checked'; } ?> />
        <label for="no"><?php echo __('No','buddystream_twitter'); ?></label>

        <br/>
        <?php } ?>
        <?php if (get_site_option('tweetstream_user_settings_syncbp') == 0) { ?>

        <br/><h5><?php _e('Filters', 'buddystream_twitter');?></h5>
        <?php _e('user settings', 'buddystream_twitter'); ?><br />
        
        <br/><h5><?php echo __('Good Filter (separate words with commas)', 'buddystream_twitter');?></h5>
        <input type="text" name="tweetstream_filtergood" value="<?php echo $tweetstream_filtergood;?>" size="50" />
        
        <br/><h5><?php echo __('Bad Filter (separate words with commas)', 'buddystream_twitter'); ?></h5>
        <input type="text" name="tweetstream_filterbad" value="<?php echo $tweetstream_filterbad;?>" size="50" />

        <?php if(defined('ACHIEVEMENTS_IS_INSTALLED')){ ?>
            <br/><h5><?php echo __( 'Send achievements unlock to my twitter'  , 'buddystream_twitter' );?></h5>
    		<input type="radio" name="tweetstream_achievements" id="tweetstream_achievements" value="1" <?php if($tweetstream_achievements==1){echo'checked';}?>> <?php echo __('Yes','buddsytream_lang');?><br/>
    		<input type="radio" name="tweetstream_achievements" id="tweetstream_achievements" value="0" <?php if($tweetstream_achievements==0){echo'checked';}?>> <?php echo __('No','buddsytream_lang');?><br/>
    	<?php } ?>

        <?php } ?>

        <br/><input type="submit" value="<?php echo __('Save settings', 'buddystream_twitter'); ?>" />
        </form>
        
        <?php
         }else{
             if(buddystreamCheckNetwork("http://twitter.com")){
                 echo '<h3>' . __('Twitter setup</h3>
                 You may setup you twitter intergration over here.<br/>
                 Before you can begin using Twitter with this site you must authorize on Twitter by clicking the link below.', 'buddystream_twitter') . '<br/><br/>';

                 //tweetstream twitterclass
                 $twitter = new BuddystreamTwitter;
                 $twitter->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-twitter');
                 $twitter->setConsumerKey(get_site_option("tweetstream_consumer_key"));
                 $twitter->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));
                 
                 $redirectUrl = $twitter->getRedirectUrl();
                 if($redirectUrl){
                 
                 echo '<a href="' . $twitter->getRedirectUrl() . '">' . __('Click here to start authorization', 'buddystream_twitter') . '</a><br/><br/>';
                 }else{
                     _e('There is a problem with the authentication service at this moment please come back in a while.','buddystream_twitter');
                 }
             }else{
               _e('Twitter is offline currently please come back in a while.','buddystream_twitter');
             }
      }