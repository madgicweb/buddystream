<link href="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/css/bootstrap.css" rel="stylesheet"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<script src="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/js/bootstrap.js"></script>
<link href="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/css/buddystream.css" rel="stylesheet">

<script src="<?php echo BP_BUDDYSTREAM_URL;?>extensions/default/jquery.slickswitch.js"></script>
<link href="<?php echo BP_BUDDYSTREAM_URL;?>extensions/default/slickswitch.css" rel="stylesheet">

<br><br>
<div id="buddystream" class="container">
    <div class="span9">

        <?php
            $buddyStreamExtensions = new BuddyStreamExtensions();
            echo $buddyStreamExtensions->tabLoader('youtube');

            $arraySwitches = array(
                'buddystream_youtube_album'
            );

            if ($_POST) {
                update_site_option('buddystream_youtube_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_youtube_user_settings_maximport']))));
                update_site_option('buddystream_youtube_setup', true);

                foreach ($arraySwitches as $switch) {
                    update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));
                }

                $message = __('Settings saved.', 'buddystream_youtube');
            }
        ?>


        <blockquote>
            <p><?php
                _e('youtube settings description', 'buddystream_youtube'); ?></p>
        </blockquote>

        <?php if ($message): ?>
            <div class="alert alert-<?php if (isset($message_type)) {
                echo $message_type;
            } else {
                echo "success";
            } ?>"><?php echo $message; ?></div>
        <?php endif; ?>



        <form method="post" action="">
            <table class="table table-striped" cellpadding="0" cellspacing="0">

                <thead>
                    <tr>
                        <th><?php _e('User options', 'buddystream_youtube');?></th>
                        <th></th>
                    </tr>
                </thead>

                <tr>
                    <td width="600"><?php _e('Show YouTube album on user profile page?', 'buddystream_youtube');?></td>
                    <td><input class="switch icons" type="checkbox" name="buddystream_youtube_album"
                               id="buddystream_youtube_album"/></td>
                </tr>

                <tr>
                    <td width="600"><?php _e('Maximum number of videos to import per user, per day (empty - unlimited):', 'buddystream_youtube'); ?></td>
                    <td><input type="text" name="buddystream_youtube_user_settings_maximport"
                               value="<?php echo get_site_option('buddystream_youtube_user_settings_maximport'); ?>" size="5"/>
                    </td>
                </tr>

            </table>
            <p class="submit"><input type="submit" class="btn btn-inverse"
                                     value="<?php _e('Save Changes', 'buddystream_youtube') ?>"/></p>
        </form>

        <script type="text/javascript">
            jQuery(".switch").slickswitch();
        </script>

<?php
    foreach ($arraySwitches as $switch) {
        if (get_site_option($switch)) {
            echo'
            <script>
                jQuery("#' . $switch . '").slickswitch("toggleOn");
            </script>
            ';
        } else {
            echo'
            <script>
                jQuery("#' . $switch . '").slickswitch("toggleOff");
            </script>
            ';
        }
    }
?>