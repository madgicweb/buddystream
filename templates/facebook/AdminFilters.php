<link rel="stylesheet" href="<?= WP_PLUGIN_URL . '/buddystream/css/buddystream.css';?>" type="text/css" />
<br/>
<?php include "AdminMenu.php"; ?>

<?php
if ($_POST) {
   update_site_option('facestream_filter', trim(strip_tags(strtolower($_POST ['facestream_filter']))));
   update_site_option('facestream_filter_show', trim(strip_tags($_POST ['facestream_filter_show'])));
   update_site_option('facestream_filterexplicit', trim(strip_tags(strtolower($_POST ['facestream_filterexplicit']))));
   echo '<div class="updated" style="margin-top:50px;"><p><strong>' . __('Filters saved.', 'facestream_lang') . '</strong></p></div>';
}
?>
<div class="wrap"><br/>
    <h2 style="float: left; line-height: 5px; padding-left: 5px;"><?php echo __('Filters (optional)');?></h2><br />
    <br><br>
    <div class="bs_info_box">
    <?php echo __('
    Using Facebook filters will prevent overcrowded and "messy" activity streams.<br>
    Example: You may have a network what focuses on soccer. <br>To keep things "clean" you will only want items that pertain to soccer to be imported.<br>
    By adding the word "soccer" to the filter below, only items with the word "soccer" will be imported and shown in the users activity stream.<br><br>
    By using commas as a delimiter  you may set-up multiple filters (No filer = all Facebook items.)<br>
    In addition, the "Explicit words" filter, will block any item that contains them regardless of the other filter.
', 'facestream_lang');?></div><br>

    <form method="post" action="">
        <table class="form-table">
           
            <tr valign="top">
                <th scope="row"><?php echo __('Filters (comma seperated)', 'facestream_lang');?></th>
                <td>
                    <input type="text" name="facestream_filter"value="<?php echo get_site_option('facestream_filter');?>" size="50" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php echo __('Explicit words filters (comma seperated)', 'facestream_lang');?></th>
                <td>
                    <input type="text" name="facestream_filterexplicit" value="<?php echo get_site_option('facestream_filterexplicit');?>" size="50" />
                </td>
            </tr>
        </table>
        <p class="submit"><input type="submit" class="button-primary" value="<?php echo __('Save Changes') ?>" /></p>
    </form>
</div>