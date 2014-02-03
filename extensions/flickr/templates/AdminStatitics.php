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

        global $bp, $wpdb; $component = "flickr";

        ?>

        <table class="table table-striped" cellspacing="0">

            <thead>
            <tr>
                <th><?php _e('Statistics', 'buddystream_flickr'); ?></th>
                <th></th>
            </tr>
            </thead>
            <?php
            $count_users = count($wpdb->get_results("SELECT * FROM $wpdb->users"));
            $count_flickr_users = count($wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='bs_flickr_username';"));
            $perc_flickr_users = round(($count_flickr_users / $count_users) * 100);
            $count_photos = count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='flickr';"));
            $count_activity = count($wpdb->get_results("SELECT id FROM " . $bp->activity->table_name));
            $perc_flickrupdates = round(($count_photos / $count_activity * 100));
            $average_photos_day = round($count_photos / 24);
            $average_photos_week = $average_photos_day * 7;
            $average_photos_month = $average_photos_day * 30;
            $average_photos_year = $average_photos_day * 365;

            echo "
        <tr>
           <td>" . __('Amount of users:', 'buddystream_flickr') . "</td>
            <td scope='row' class='column'>" . $count_users . "</td>
        </tr>
        <tr class='odd'>
           <td>" . __('Amount of user using Flickr integration:', 'buddystream_flickr') . "</td>
            <td scope='row' class='column'>" . $count_flickr_users . "</td>
        </tr>
        <tr>
           <td>" . __('Percentage of users using Flickr integration:', 'buddystream_flickr') . "</td>
            <td scope='row' class='column'>" . $perc_flickr_users . "%</td>
        </tr>
        <tr class='odd'>
           <td>" . __('Amount of activity updates:', 'buddystream_flickr') . "</td>
            <td scope='row' class='column'>" . $count_activity . "</td>
        </tr>
        <tr>
           <td>" . __('Amount of Flickr photos:', 'buddystream_flickr') . "</td>
            <td scope='row' class='column'>" . $count_photos . "</td>
        </tr>
        <tr class='odd'>
           <td>" . __('Percentage of Flickr photos:', 'buddystream_flickr') . "</td>
            <td scope='row' class='column'>" . $perc_flickrupdates . "%</td>
        </tr>
        <tr>
           <td>" . __('Average number of Flickr photos imported per day:', 'buddystream_flickr') . "</td>
            <td scope='row' class='column'>" . $average_photos_day . "</td>
        </tr>
        <tr class='odd'>
           <td>" . __('Average number of Flickr photos imported per week:', 'buddystream_flickr') . "</td>
            <td scope='row' class='column'>" . $average_photos_week . "</td>
        </tr>
        <tr>
           <td>" . __('Average number of Flickr photos imported per month:', 'buddystream_flickr') . "</td>
            <td scope='row' class='column'>" . $average_photos_month . "</td>
        </tr>
        <tr class='odd'>
           <td>-" . __('Average number of Flickr photos imported per year:', 'buddystream_flickr') . "</td>
            <td scope='row' class='column'>" . $average_photos_year . "</td>
        </tr>
        ";
            ?>
        </table>