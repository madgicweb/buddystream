<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php echo buddystreamTabLoader('lastfm'); ?>

<div class="buddystream_info_box">
<?php _e('lastfm statitics description','buddystream_lastfm');?>
</div>

<table class="buddystream_table " cellspacing="0">

        <tr class="header">
            <td><?php _e('Statistics', 'buddystream_lastfm'); ?></td>
            <td></td>
        </tr>
   
    <?php
       $count_users             = count($wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users")));
       $count_lastfm_users      = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='bs_lastfm_username';")));
       $perc_lastfm_users       = round(($count_lastfm_users / $count_users) * 100);
       $count_history           = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='lastfm';")));
       $count_activity          = count($wpdb->get_results($wpdb->prepare("SELECT id FROM " . $bp->activity->table_name)));
       $perc_lastfmupdates      = round(($count_history / $count_activity * 100));
       $average_history_day     = round($count_history / 24);
       $average_history_week    = $average_history_day * 7;
       $average_history_month   = $average_history_day * 30;
       $average_history_year    = $average_history_day * 365;

       echo "
        <tr>
            <td>". __('Amount of users:', 'buddystream_lastfm')."</td>
            <td>" . $count_users . "</td>
        </tr>
        <tr class='odd'>
            <td>". __('Amount of user using Last.fm integration:', 'buddystream_lastfm')."</td>
            <td>" . $count_lastfm_users . "</td>
        </tr>
        <tr>
            <td>". __('Percentage of users using Last.fm integration:', 'buddystream_lastfm')."</td>
            <td>" . $perc_lastfm_users . "%</td>
        </tr>
        <tr class='odd'>
            <td>". __('Amount of activity updates:', 'buddystream_lastfm')."</td>
            <td>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>". __('Amount of Last.fm songs history:', 'buddystream_lastfm')."</td>
            <td>" . $count_history . "</td>
        </tr>
        <tr class='odd'>
            <td>". __('Percentage of Last.fm songs history:', 'buddystream_lastfm')."</td>
            <td>" . $perc_lastfmupdates . "%</td>
        </tr>
        <tr>
            <td>". __('Average number of Last.fm history imported per day:', 'buddystream_lastfm')."</td>
            <td>" . $average_history_day . "</td>
        </tr>
        <tr class='odd'>
            <td>". __('Average number of Last.fm history imported per week:', 'buddystream_lastfm')."</td>
            <td>" . $average_history_week . "</td>
        </tr>
        <tr>
            <td>". __('Average number of Last.fm history imported per month:', 'buddystream_lastfm')."</td>
            <td>" . $average_history_month . "</td>
        </tr>
        <tr class='odd'>
            <td>". __('Average number of Last.fm history imported per year:', 'buddystream_lastfm')."</td>
            <td>" . $average_history_year . "</td>
        </tr>
        ";
    ?>
   </tbody>

  </table>