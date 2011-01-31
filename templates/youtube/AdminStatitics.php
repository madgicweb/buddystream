<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/css/buddystream.css';?>" type="text/css" />
<br/>
<?php include "AdminMenu.php"; ?>


<div class="wrap"><br />
<h2 style="float: left; line-height: 5px; padding-left: 5px;"><?php echo __('Statistics', 'buddystream_lang'); ?></h2>
<br /><br /><br />

<?php echo __('BuddyStream YouTube Statistics:', 'buddystream_lang'); ?><br/><br/>

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
       $count_youtube_users = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='bs_youtube_username';")));
       $perc_youtube_users = round(($count_youtube_users / $count_users) * 100);
       $count_history = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='youtube';")));
       $count_activity = count($wpdb->get_results($wpdb->prepare("SELECT id FROM " . $bp->activity->table_name)));
       $perc_youtubeupdates = round(($count_history / $count_activity * 100));
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
            <th scope='row' class='column'>Amount of user using YouTube:</th>
            <td scope='row' class='column'>" . $count_youtube_users . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Percentage of users using YouTube:</th>
            <td scope='row' class='column'>" . $perc_youtube_users . "%</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of activity updates:</th>
            <td scope='row' class='column'>" . $count_activity . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of YouTube videos:</th>
            <td scope='row' class='column'>" . $count_history . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Percentage of YouTube videos:</th>
            <td scope='row' class='column'>" . $perc_youtubeupdates . "%</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of YouTube videos imported per day:</th>
            <td scope='row' class='column'>" . $average_history_day . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of YouTube videos imported per week:</th>
            <td scope='row' class='column'>" . $average_history_week . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of YouTube videos imported per month:</th>
            <td scope='row' class='column'>" . $average_history_month . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of YouTube videos imported per year:</th>
            <td scope='row' class='column'>" . $average_history_year . "</th>
        </tr>
        ";
    ?>
   </tbody>

  </table>
</div>

