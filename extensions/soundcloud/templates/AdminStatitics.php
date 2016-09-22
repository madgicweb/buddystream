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
        global $bp, $wpdb; $component = "soundcloud";

         ?>

        <blockquote>
            <p>
                <?php _e('soundcloud statitics description', 'buddystream_soundcloud');?>
            </p>
        </blockquote>

        <table class="table table-striped" cellspacing="0">

            <thead>
            <tr>
                <th><?php echo __('Statistics', 'buddystream_soundcloud'); ?></th>
                <th></th>
            </tr>
            </thead>

            <tbody>

            <?php
            $count_users = count($wpdb->get_results("SELECT * FROM $wpdb->users"));
            $count_soundcloudusers = count($wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='soundcloud_access_token';"));
            $perc_soundcloudusers = round(($count_soundcloudusers / $count_users) * 100);
            $count_tracks = count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='soundcloud';"));
            $count_activity = count($wpdb->get_results("SELECT id FROM " . $bp->activity->table_name));
            $perc_trackupdates = round(($count_tracks / $count_activity * 100));
            $average_tracks_day = round($count_tracks / 24);
            $average_tracks_week = $average_tracks_day * 7;
            $average_tracks_month = $average_tracks_day * 30;
            $average_tracks_year = $average_tracks_day * 365;

            echo "
                <tr>
                    <td>" . __('Amount of users:', 'buddystream_soundcloud') . "</td>
                    <td>" . $count_users . "</td>
                </tr>
                <tr>
                    <td>" . __('Amount of user Soundcloud intergration:', 'buddystream_soundcloud') . "</td>
                    <td>" . $count_soundcloudusers . "</td>
                </tr>
                <tr>
                    <td>" . __('Percentage of users Soundcloud using intergration:', 'buddystream_soundcloud') . "</td>
                    <td>" . $perc_soundcloudusers . "%</td>
                </tr>
                <tr>
                    <td>" . __('Amount of activity updates:', 'buddystream_soundcloud') . "</td>
                    <td>" . $count_activity . "</td>
                </tr>
                <tr>
                    <td>" . __('Amount of tracks updates:', 'buddystream_soundcloud') . "</td>
                    <td>" . $count_tracks . "</td>
                </tr>
                <tr>
                    <td>" . __('Percentage of tracks in activity updates:', 'buddystream_soundcloud') . "</td>
                    <td>" . $perc_trackupdates . "%</td>
                </tr>
                <tr>
                    <td>" . __('Average tracks import per day:', 'buddystream_soundcloud') . "</td>
                    <td>" . $average_tracks_day . "</td>
                </tr>
                <tr>
                    <td>" . __('Average tracks import per week:', 'buddystream_soundcloud') . "</td>
                    <td>" . $average_tracks_week . "</td>
                </tr>
                <tr>
                    <td>" . __('Average tracks import per month:', 'buddystream_soundcloud') . "</td>
                    <td>" . $average_tracks_month . "</td>
                </tr>
                <tr>
                    <td>" . __('Average tracks import per year:', 'buddystream_soundcloud') . "</td>
                    <td>" . $average_tracks_year . "</td>
                </tr>
                ";
            ?>
            </tbody>
        </table>
    </div>
</div>