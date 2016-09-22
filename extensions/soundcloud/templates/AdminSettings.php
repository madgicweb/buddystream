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
        echo $buddyStreamExtensions->tabLoader('soundcloud');
        ?>

        <?php

        if ($_POST) {
            update_site_option('soundcloud_client_id', trim(strip_tags($_POST['soundcloud_client_id'])));
            update_site_option('soundcloud_client_secret', trim(strip_tags($_POST['soundcloud_client_secret'])));
            update_site_option('buddystream_soundcloud_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_soundcloud_user_settings_maximport']))));

            if ($_POST['soundcloud_client_id']) {
                update_site_option('buddystream_soundcloud_setup', true);
            }

            foreach ($arraySwitches as $switch) {
                update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));
            }

            $message = __('Settings saved.', 'buddystream_soundcloud');
        }
        ?>

        <blockquote>
            <p>
                <?php global $bp; echo str_replace("#ROOTDOMAIN", $bp->root_domain . "/?buddystream_auth=soundcloud", __('soundcloud settings description', 'buddystream_soundcloud')); ?>
            </p>
        </blockquote>


        <form method="post" action="">

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="table table-striped" cellspacing="0">
                <thead>
                <tr>
                    <th><?php _e('Soundcloud API', 'buddystream_soundcloud');?></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>

                <tr <? if (get_site_option('soundcloud_client_id') == "") {
                    echo 'class="buddystream_error_box"';
                }?>>
                    <td width="600"><?php _e('Client ID:', 'buddystream_soundcloud');?></td>
                    <td><input type="text" name="soundcloud_client_id"
                               value="<?php echo get_site_option('soundcloud_client_id'); ?>" size="50"/></td>
                </tr>

                <tr <? if (get_site_option('soundcloud_client_secret') == "") {
                    echo 'class="buddystream_error_box"';
                }?> class="odd">
                    <td width="600"><?php _e('Client secret key:', 'buddystream_soundcloud');?></td>
                    <td><input type="text" name="soundcloud_client_secret"
                               value="<?php echo get_site_option('soundcloud_client_secret'); ?>" size="50"/></td>
                </tr>
                </tbody>
            </table>

            <? if (get_site_option('soundcloud_client_id') && get_site_option('soundcloud_client_secret')) { ?>

                <table class="table table-striped" cellspacing="0">

                    <thead>
                    <tr>
                        <th><?php _e('User options', 'buddystream_soundcloud');?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td width="600"><?php _e('Show  Soundcloud tracks (album) on user profile page?', 'buddystream_soundcloud');?></td>
                        <td><input class="switch icons" type="checkbox" name="buddystream_soundcloud_tracks"
                                   id="buddystream_soundcloud_tracks"/></td>
                    </tr>

                    <tr>
                        <td width="600"><?php _e('Maximum tracks to be imported per user, per day (empty = unlimited tracks import):', 'buddystream_soundcloud'); ?></td>
                        <td>
                            <input type="text" name="buddystream_soundcloud_user_settings_maximport"
                                   value="<?php echo get_site_option('buddystream_soundcloud_user_settings_maximport'); ?>"
                                   size="5"/>
                        </td>
                    </tr>
                    </tbody>
                </table>

            <? } ?>

            <p class="submit"><input type="submit" class="btn btn-inverse"
                                     value="<?php _e('Save Changes', 'buddystream_facebook') ?>"/></p>
        </form>
    </div>
</div>