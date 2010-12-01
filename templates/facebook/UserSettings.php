<?php
//oauth back from facebook
include_once WP_PLUGIN_DIR."/buddystream/classes/facebook/BuddystreamFacebook.php";

  if (isset($_GET['code'])) {
      $facebook = new BuddystreamFacebook();

      $facebook->setCallbackUrl(
          $bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-facebook'
      );

      $facebook->setApplicationId(
          get_site_option("facestream_application_id")
      );

      $facebook->setApplicationSecret(
          get_site_option("facestream_application_secret")
      );

      $facebook->setCode(
          $_GET['code']
      );

      $accessToken = $facebook->requestAccessToken();
      $facebook->setAccessToken($accessToken);

      update_usermeta(
          $bp->loggedin_user->id,
          'facestream_session_key',
          $accessToken
      );

      update_usermeta(
          $bp->loggedin_user->id,
          'facestream_user_id',
          $facebook->requestUser()->id
      );

      update_usermeta(
          $bp->loggedin_user->id,
          'facestream_synctoac',
          1
      );

      //for other plugins
      do_action('buddystream_facebook_activated');
  }


if ($_POST) {
    update_usermeta($bp->loggedin_user->id, 'facestream_synctoac', $_POST['facestream_synctoac']);
    update_usermeta($bp->loggedin_user->id, 'facestream_filtermentions', $_POST['facestream_filtermentions']);
    update_usermeta($bp->loggedin_user->id, 'facestream_filtergood', $_POST['facestream_filtergood']);
    update_usermeta($bp->loggedin_user->id, 'facestream_filterbad', $_POST['facestream_filterbad']);
    
    //achievement plugin
    update_usermeta($bp->loggedin_user->id, 'facestream_achievements', $_POST['facestream_achievements']);

    echo '<div id="message" class="updated fade">
            <p>' . __('Settings saved', 'buddystream_lang') . '</p>
        </div>';

    }

    //put some options into variables
    $facestream_synctoac = get_usermeta($bp->loggedin_user->id, 'facestream_synctoac');
    $facestream_filtermentions = get_usermeta($bp->loggedin_user->id, 'facestream_filtermentions');
    $facestream_filtergood = get_usermeta($bp->loggedin_user->id, 'facestream_filtergood');
    $facestream_filterbad = get_usermeta($bp->loggedin_user->id, 'facestream_filterbad');

    //achievement plugin
    $facestream_achievements = get_usermeta($bp->loggedin_user->id, 'facestream_achievements');

    if (get_usermeta($bp->loggedin_user->id, 'facestream_session_key')) {
        echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'settings/buddystream-facebook/" method="post">
        <h3>' . __('Facebook Settings', 'buddystream_lang') . '</h3>';
        ?>
   
        <?php
                if (get_site_option('facestream_user_settings_syncbp') == 0) {
        ?>

       <h5><?php echo __('Synchronize Facebook to my activity stream:', 'buddystream_lang'); ?></h5>
       <input type="radio" name="facestream_synctoac"
           id="facestream_synctoac" value="1"
           <?php
           if ($facestream_synctoac == 1) {
               echo 'checked';
           }
        ?>> <label for="yes"><?php echo __('Yes','buddsytream_lang');?> </label><br>

       <input type="radio" name="facestream_synctoac"
           id="facestream_synctoac" value="0"
           <?php
           if ($facestream_synctoac == 0) {
               echo 'checked';
           }
        ?>> <label for="no"><?php echo __('No','buddsytream_lang');?></label><br>

        <?php } ?>
        <?php if (get_site_option('facestream_user_settings_syncbp') == 0) { ?>

       <br><h5><?php echo __('Filters', 'buddystream_lang');?></h5>
        <?php echo __('By using filters, you may decide which Tweets will be imported or excluded.<br>
        <b>Note:</b> Each site also has a "Global" list of filters and its settings will override these settings. <br>
        <b>Note:</b> Keywords listed in the "Good" Filter must be present in the update itself to be included in the site import. Likewise, keywords listed in the "Explicit Filter" will cause that particular update NOT to be imported.', 'buddystream_lang');
        ?>
       <br>
        <br><h5><?php echo __('Good Filter (separate words with commas)', 'buddystream_lang');?></h5>
        <input type="text" name="facestream_filtergood"
            value="<?php echo $facestream_filtergood;?>" size="50" /><br>

        <br><h5><?php echo __('Bad Filter (separate words with commas)', 'buddystream_lang');?></h5>
        <input type="text" name="facestream_filterbad" value="<?php echo $facestream_filterbad;?>" size="50" /><br>

        <?php if(get_site_option('facestream_user_settings_syncupdatesbp')==0){ ?>
            <br><h5><?php echo __( 'Synchronize updates to my activity'  , 'buddystream_lang' );?></h5>
            <input type="radio" name="facestream_syncupdatestoac" id="facestream_syncupdatestoac" value="0" <?php if($facestream_syncupdatestoac==0){echo'checked';}?>> <label for="yes"><?php echo __('Yes','buddsytream_lang');?></label><br>
            <input type="radio" name="facestream_syncupdatestoac" id="facestream_syncupdatestoac" value="1" <?php if($facestream_syncupdatestoac==1){echo'checked';}?>> <label for="no"><?php echo __('No','buddsytream_lang');?></label><br>
        <?php } ?>

        <?php if(get_site_option('facestream_user_settings_synclinksbp')==0){ ?>
            <br><h5><?php echo __( 'Synchronize links to my activity'  , 'buddystream_lang' );?></h5>
    		<input type="radio" name="facestream_synclinkstoac" id="facestream_synclinkstoac" value="0" <?php if($facestream_synclinkstoac==0){echo'checked';}?>> <label for="yes"><?php echo __('Yes','buddsytream_lang');?></label><br>
    		<input type="radio" name="facestream_synclinkstoac" id="facestream_synclinkstoac" value="1" <?php if($facestream_synclinkstoac==1){echo'checked';}?>> <label for="no"><?php echo __('No','buddsytream_lang');?></label><br>
    	<?php } ?>

        <?php if(get_site_option('facestream_user_settings_syncphotosbp')==0){ ?>
            <br><h5><?php echo __( 'Synchronize photos to my activity'  , 'buddystream_lang' );?></h5>
            <input type="radio" name="facestream_syncphotostoac" id="facestream_syncphotostoac" value="0" <?php if($facestream_syncphotostoac==0){echo'checked';}?>> <label for="yes"><?php echo __('Yes','buddsytream_lang');?></label><br>
            <input type="radio" name="facestream_syncphotostoac" id="facestream_syncphotostoac" value="1" <?php if($facestream_syncphotostoac==1){echo'checked';}?>> <label for="no"><?php echo __('No','buddsytream_lang');?></label><br>
        <?php } ?>

    	<?php if(get_site_option('facestream_user_settings_syncvideosbp')==0){ ?>
            <br><h5><?php echo __( 'Synchronize videos to my activity'  , 'buddystream_lang' );?></h5>
    		<input type="radio" name="facestream_syncvideostoac" id="facestream_syncvideostoac" value="0" <?php if($facestream_syncvideostoac==0){echo'checked';}?>> <label for="yes"><?php echo __('Yes','buddsytream_lang');?></label><br>
    		<input type="radio" name="facestream_syncvideostoac" id="facestream_syncvideostoac" value="1" <?php if($facestream_syncvideostoac==1){echo'checked';}?>> <label for="no"><?php echo __('No','buddsytream_lang');?></label><br>
    	<?php } ?>


        <?php if(defined('ACHIEVEMENTS_IS_INSTALLED')){ ?>
            <br><h5><?php echo __( 'Send achievements unlock to my facebook'  , 'buddystream_lang' );?></h5>
    		<input type="radio" name="facestream_achievements" id="facestream_achievements" value="1" <?php if($facestream_achievements==1){echo'checked';}?>> <label for="yes"><?php echo __('Yes','buddsytream_lang');?></label><br>
    		<input type="radio" name="facestream_achievements" id="facestream_achievements" value="0" <?php if($facestream_achievements==0){echo'checked';}?>> <label for="no"><?php echo __('No','buddsytream_lang');?></label><br>
    	<?php } ?>

<?php
                              }
?>

           <input type="submit"
                  value="<?php
                              echo __('Save settings', 'buddystream_lang');
?>">
</form>
<?php

                          } else {
                              echo '<b>' . __('Permission', 'buddystream_lang') . '</b><br>' . __('You can setup facebook over here.', 'buddystream_lang') . '<br>
			' . __('Before u can see al settings please authorize on facebook, to do so click on the link below.', 'buddystream_lang') . '<br><br>';

                              //facebook class
                              $facebook = new Buddystreamfacebook;
                              $facebook->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-facebook');
                              $facebook->setApplicationId(get_site_option("facestream_application_id"));
                              echo '<a href="' . $facebook->getRedirectUrl() . '">' . __('Authorize with facebook', 'buddystream_lang') . '</a><br/><br/>';

                          }