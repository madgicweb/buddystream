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

global $bp, $wpdb; $component = "youtube";

?>


        <div class="buddystream_info_box">
            <?php _e('youtube statitics description', 'buddystream_youtube'); ?>
        </div>

<table class="buddystream_table" cellspacing="0">

    <tr class="header">
        <td colspan="2"><?php _e('Statistics', 'buddystream_youtube'); ?></td>
    </tr>

    <?php
    $count_users = count($wpdb->get_results("SELECT * FROM $wpdb->users"));
    $count_youtube_users = count($wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='bs_youtube_username';"));
    $perc_youtube_users = round(($count_youtube_users / $count_users) * 100);
    $count_history = count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='youtube';"));
    $count_activity = count($wpdb->get_results("SELECT id FROM " . $bp->activity->table_name));
    $perc_youtubeupdates = round(($count_history / $count_activity * 100));
    $average_history_day = round($count_history / 24);
    $average_history_week = $average_history_day * 7;
    $average_history_month = $average_history_day * 30;
    $average_history_year = $average_history_day * 365;

    echo "
        <tr>
            <td>" . __('Amount of users:', 'buddystream_youtube') . "</td>
            <td scope='row' class='column'>" . $count_users . "</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Amount of user using YouTube:', 'buddystream_youtube') . "</td>
            <td scope='row' class='column'>" . $count_youtube_users . "</td>
        </tr>
        <tr>
            <td>" . __('Percentage of users using YouTube:', 'buddystream_youtube') . "</td>
            <td scope='row' class='column'>" . $perc_youtube_users . "%</td>
        </tr>
       <tr class='odd'>
            <td>" . __('Amount of activity updates:', 'buddystream_youtube') . "</td>
            <td scope='row' class='column'>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>" . __('Amount of YouTube videos:', 'buddystream_youtube') . "</td>
            <td scope='row' class='column'>" . $count_history . "</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Percentage of YouTube videos:', 'buddystream_youtube') . "</td>
            <td scope='row' class='column'>" . $perc_youtubeupdates . "%</td>
        </tr>
        <tr>
            <td>" . __('Average number of YouTube videos imported per day:', 'buddystream_youtube') . "</td>
            <td scope='row' class='column'>" . $average_history_day . "</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Average number of YouTube videos imported per week:', 'buddystream_youtube') . "</td>
            <td scope='row' class='column'>" . $average_history_week . "</td>
        </tr>
        <tr>
            <td>" . __('Average number of YouTube videos imported per month:', 'buddystream_youtube') . "</td>
            <td scope='row' class='column'>" . $average_history_month . "</td>
        </tr>
        <tr class='odd'>
            <td>" . __('Average number of YouTube videos imported per year:', 'buddystream_youtube') . "</td>
            <td scope='row' class='column'>" . $average_history_year . "</td>
        </tr>
        ";
    ?>
    </tbody>

</table>
</div>

