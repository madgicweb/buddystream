<br/>
<?php include "AdminMenu.php"; ?>


<div class="wrap"><br />
<h2 style="float: left; line-height: 5px; padding-left: 5px;"><?php echo __('Statistics', 'buddystream_lang'); ?></h2>
<br /><br /><br />

<?php echo __('BuddyStream Facebook Statistics:', 'buddystream_lang'); ?><br/><br/>

<table class="widefat fixed" cellspacing="0">
    <thead>
        <tr class="thead">
            <th scope="col" id="cb" class="manage-column column name-column" style=""><?php echo __('statistics', 'buddystream_lang'); ?><br/><br/></th>
            <th scope="col" id="cb" class="manage-column column name-column" style=""></th>
        </tr>
    </thead>
    <tfoot>
        <tr class="thead">
            <th scope="col" id="cb" class="manage-column column name-column" style=""><?php echo __('statistics', 'buddystream_lang'); ?></th>
            <th scope="col" id="cb" class="manage-column column name-column" style=""></th>
        </tr>
    </tfoot>
    <tbody id="users" class="list:user user-list">
    <?php
       $count_users = count($wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users")));
       $count_facestreamusers = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='facestream_session_key")));
       $perc_facestreamusers = round(($count_facestreamusers / $count_users) * 100);
       $count_facebook = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='facebook';")));
       $count_activity = count($wpdb->get_results($wpdb->prepare("SELECT id FROM " . $bp->activity->table_name)));
       $perc_facebookupdates = round(($count_facebook / $count_activity * 100));
       $average_facebook_day = round($count_facebook / 24);
       $average_facebook_week = $average_facebook_day * 7;
       $average_facebook_month = $average_facebook_day * 30;
       $average_facebook_year = $average_facebook_day * 365;

       echo "
        <tr id='stats'>
            <th scope='row' class='column'>Amount of users:</th>
            <td scope='row' class='column'>" . $count_users . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of user using Facebook integration:</th>
            <td scope='row' class='column'>" . $count_facestreamusers . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Percentage of users using Facebook integration:</th>
            <td scope='row' class='column'>" . $perc_facestreamusers . "%</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of activity updates:</th>
            <td scope='row' class='column'>" . $count_activity . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of Facebook items:</th>
            <td scope='row' class='column'>" . $count_facebook . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Percentage of Facebook items:</th>
            <td scope='row' class='column'>" . $perc_facebookupdates . "%</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of Facebook items imported per day:</th>
            <td scope='row' class='column'>" . $average_facebook_day . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number of Facebook items imported per week:</th>
            <td scope='row' class='column'>" . $average_facebook_week . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number Facebook items imported per month:</th>
            <td scope='row' class='column'>" . $average_facebook_month . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average number Facebook items imported per year:</th>
            <td scope='row' class='column'>" . $average_facebook_year . "</th>
        </tr>
        ";
    ?>
   </tbody>

  </table>
</div>

