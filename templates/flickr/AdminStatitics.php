<link rel="stylesheet" href="<?= WP_PLUGIN_URL . '/buddystream/css/buddystream.css';?>" type="text/css" /><br/>
<?php include "AdminMenu.php"; ?>


<div class="wrap"><br />
<h2 style="float: left; line-height: 5px; padding-left: 5px;"><?php echo __('Statistics', 'buddystream_lang'); ?></h2>
<br /><br /><br />

<?php echo __('BuddyStream Flickr Statistics:', 'buddystream_lang'); ?><br/><br/>

<table class="widefat fixed" cellspacing="0">
    <thead>
        <tr class="thead">
            <th scope="col" id="cb" class="manage-column column name-column" style=""><?php echo __('Statistics', 'buddystream_lang'); ?><br/><br/></th>
            <th scope="col" id="cb" class="manage-column column name-column" style=""></th>
        </tr>
    </thead>
    <tfoot>
        <tr class="thead">
            <th scope="col" id="cb" class="manage-column column name-column" style=""><?php echo __('Statistics', 'buddystream_lang'); ?></th>
            <th scope="col" id="cb" class="manage-column column name-column" style=""></th>
        </tr>
    </tfoot>
    <tbody id="users" class="list:user user-list">
    <?php
       $count_users = count($wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users")));
       $count_flickr_users = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='fs_flickr_username';")));
       $perc_flickr_users = round(($count_flickr_users / $count_users) * 100);
       $count_photos = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='flickr';")));
       $count_activity = count($wpdb->get_results($wpdb->prepare("SELECT id FROM " . $bp->activity->table_name)));
       $perc_flickrupdates = round(($count_photos / $count_activity * 100));
       $average_photos_day = round($count_photos / 24);
       $average_photos_week = $average_photos_day * 7;
       $average_photos_month = $average_photos_day * 30;
       $average_photos_year = $average_photos_day * 365;

       echo "
        <tr id='stats'>
            <th scope='row' class='column'>Amount of users:</th>
            <td scope='row' class='column'>" . $count_users . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of user using Flickr integration:</th>
            <td scope='row' class='column'>" . $count_flickr_users . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Percentage of users using Flickr integration:</th>
            <td scope='row' class='column'>" . $perc_flickr_users . "%</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of activity updates:</th>
            <td scope='row' class='column'>" . $count_activity . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of Flickr photos:</th>
            <td scope='row' class='column'>" . $count_photos . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Percentage of Flickr photos:</th>
            <td scope='row' class='column'>" . $perc_flickrupdates . "%</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of Flickr photos imported per day:</th>
            <td scope='row' class='column'>" . $average_photos_day . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of Flickr photos imported per week:</th>
            <td scope='row' class='column'>" . $average_photos_week . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of Flickr photos imported per month:</th>
            <td scope='row' class='column'>" . $average_photos_month . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of Flickr photos imported per year:</th>
            <td scope='row' class='column'>" . $average_photos_year . "</th>
        </tr>
        ";
    ?>
   </tbody>

  </table>
</div>

