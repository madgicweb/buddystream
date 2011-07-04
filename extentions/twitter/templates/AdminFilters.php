<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php echo buddystreamTabloader('twitter'); ?>

<?php
if ($_POST) {
   update_site_option('tweetstream_filter', trim(strip_tags(strtolower($_POST ['tweetstream_filter']))));
   update_site_option('tweetstream_filter_show', trim(strip_tags($_POST ['tweetstream_filter_show'])));
   update_site_option('tweetstream_filterexplicit', trim(strip_tags(strtolower($_POST ['tweetstream_filterexplicit']))));
   echo '<div class="buddystream_info_box_green" style="margin-top:50px;">' . __('Filters saved.', 'buddystream_twitter') . '</div>';
}
?>

    <div class="buddystream_info_box">
    <?php _e('twitter filters description','buddystream_twitter');?>
    </div>

    <form method="post" action="">
        <table class="buddystream_table" cellspacing="0">
           
            <tr class="header">
                <td colspan="2"><?php echo __('Twitter filters (optional)', 'buddystream_twitter');?></td>
            </tr>
            
            <tr>
                <td><?php echo __('Filters (comma seperated)', 'buddystream_twitter');?></td>
                <td><input type="text" name="tweetstream_filter"value="<?php echo get_site_option('tweetstream_filter');?>" size="50" /></td>
            </tr>

            <tr class="odd">
                <td><?php echo __('Explicit words (comma seperated)', 'buddystream_twitter');?></td>
                <td><input type="text" name="tweetstream_filterexplicit" value="<?php echo get_site_option('tweetstream_filterexplicit');?>" size="50" /></td>
            </tr>
        </table>
        <p class="submit"><input type="submit" class="button-primary" value="<?php echo __('Save Changes') ?>" /></p>
    </form>