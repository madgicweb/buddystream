<link rel="stylesheet" href="<?= WP_PLUGIN_URL . '/buddystream/css/buddystream.css';?>" type="text/css" />
<br>
<?php include "AdminMenu.php"; ?>
<?php
global $bp;

if ($_POST) {
      update_site_option('buddystream_server_cron', trim(strip_tags($_POST['buddystream_server_cron'])));

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
<?
$error = buddystream_check_requirements();
if ($error) {
    echo '<br><br><div class="bs_error_box"><strong>Wait on! There is something wrong!</strong><br/><br/>'.$error.'</div>';
    return;
}
?>

<br/>

<form id="settings_form" action="" method="post">

    <div class="bs_info_box">
<?php echo __('You may choose to use the cronjob on your server.<br>
    If you select "yes", you will have to create a cron on your server that runs every minute.<br>
    You may copy the generated cronjob information to your server from BuddyStream.<br>
    If you choose, "no" the social network import function will happen in the "background" when a vistor arrives on your website', 'buddystream_lang');?>
    </div>
<br/>
<br/>
<input type="radio" name="buddystream_server_cron" id="buddystream_server_cron" value="1" <?php if(get_site_option('buddystream_server_cron')==1){echo'checked';}?>> <?= __('Yes - Turn on cronjob', 'buddystream_lang');?><br/>
<input type="radio" name="buddystream_server_cron" id="buddystream_server_cron" value="0" <?php if(get_site_option('buddystream_server_cron')==0){echo'checked';}?>> <?= __('No - Use "background" importing', 'buddystream_lang');?><br/>
<br/>


<?php
if(get_site_option('buddystream_server_cron')==1){ ?>
    <h2><?php echo __('Cronjob command', 'buddystream_lang');?>:</h2><br/>
    <input type="text" name="cronurl" value="<?= "* * * * * wget -q -nc ".$bp->root_domain."/?buddystreamcron=run";?>" size="150" />
<?php } ?>


<br><br>
<b><?php echo __('Last cron run was on'); ?></b> <?= get_site_option("buddystream_cron_stamp");?> <b><?= __('and toke','buddystream_lang');?></b> <?= round(get_site_option("buddystream_cron_runtime"));?> <? echo __('seconds');?>
<br><br>


<h2><?php echo __('Social networks power management'); ?></h2>

<h4><?php echo __('Turn on/off Twitter integration', 'buddystream_lang');?></h4>
<input type="radio" name="buddystream_twitter_power" id="buddystream_server_cron" value="1" <?php if(get_site_option('buddystream_twitter_power')==1){echo'checked';}?>> <?= __('On','buddystream_lang');?><br/>
<input type="radio" name="buddystream_twitter_power" id="buddystream_server_cron" value="0" <?php if(get_site_option('buddystream_twitter_power')==0){echo'checked';}?>> <?= __('Off','buddystream_lang');?><br/>
<br/>

<h4><?php echo __('Turn on/off Facebook integration', 'buddystream_lang');?></h4>
<input type="radio" name="buddystream_facebook_power" id="buddystream_server_cron" value="1" <?php if(get_site_option('buddystream_facebook_power')==1){echo'checked';}?>> <?= __('On','buddystream_lang');?><br/>
<input type="radio" name="buddystream_facebook_power" id="buddystream_server_cron" value="0" <?php if(get_site_option('buddystream_facebook_power')==0){echo'checked';}?>> <?= __('Off','buddystream_lang');?><br/>
<br/>

<h4><?php echo __('Turn on/off Flickr integration', 'buddystream_lang');?></h4>
<input type="radio" name="buddystream_flickr_power" id="buddystream_server_cron" value="1" <?php if(get_site_option('buddystream_flickr_power')==1){echo'checked';}?>> <?= __('On','buddystream_lang');?><br/>
<input type="radio" name="buddystream_flickr_power" id="buddystream_server_cron" value="0" <?php if(get_site_option('buddystream_flickr_power')==0){echo'checked';}?>> <?= __('Off','buddystream_lang');?><br/>
<br/>

<h4><?php echo __('Turn on/off Last.fm integration', 'buddystream_lang');?></h4>
<input type="radio" name="buddystream_lastfm_power" id="buddystream_server_cron" value="1" <?php if(get_site_option('buddystream_lastfm_power')==1){echo'checked';}?>> <?= __('On','buddystream_lang');?><br/>
<input type="radio" name="buddystream_lastfm_power" id="buddystream_server_cron" value="0" <?php if(get_site_option('buddystream_lastfm_power')==0){echo'checked';}?>> <?= __('Off','buddystream_lang');?><br/>
<br/>

<h4><?php echo __('Turn on/off Youtube integration', 'buddystream_lang');?></h4>
<input type="radio" name="buddystream_youtube_power" id="buddystream_server_cron" value="1" <?php if(get_site_option('buddystream_youtube_power')==1){echo'checked';}?>> <?= __('On','buddystream_lang');?><br/>
<input type="radio" name="buddystream_youtube_power" id="buddystream_server_cron" value="0" <?php if(get_site_option('buddystream_youtube_power')==0){echo'checked';}?>> <?= __('Off','buddystream_lang');?><br/>
<br/>

<p class="submit"><input type="submit" class="button-primary" value="<?php echo __('Save Changes') ?>" /></p>
<br/>
<br/>

</div>