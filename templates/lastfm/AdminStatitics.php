<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/css/buddystream.css';?>" type="text/css" />
<br/>
<?php include "AdminMenu.php"; ?>


<div class="wrap"><br />
<h2 style="float: left; line-height: 5px; padding-left: 5px;"><?php echo __('Statistics', 'buddystream_lang'); ?></h2>
<br /><br /><br />

<?php echo __('BuddyStream Last.fm Statistics:', 'buddystream_lang'); ?><br/><br/>

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
       $count_lastfm_users = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='fs_lastfm_username';")));
       $perc_lastfm_users = round(($count_lastfm_users / $count_users) * 100);
       $count_history = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='lastfm';")));
       $count_activity = count($wpdb->get_results($wpdb->prepare("SELECT id FROM " . $bp->activity->table_name)));
       $perc_lastfmupdates = round(($count_history / $count_activity * 100));
       $average_history_day = round($count_history / 24);
       $average_history_week = $average_history_day * 7;
       $average_history_month = $average_history_day * 30;
       $average_history_year = $average_history_day * 365;

       echo "
        <tr id='stats'>
            <th scope='row' class='column'>Amount of users:</th>
            <td scope='row' class='column'>" . $count_users . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of user using Last.fm integration:</th>
            <td scope='row' class='column'>" . $count_lastfm_users . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Percentage of users using Last.fm integration:</th>
            <td scope='row' class='column'>" . $perc_lastfm_users . "%</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of activity updates:</th>
            <td scope='row' class='column'>" . $count_activity . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of Last.fm songs history:</th>
            <td scope='row' class='column'>" . $count_history . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Percentage of Last.fm songs history:</th>
            <td scope='row' class='column'>" . $perc_lastfmupdates . "%</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of Last.fm history imported per day:</th>
            <td scope='row' class='column'>" . $average_history_day . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of Last.fm history imported per week:</th>
            <td scope='row' class='column'>" . $average_history_week . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of Last.fm history imported per month:</th>
            <td scope='row' class='column'>" . $average_history_month . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of Last.fm history imported per year:</th>
            <td scope='row' class='column'>" . $average_history_year . "</th>
        </tr>
        ";
    ?>
   </tbody>

  </table>
</div>

