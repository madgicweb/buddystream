<?php
if ($_POST) {
    update_usermeta($bp->loggedin_user->id, 'tweetstream_synctoac', $_POST['tweetstream_synctoac']);
    update_usermeta($bp->loggedin_user->id, 'tweetstream_filtermentions', $_POST['tweetstream_filtermentions']);
    update_usermeta($bp->loggedin_user->id, 'tweetstream_filtergood', $_POST['tweetstream_filtergood']);
    update_usermeta($bp->loggedin_user->id, 'tweetstream_filterbad', $_POST['tweetstream_filterbad']);

    //achievements plugins
    update_usermeta($bp->loggedin_user->id, 'tweetstream_achievements', $_POST['tweetstream_achievements']);

    echo '<div id="message" class="updated fade">
            <p>' . __('Settings saved', 'buddystream_lang') . '</p>
        </div>';
    }

    //put some options into variables
    $tweetstream_synctoac = get_usermeta($bp->loggedin_user->id, 'tweetstream_synctoac');
    $tweetstream_filtermentions = get_usermeta($bp->loggedin_user->id, 'tweetstream_filtermentions');
    $tweetstream_filtergood = get_usermeta($bp->loggedin_user->id, 'tweetstream_filtergood');
    $tweetstream_filterbad = get_usermeta($bp->loggedin_user->id, 'tweetstream_filterbad');

    //achievements plugin
    $tweetstream_achievements = get_usermeta($bp->loggedin_user->id, 'tweetstream_achievements');

    if (get_usermeta($bp->loggedin_user->id, 'tweetstream_token')) {
        echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'settings/buddystream-twitter/" method="post">
        <h3>' . __('Twitter Settings', 'buddystream_lang') . '</h3>';
        ?>
   
        <?php
           if (get_site_option('tweetstream_user_settings_syncbp') == 0) {
        ?>

        <br><h5><?php echo __('Synchronize Tweets to my activity stream?', 'buddystream_lang'); ?></h5>
        <input type="radio" name="tweetstream_synctoac" id="tweetstream_synctoac" value="1"
           <?php
           if ($tweetstream_synctoac == 1) {
               echo 'checked';
           }
        ?>> <?= __('Yes','buddystream_lang'); ?>
        <input type="radio" name="tweetstream_synctoac" id="tweetstream_synctoac" value="0"
           <?php
           if ($tweetstream_synctoac == 0) {
               echo 'checked';
           }
        ?>> <?= __('No','buddystream_lang'); ?>


        <br>
        <?php } ?>
        <?php if (get_site_option('tweetstream_user_settings_syncbp') == 0) { ?>

        <br><h5><?php echo __('Filters', 'buddystream_lang');?></h5>
        <?php echo __('By using filters, you may decide which Tweets will be imported or excluded.<br>
        By adding words to the "Good Filter", only Tweets containing those words will be imported. <br>
        By adding words to the "Bad Filter", Tweets containing those words will NOT be imported. <br>
        Note: Each site also has a "Global" list of filters and its settings will override these settings. ', 'buddystream_lang'); ?><br />
        
        <br><h5><?php echo __('Good Filter (separate words with commas)', 'buddystream_lang');?></h5>
        <input type="text" name="tweetstream_filtergood" value="<?php echo $tweetstream_filtergood;?>" size="50" />
        
        <br><h5><?php echo __('Bad Filter (separate words with commas)', 'buddystream_lang'); ?></h5>
        <input type="text" name="tweetstream_filterbad" value="<?php echo $tweetstream_filterbad;?>" size="50" />

        <?php if(defined('ACHIEVEMENTS_IS_INSTALLED')){ ?>
            <br><h5><?php echo __( 'Send achievements unlock to my twitter'  , 'buddystream_lang' );?></h5>
    		<input type="radio" name="tweetstream_achievements" id="tweetstream_achievements" value="1" <?php if($tweetstream_achievements==1){echo'checked';}?>> <?= __('Yes','buddsytream_lang');?><br>
    		<input type="radio" name="tweetstream_achievements" id="tweetstream_achievements" value="0" <?php if($tweetstream_achievements==0){echo'checked';}?>> <?= __('No','buddsytream_lang');?><br>
    	<?php } ?>

        <?php } ?>

        <br><input type="submit" value="<?php echo __('Save settings', 'buddystream_lang'); ?>">
        </form>
        
        <?php
         }else{
         echo '<h3>' . __('Twitter setup</h3>
         You may setup you twitter intergration over here.<br>
         Before you can begin using Twitter with this site you must authorize on Twitter by clicking the link below.', 'buddystream_lang') . '<br><br>';

         //tweetstream twitterclass
         include_once "classes/twitter/BuddystreamTwitter.php";

         $twitter = new BuddystreamTwitter;
         $twitter->setCallbackUrl($bp->root_domain.'/?social=twitter');
         $twitter->setConsumerKey(get_site_option("tweetstream_consumer_key"));
         $twitter->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));
         echo '<a href="' . $twitter->getRedirectUrl() . '">' . __('Click here to start authorization', 'buddystream_lang') . '</a><br/><br/>';
      }