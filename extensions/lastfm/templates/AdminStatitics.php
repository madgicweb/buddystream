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

        <blockquote>
            <p><?php _e('lastfm statitics description', 'buddystream_lastfm');?></p>
        </blockquote>

        <?php

        global $bp, $wpdb; $component = "lastfm";



        ?>


        <table class="table table-striped" cellspacing="0">

            <thead>
            <tr>
                <th><?php _e('Statistics', 'buddystream_lastfm'); ?></th>
                <th></th>
            </tr>
            </thead>

            <?php
            $count_users = count($wpdb->get_results("SELECT * FROM $wpdb->users"));
            $count_lastfm_users = count($wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='bs_lastfm_username';"));
            $perc_lastfm_users = round(($count_lastfm_users / $count_users) * 100);
            $count_history = count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='lastfm';"));
            $count_activity = count($wpdb->get_results("SELECT id FROM " . $bp->activity->table_name));
            $perc_lastfmupdates = round(($count_history / $count_activity * 100));
            $average_history_day = round($count_history / 24);
            $average_history_week = $average_history_day * 7;
            $average_history_month = $average_history_day * 30;
            $average_history_year = $average_history_day * 365;

            echo "
        <tr>
            <td>" . __('Amount of users:', 'buddystream_lastfm') . "</td>
            <td>" . $count_users . "</td>
        </tr>
        <tr>
            <td>" . __('Amount of user using Last.fm integration:', 'buddystream_lastfm') . "</td>
            <td>" . $count_lastfm_users . "</td>
        </tr>
        <tr>
            <td>" . __('Percentage of users using Last.fm integration:', 'buddystream_lastfm') . "</td>
            <td>" . $perc_lastfm_users . "%</td>
        </tr>
        <tr>
            <td>" . __('Amount of activity updates:', 'buddystream_lastfm') . "</td>
            <td>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>" . __('Amount of Last.fm songs history:', 'buddystream_lastfm') . "</td>
            <td>" . $count_history . "</td>
        </tr>
        <tr>
            <td>" . __('Percentage of Last.fm songs history:', 'buddystream_lastfm') . "</td>
            <td>" . $perc_lastfmupdates . "%</td>
        </tr>
        <tr>
            <td>" . __('Average number of Last.fm history imported per day:', 'buddystream_lastfm') . "</td>
            <td>" . $average_history_day . "</td>
        </tr>
        <tr>
            <td>" . __('Average number of Last.fm history imported per week:', 'buddystream_lastfm') . "</td>
            <td>" . $average_history_week . "</td>
        </tr>
        <tr>
            <td>" . __('Average number of Last.fm history imported per month:', 'buddystream_lastfm') . "</td>
            <td>" . $average_history_month . "</td>
        </tr>
        <tr>
            <td>" . __('Average number of Last.fm history imported per year:', 'buddystream_lastfm') . "</td>
            <td>" . $average_history_year . "</td>
        </tr>
        ";
            ?>

        </table>
    </div>
</div>