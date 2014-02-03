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
            echo $buddyStreamExtensions->tabLoader('instagram');
        ?>

        <?php
        global $bp;

        if ($_POST) {
            update_site_option('buddystream_instagram_consumer_key', trim(@$_POST['buddystream_instagram_consumer_key']));
            update_site_option('buddystream_instagram_consumer_secret', trim(@$_POST['buddystream_instagram_consumer_secret']));
            update_site_option('buddystream_instagram_user_settings_maximport', trim(strip_tags(strtolower(@$_POST['buddystream_instagram_user_settings_maximport']))));

            update_site_option('buddystream_instagram_map_width', trim(strip_tags(strtolower(@$_POST['buddystream_instagram_map_width']))));
            update_site_option('buddystream_instagram_map_height', trim(strip_tags(strtolower(@$_POST['buddystream_instagram_map_height']))));
            update_site_option('buddystream_instagram_map_zoom', trim(strip_tags(strtolower(@$_POST['buddystream_instagram_map_zoom']))));

            if ($_POST['buddystream_instagram_consumer_key']) {
                update_site_option('buddystream_instagram_setup', true);
            }

            $message = __('Settings saved.', 'buddystream_instagram');
        }
        ?>

        <blockquote>
            <p>
                <?php echo str_replace("#ROOTDOMAIN", $bp->root_domain, __('instagram settings description', 'buddystream_instagram')); ?>
            </p>
        </blockquote>


        <form method="post" action="">

            <?php if (isset($message)): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="table table-striped" cellspacing="0">
                <thead>
                <tr>
                    <th><?php _e('Instagram API', 'buddystream_instagram');?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td width="600"><?php _e('Client key:', 'buddystream_instagram');?></td>
                    <td><input type="text" name="buddystream_instagram_consumer_key"
                               value="<?php echo get_site_option('buddystream_instagram_consumer_key'); ?>" size="50"/></td>
                </tr>

                <tr>
                    <td width="600"><?php _e('Client secret:', 'buddystream_instagram');?></td>
                    <td><input type="text" name="buddystream_instagram_consumer_secret"
                               value="<?php echo get_site_option('buddystream_instagram_consumer_secret'); ?>" size="50"/></td>
                </tr>
                </tbody>
            </table>

            <?php if (get_site_option('buddystream_instagram_consumer_key') && get_site_option('buddystream_instagram_consumer_secret')) { ?>

                <table class="table table-striped" cellspacing="0">
                    <thead>
                    <tr>
                        <th><?php _e('User options', 'buddystream_instagram');?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr valign="top">
                        <td width="600"><?php _e('Maximum Instagram images to be imported per user, per day (empty = unlimited tweets import):', 'buddystream_instagram'); ?></td>
                        <td><input type="text" name="buddystream_instagram_user_settings_maximport"
                                   value="<?php echo get_site_option('buddystream_instagram_user_settings_maximport'); ?>"
                                   size="5"/></td>
                    </tr>
                    </tbody>
                </table>

            <?php } ?>


            <p class="submit"><input type="submit" class="btn btn-inverse"
                                     value="<?php _e('Save Changes', 'buddystream_instagram') ?>"/></p>
        </form>

    </div>
</div>
