<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/css/buddystream.css';?>" type="text/css" />
<br/>
<?php include "AdminMenu.php"; ?>


<div class="wrap"><br />
<h2 style="float: left; line-height: 5px; padding-left: 5px;"><?php echo __('Twitter Statistics', 'buddystream_lang'); ?></h2>
<br /><br /><br />

<?php echo __('BuddyStream Twitter Statistics:', 'buddystream_lang'); ?><br/><br/>

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
       $count_tweetstreamusers = count($wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='tweetstream_token';")));
       $perc_tweetstreamusers = round(($count_tweetstreamusers / $count_users) * 100);
       $count_tweets = count($wpdb->get_results($wpdb->prepare("SELECT type FROM " . $bp->activity->table_name . " WHERE type='tweet';")));
       $count_activity = count($wpdb->get_results($wpdb->prepare("SELECT id FROM " . $bp->activity->table_name)));
       $perc_tweetupdates = round(($count_tweets / $count_activity * 100));
       $average_tweets_day = round($count_tweets / 24);
       $average_tweets_week = $average_tweets_day * 7;
       $average_tweets_month = $average_tweets_day * 30;
       $average_tweets_year = $average_tweets_day * 365;

       echo "
        <tr id='stats'>
            <th scope='row' class='column'>Amount of users:</th>
            <td scope='row' class='column'>" . $count_users . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of user Twitter intergration:</th>
            <td scope='row' class='column'>" . $count_tweetstreamusers . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Percentage of users Twitter using intergration:</th>
            <td scope='row' class='column'>" . $perc_tweetstreamusers . "%</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of activity updates:</th>
            <td scope='row' class='column'>" . $count_activity . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Amount of tweets updates:</th>
            <td scope='row' class='column'>" . $count_tweets . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Percentage of tweets in activity updates:</th>
            <td scope='row' class='column'>" . $perc_tweetupdates . "%</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average tweets import per day:</th>
            <td scope='row' class='column'>" . $average_tweets_day . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average tweets import per week:</th>
            <td scope='row' class='column'>" . $average_tweets_week . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average tweets import per month:</th>
            <td scope='row' class='column'>" . $average_tweets_month . "</th>
        </tr>
        <tr id='stats'>
            <th scope='row' class='column'>Average tweets import per year:</th>
            <td scope='row' class='column'>" . $average_tweets_year . "</th>
        </tr>
        ";
    ?>
   </tbody>

  </table>
</div>

