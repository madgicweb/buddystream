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

        global $bp, $wpdb; $component = "facebook";

        ?>


        <table class="table table-striped" cellspacing="0">

            <thead>
            <th><?php _e('Statistics', 'buddystream_facebook'); ?></th>
            <th></th>
            </thead>

            <?php
            $count_users = count($wpdb->get_results("SELECT * FROM $wpdb->users"));
            $count_facestreamusers = count($wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='facestream_session_key';"));
            $perc_facestreamusers = round(($count_facestreamusers / $count_users) * 100);
            $count_facebook = count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='facebook';"));
            $count_activity = count($wpdb->get_results("SELECT id FROM " . $bp->activity->table_name));
            $perc_facebookupdates = round(($count_facebook / $count_activity * 100));
            $average_facebook_day = round($count_facebook / 24);
            $average_facebook_week = $average_facebook_day * 7;
            $average_facebook_month = $average_facebook_day * 30;
            $average_facebook_year = $average_facebook_day * 365;

            echo "
        <tr>
            <td>" . __('Amount of users:', 'buddystream_facebook') . "</td>
            <td>" . $count_users . "</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Amount of user using Facebook integration:', 'buddystream_facebook') . "</td>
            <td>" . $count_facestreamusers . "</td>
        </tr>
        <tr>
            <td>" . __('Percentage of users using Facebook integration:', 'buddystream_facebook') . "</td>
            <td>" . $perc_facestreamusers . "%</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Amount of activity updates:', 'buddystream_facebook') . "</td>
            <td>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>" . __('Amount of Facebook items:', 'buddystream_facebook') . "</td>
            <td>" . $count_facebook . "</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Percentage of Facebook items:', 'buddystream_facebook') . "</td>
            <td>" . $perc_facebookupdates . "%</td>
        </tr>
        <tr>
            <td>" . __('Average number of Facebook items imported per day:', 'buddystream_facebook') . "</td>
            <td>" . $average_facebook_day . "</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Average number of Facebook items imported per week:', 'buddystream_facebook') . "</td>
            <td>" . $average_facebook_week . "</td>
        </tr>
        <tr>
            <td>" . __('Average number Facebook items imported per month:', 'buddystream_facebook') . "</td>
            <td>" . $average_facebook_month . "</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Average number Facebook items imported per year', 'buddystream_facebook') . "</td>
            <td>" . $average_facebook_year . "</td>
        </tr>
        ";
            ?>
            </tbody>
        </table>