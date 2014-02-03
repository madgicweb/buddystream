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
        echo $buddyStreamExtensions->tabLoader('flickr');
        ?>

        <?php

        $arraySwitches = array(
            'buddystream_flickr_album'
        );

        if ($_POST) {
            update_site_option('bs_flickr_api_key', trim(strip_tags($_POST['bs_flickr_api_key'])));
            update_site_option('buddystream_flickr_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_flickr_user_settings_maximport']))));

            if ($_POST['bs_flickr_api_key']) {
                update_site_option('buddystream_flickr_setup', true);
            }

            foreach ($arraySwitches as $switch) {
                update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));
            }

            $message = __('Settings saved.', 'buddystream_flickr');
        }
        ?>
        <blockquote>
            <p>
                <?php _e('flickr settings description', 'buddystream_flickr'); ?>
                <a href="http://www.flickr.com/services/api/misc.api_keys.html" target="_new">http://www.flickr.com/services/api/misc.api_keys.html</a>

            </p>
        </blockquote>

        <form method="post" action="">

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="table table-striped" cellspacing="0">

                <thead>
                <th><?php _e('Flickr API', 'buddystream_flickr');?></th>
                <th></th>
                </thead>

                <tbody>
                <tr>
                    <td><?php _e('API key:', 'buddystream_flickr');?></td>
                    <td><input type="text" name="bs_flickr_api_key"
                               value="<?php echo get_site_option('bs_flickr_api_key'); ?>" size="50"/></td>
                </tr>
                </tbody>
            </table>


            <table class="table table-striped" cellspacing="0">

                <thead>
                <tr>
                    <th><?php _e('User options', 'buddystream_flickr');?></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>

                <tr>
                    <td><?php _e('Show  Flickr album on user profile page?', 'buddystream_flickr');?></td>
                    <td><input class="switch icons" type="checkbox" name="buddystream_flickr_album"
                               id="buddystream_flickr_album"/></td>
                </tr>

                <tr>
                    <td><?php _e('Maximum number of photos imported per user, per day (empty = unlimited):', 'buddystream_flickr'); ?></td>
                    <td><input type="text" name="buddystream_flickr_user_settings_maximport"
                               value="<?php echo get_site_option('buddystream_flickr_user_settings_maximport'); ?>"
                               size="5"/></td>
                </tr>
                </tbody>
            </table>

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