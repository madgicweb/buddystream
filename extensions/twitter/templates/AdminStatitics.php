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
        echo $buddyStreamExtensions->tabLoader('twitter');
        ?>
        <?php

        global $bp, $wpdb; $component = "twitter";


        ?>
        <blockquote>
            <p>
                <?php _e('twitter statitics description', 'buddystream');?>
            </p>
        </blockquote>

        <table class="table table-striped" cellspacing="0">
            <thead>
            <tr>
                <th><?php echo __('Statistics', 'buddystream'); ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>


            <?php
            $count_users = count($wpdb->get_results("SELECT * FROM $wpdb->users"));
            $count_tweetstreamusers = count($wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='tweetstream_token';"));
            $perc_tweetstreamusers = round(($count_tweetstreamusers / $count_users) * 100);
            $count_tweets = count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='twitter';"));
            $count_activity = count($wpdb->get_results("SELECT id FROM " . $bp->activity->table_name));
            $perc_tweetupdates = round(($count_tweets / $count_activity * 100));
            $average_tweets_day = round($count_tweets / 24);
            $average_tweets_week = $average_tweets_day * 7;
            $average_tweets_month = $average_tweets_day * 30;
            $average_tweets_year = $average_tweets_day * 365;

            echo "
        <tr>
            <td>" . __('Amount of users:', 'buddystream') . "</td>
            <td>" . $count_users . "</td>
        </tr>
        <tr>
            <td>" . __('Amount of user Twitter intergration:', 'buddystream') . "</td>
            <td>" . $count_tweetstreamusers . "</td>
        </tr>
        <tr>
            <td>" . __('Percentage of users using intergration:', 'buddystream') . "</td>
            <td>" . $perc_tweetstreamusers . "%</td>
        </tr>
        <tr>
            <td>" . __('Amount of activity updates:', 'buddystream') . "</td>
            <td>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>" . __('Amount of tweets updates:', 'buddystream') . "</td>
            <td>" . $count_tweets . "</td>
        </tr>
        <tr>
            <td>" . __('Percentage of tweets in activity updates:', 'buddystream') . "</td>
            <td>" . $perc_tweetupdates . "%</td>
        </tr>
        <tr>
            <td>" . __('Average tweets import per day:', 'buddystream') . "</td>
            <td>" . $average_tweets_day . "</td>
        </tr>
        <tr>
            <td>" . __('Average tweets import per week:', 'buddystream') . "</td>
            <td>" . $average_tweets_week . "</td>
        </tr>
        <tr>
            <td>" . __('Average tweets import per month:', 'buddystream') . "</td>
            <td>" . $average_tweets_month . "</td>
        </tr>
        <tr>
            <td>" . __('Average tweets import per year:', 'buddystream') . "</td>
            <td>" . $average_tweets_year . "</td>
        </tr>
        ";
            ?>
            </tbody>

        </table>
    </div>
</div>

