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
        echo $buddyStreamExtensions->tabLoader('lastfm');
        ?>
        <?php


        if ($_POST) {
            update_site_option('buddystream_lastfm_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_lastfm_user_settings_maximport']))));
            update_site_option('buddystream_lastfm_setup', true);

            foreach ($arraySwitches as $switch) {
                update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));
            }

            $message = __('Settings saved.', 'buddystream_lastfm');
        }
        ?>

        <blockquote>
            <p><?php _e('lastfm settings description', 'buddystream_lastfm'); ?></p>
        </blockquote>

        <form method="post" action="">

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="table table-striped" cellspacing="0">
                <thead>
                <tr>
                    <th><?php _e('User options', 'buddystream_lastfm');?></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>

                <tr>
                    <td><?php _e('Maximum amount of songs to import per user, per day (empty = unlimited):', 'buddystream_lastfm'); ?></td>
                    <td><input type="text" name="buddystream_lastfm_user_settings_maximport"
                               value="<?php echo get_site_option('buddystream_lastfm_user_settings_maximport'); ?>"
                               size="5"/></td>
                </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" class="btn btn-inverse"
                                     value="<?php _e('Save Changes', 'buddystream_facebook') ?>"/></p>
        </form>
    </div>
</div>