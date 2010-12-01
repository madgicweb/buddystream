<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/css/buddystream.css';?>" type="text/css" />
<br>
<?php include "AdminMenu.php"; ?>
<?php
global $bp;

if ($_POST) {
      update_site_option('buddystream_twitter_power', trim(strip_tags($_POST['buddystream_twitter_power'])));
      update_site_option('buddystream_facebook_power', trim(strip_tags($_POST['buddystream_facebook_power'])));
      update_site_option('buddystream_flickr_power', trim(strip_tags($_POST['buddystream_flickr_power'])));
      update_site_option('buddystream_lastfm_power', trim(strip_tags($_POST['buddystream_lastfm_power'])));
      update_site_option('buddystream_youtube_power', trim(strip_tags($_POST['buddystream_youtube_power'])));
      echo '<div class="updated" style="margin-top:50px;"><p><strong>' . __('Settings saved.', 'buddystream_lang') . '</strong></p></div>';
   }
?>


<div class="wrap"><br />

<h2 style="float: left; line-height: 5px; padding-left: 5px;">
    <?php echo __('BuddyStream Import Settings'); ?>
</h2>


<br/><br/>
<?php
$error = buddystreamCheckRequirements();
if ($error) {
    echo '<br><br><div class="bs_error_box"><strong>Wait on! There is something wrong!</strong><br/><br/>'.$error.'</div>';
}else{
?>
<br/>

<form id="settings_form" action="" method="post">

    <div class="bs_info_box">
<?php echo __('You need to use the cronjob on your server.<br>
    Create a cron on your server that runs every 5 minutes.<br>
    You may copy the generated cronjob information to your server from BuddyStream.<br>', 'buddystream_lang');?>
    </div>
<br/>
<br/>


<h2><?php echo __('Cronjob command', 'buddystream_lang');?>:</h2><br/>
<input type="text" name="cronurl" value="<?php echo "*/5 * * * * wget ".plugins_url()."/buddystream/import.php >/dev/null 2>&1";?>" size="150" />

<br><br>
<b><?php echo __('Last cron run was on'); ?></b> <?php echo get_site_option("buddystream_cron_stamp");?>
<br><br>


<h2><?php echo __('Social networks power management'); ?></h2>

<h4><?php echo __('Turn on/off Twitter integration', 'buddystream_lang');?></h4>
<input type="radio" name="buddystream_twitter_power" id="buddystream_server_cron" value="1" <?php if(get_site_option('buddystream_twitter_power')==1){echo'checked';}?>> <label for="on"><?php echo __('On','buddystream_lang');?></label><br/>
<input type="radio" name="buddystream_twitter_power" id="buddystream_server_cron" value="0" <?php if(get_site_option('buddystream_twitter_power')==0){echo'checked';}?>> <label for="off"><?php echo __('Off','buddystream_lang');?></label><br/>
<br/>

<h4><?php echo __('Turn on/off Facebook integration', 'buddystream_lang');?></h4>
<input type="radio" name="buddystream_facebook_power" id="buddystream_server_cron" value="1" <?php if(get_site_option('buddystream_facebook_power')==1){echo'checked';}?>> <label for="on"><?php echo __('On','buddystream_lang');?></label><br/>
<input type="radio" name="buddystream_facebook_power" id="buddystream_server_cron" value="0" <?php if(get_site_option('buddystream_facebook_power')==0){echo'checked';}?>> <label for="off"><?php echo __('Off','buddystream_lang');?></label><br/>
<br/>

<h4><?php echo __('Turn on/off Flickr integration', 'buddystream_lang');?></h4>
<input type="radio" name="buddystream_flickr_power" id="buddystream_server_cron" value="1" <?php if(get_site_option('buddystream_flickr_power')==1){echo'checked';}?>> <label for="on"><?php echo __('On','buddystream_lang');?></label><br/>
<input type="radio" name="buddystream_flickr_power" id="buddystream_server_cron" value="0" <?php if(get_site_option('buddystream_flickr_power')==0){echo'checked';}?>> <label for="off"><?php echo __('Off','buddystream_lang');?></label><br/>
<br/>

<h4><?php echo __('Turn on/off Last.fm integration', 'buddystream_lang');?></h4>
<input type="radio" name="buddystream_lastfm_power" id="buddystream_server_cron" value="1" <?php if(get_site_option('buddystream_lastfm_power')==1){echo'checked';}?>> <label for="on"><?php echo __('On','buddystream_lang');?></label><br/>
<input type="radio" name="buddystream_lastfm_power" id="buddystream_server_cron" value="0" <?php if(get_site_option('buddystream_lastfm_power')==0){echo'checked';}?>> <label for="off"><?php echo __('Off','buddystream_lang');?></label><br/>
<br/>

<h4><?php echo __('Turn on/off Youtube integration', 'buddystream_lang');?></h4>
<input type="radio" name="buddystream_youtube_power" id="buddystream_server_cron" value="1" <?php if(get_site_option('buddystream_youtube_power')==1){echo'checked';}?>> <label for="on"><?php echo __('On','buddystream_lang');?></label><br/>
<input type="radio" name="buddystream_youtube_power" id="buddystream_server_cron" value="0" <?php if(get_site_option('buddystream_youtube_power')==0){echo'checked';}?>> <label for="off"><?php echo __('Off','buddystream_lang');?></label><br/>
<br/>

<p class="submit"><input type="submit" class="button-primary" value="<?php echo __('Save Changes') ?>" /></p>
<br/>
<br/>
<?php } ?>
</div>