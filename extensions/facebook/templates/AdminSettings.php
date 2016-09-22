<link href="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/css/bootstrap.css" rel="stylesheet">
<script src="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/js/bootstrap.js"></script>
<link href="<?php echo BP_BUDDYSTREAM_URL;?>bootstrap/css/buddystream.css" rel="stylesheet">

<script src="<?php echo BP_BUDDYSTREAM_URL;?>extensions/default/jquery.slickswitch.js"></script>
<link href="<?php echo BP_BUDDYSTREAM_URL;?>extensions/default/slickswitch.css" rel="stylesheet">

<br><br>
<div id="buddystream" class="container">


    <div class="span9">

        <?php
            $buddyStreamExtensions = new BuddyStreamExtensions();
            echo $buddyStreamExtensions->tabLoader('facebook');
        ?>

        <?php

        $arraySwitches = array(
            'buddystream_facebook_privacy_setting'
        );

        if ($_POST) {

            update_site_option('facestream_application_id', trim(strip_tags($_POST['facestream_application_id'])));
            update_site_option('facestream_application_secret', trim(strip_tags($_POST['facestream_application_secret'])));
            update_site_option('buddystream_facebook_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_facebook_user_settings_maximport']))));

            if ($_POST['facestream_application_id']) {
                update_site_option('buddystream_facebook_setup', true);
            }

            foreach ($arraySwitches as $switch) {
                update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));
            }

            $message = __('Settings saved.', 'buddystream_facebook');
        }
        ?>

        <blockquote>
            <p><?php _e('facebook settings description', 'buddystream_facebook'); ?></p>
        </blockquote>

        <form method="post" action="">

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="table table-striped" cellspacing="0">
                <thead>
                <tr>
                    <th><?php _e('Facebook API', 'buddystream_facebook');?></th>
                    <th></th>
                </tr>
                </thead>

                <tr <? if (get_site_option('facestream_application_id') == "") {
                    echo 'class="buddystream_error_box"';
                } ?>>
                    <td width="600"><?php _e('Application ID:', 'buddystream_facebook');?></td>
                    <td>
                        <input type="text" name="facestream_application_id"
                               value="<?php echo get_site_option('facestream_application_id'); ?>" size="50"/>
                    </td>
                </tr>

                <tr <? if (get_site_option('facestream_application_secret') == "") {
                    echo 'class="buddystream_error_box"';
                } ?>>
                    <td width="600"><?php _e('Application secret:', 'buddystream_facebook');?></td>
                    <td>
                        <input type="text" name="facestream_application_secret"
                               value="<?php echo get_site_option('facestream_application_secret'); ?>" size="50"/>
                    </td>
                </tr>
            </table>

            <? if (get_site_option('facestream_application_secret') != "" && get_site_option('facestream_application_id') != "") { ?>
                <table class="table table-striped" cellspacing="0">
                    <thead>
                    <tr>
                        <th><?php _e('User options', 'buddystream_facebook');?></th>
                        <th></th>
                    </tr>
                    </thead>

                    <tr>
                        <td width="600"><?php _e('Maximum amount of Facebook items (total) to be imported per user, per day (empty = unlimited):', 'buddystream_facebook'); ?></td>
                        <td>
                            <input type="text" name="buddystream_facebook_user_settings_maximport"
                                   value="<?php echo get_site_option('buddystream_facebook_user_settings_maximport'); ?>"
                                   size="5"/>
                        </td>
                    </tr>
                </table>


                <tr>
                    <td width="600"><?php _e('Import also items that are rated "friends only" by default', 'buddystream_facebook');?></td>
                    <td><input class="switch icons" type="checkbox" name="buddystream_facebook_privacy_setting"
                               id="buddystream_facebook_privacy_setting"/></td>
                </tr>
                </table>

            <? } ?>


            <p class="submit"><input type="submit" class="btn btn-inverse"
                                     value="<?php _e('Save Changes', 'buddystream_facebook') ?>"/></p>
        </form>
    </div>
</div>


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
