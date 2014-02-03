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
        echo $buddyStreamExtensions->tabLoader('instagram');
        ?>
        <?php

        global $bp, $wpdb;

        ?>
        <blockquote>
            <p>
                <?php _e('instagram statitics description', 'buddystream');?>
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
            $count_instagramusers = count($wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='buddystream_instagram_token';"));
            $perc_instagramusers = round(($count_instagramusers / $count_users) * 100);
            $count_items = count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='instagram';"));
            $count_activity = count($wpdb->get_results("SELECT id FROM " . $bp->activity->table_name));
            $perc_itemtupdates = round(($count_items / $count_activity * 100));
            $average_items_day = round($count_items / 24);
            $average_items_week = $average_items_day * 7;
            $average_items_month = $average_items_day * 30;
            $average_items_year = $average_items_day * 365;

            echo "
        <tr>
            <td>" . __('Amount of users:', 'buddystream') . "</td>
            <td>" . $count_users . "</td>
        </tr>
        <tr>
            <td>" . __('Amount of user Instagram intergration:', 'buddystream') . "</td>
            <td>" . $count_instagramusers . "</td>
        </tr>
        <tr>
            <td>" . __('Percentage of users using intergration:', 'buddystream') . "</td>
            <td>" . $perc_instagramusers . "%</td>
        </tr>
        <tr>
            <td>" . __('Amount of activity updates:', 'buddystream') . "</td>
            <td>" . $count_activity . "</td>
        </tr>
        <tr>
            <td>" . __('Amount of items updates:', 'buddystream') . "</td>
            <td>" . $count_items . "</td>
        </tr>
        <tr>
            <td>" . __('Percentage of items in activity updates:', 'buddystream') . "</td>
            <td>" . $perc_itemtupdates . "%</td>
        </tr>
        <tr>
            <td>" . __('Average items import per day:', 'buddystream') . "</td>
            <td>" . $average_items_day . "</td>
        </tr>
        <tr>
            <td>" . __('Average items import per week:', 'buddystream') . "</td>
            <td>" . $average_items_week . "</td>
        </tr>
        <tr>
            <td>" . __('Average items import per month:', 'buddystream') . "</td>
            <td>" . $average_items_month . "</td>
        </tr>
        <tr>
            <td>" . __('Average items import per year:', 'buddystream') . "</td>
            <td>" . $average_items_year . "</td>
        </tr>
        ";
            ?>
            </tbody>

        </table>
    </div>
</div>

