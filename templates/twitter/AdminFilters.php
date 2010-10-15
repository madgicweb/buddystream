<br/>
<?php include "AdminMenu.php"; ?>

<?php
if ($_POST) {
   update_site_option('tweetstream_filter', trim(strip_tags(strtolower($_POST ['tweetstream_filter']))));
   update_site_option('tweetstream_filter_show', trim(strip_tags($_POST ['tweetstream_filter_show'])));
   update_site_option('tweetstream_filterexplicit', trim(strip_tags(strtolower($_POST ['tweetstream_filterexplicit']))));
   echo '<div class="updated" style="margin-top:50px;"><p><strong>' . __('Filters saved.', 'buddystream_lang') . '</strong></p></div>';
}
?>
<div class="wrap"><br/>
    <h2 style="float: left; line-height: 5px; padding-left: 5px;"><?php echo __('Filters (optional)');?></h2><br />

    <form method="post" action="">
        <table class="form-table">
            <tr>
                <td colspan="2"><br>
                    <?php echo 
                    __('Using Twitter Filters will prevent overcrowded and "messy" activity streams. <br>
                        Example: You may have a network that focuses on soccer.  To keep things "clean" you will only want Tweets that pertain to soccer to be imported. By adding the word "soccer" to the Filter below, only Tweets with the word "soccer" will be imported and shown in the users Tweets on your site.
                        <br>
                        By using commas as a delimiter, you may set-up multiple filters (No Filter = All Tweets).
                        In addition, the "Explicit Words" filter, will block any Tweet that contains them regardless of the other filter.
                        ','buddystream_lang');

                    ?>
               </td>
           </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Filters (comma seperated)', 'buddystream_lang');?></th>
                <td>
                    <input type="text" name="tweetstream_filter"value="<?php echo get_site_option('tweetstream_filter');?>" size="50" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php echo __('Explicit words (comma seperated)', 'buddystream_lang');?></th>
                <td>
                    <input type="text" name="tweetstream_filterexplicit" value="<?php echo get_site_option('tweetstream_filterexplicit');?>" size="50" />
                </td>
            </tr>
        </table>
        <p class="submit"><input type="submit" class="button-primary" value="<?php echo __('Save Changes') ?>" /></p>
    </form>
</div>