<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php echo buddystreamTabLoader('flickr'); ?>

<div class="buddystream_info_box">
    <?php _e('flickr statitics description','buddystream_flickr'); ?>
</div>

<table class="buddystream_table" cellspacing="0">
        <tr class="header">
            <td><?php _e('Statistics', 'buddystream_flickr'); ?></td>
            <td></td>
        </tr>
    <?php
       $count_users          = count($wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users")));
       $count_flickr_users   = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='bs_flickr_username';")));
       $perc_flickr_users    = round(($count_flickr_users / $count_users) * 100);
       $count_photos         = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='flickr';")));
       $count_activity       = count($wpdb->get_results($wpdb->prepare("SELECT id FROM " . $bp->activity->table_name)));
       $perc_flickrupdates   = round(($count_photos / $count_activity * 100));
       $average_photos_day   = round($count_photos / 24);
       $average_photos_week  = $average_photos_day * 7;
       $average_photos_month = $average_photos_day * 30;
       $average_photos_year  = $average_photos_day * 365;

       echo "
        <tr>
           <td>".__('Amount of users:','buddystream_flickr')."</td>
            <td scope='row' class='column'>" . $count_users . "</td>
        </tr>
        <tr class='odd'>
           <td>".__('Amount of user using Flickr integration:','buddystream_flickr')."</td>
            <td scope='row' class='column'>" . $count_flickr_users . "</td>
        </tr>
        <tr>
           <td>".__('Percentage of users using Flickr integration:','buddystream_flickr')."</td>
            <td scope='row' class='column'>" . $perc_flickr_users . "%</td>
        </tr>
        <tr class='odd'>
           <td>".__('Amount of activity updates:','buddystream_flickr')."</td>
            <td scope='row' class='column'>" . $count_activity . "</td>
        </tr>
        <tr>
           <td>".__('Amount of Flickr photos:','buddystream_flickr')."</td>
            <td scope='row' class='column'>" . $count_photos . "</td>
        </tr>
        <tr class='odd'>
           <td>".__('Percentage of Flickr photos:','buddystream_flickr')."</td>
            <td scope='row' class='column'>" . $perc_flickrupdates . "%</td>
        </tr>
        <tr>
           <td>".__('Average number of Flickr photos imported per day:','buddystream_flickr')."</td>
            <td scope='row' class='column'>" . $average_photos_day . "</td>
        </tr>
        <tr class='odd'>
           <td>".__('Average number of Flickr photos imported per week:','buddystream_flickr')."</td>
            <td scope='row' class='column'>" . $average_photos_week . "</td>
        </tr>
        <tr>
           <td>".__('Average number of Flickr photos imported per month:','buddystream_flickr')."</td>
            <td scope='row' class='column'>" . $average_photos_month . "</td>
        </tr>
        <tr class='odd'>
           <td>-".__('Average number of Flickr photos imported per year:','buddystream_flickr')."</td>
            <td scope='row' class='column'>" . $average_photos_year . "</td>
        </tr>
        ";
    ?>
</table>