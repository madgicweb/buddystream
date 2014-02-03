&nbsp;
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
            echo $buddyStreamExtensions->tabLoader($buddystream_extension);
        ?>

        <?php
        if (isset($_GET['user_id'])) {
            if (isset($_GET['action']) && $_GET['action'] == "reset") {
                if (isset($_GET['confirmed']) && $_GET['confirmed'] == "1") {

                    call_user_func("buddystream" . ucfirst($buddystream_extension) . "ResetUser", $_GET['user_id']);

                    $message = __('User integration has been reset. Note: User will have to reconnect their integration if desired.', 'buddystream');
                } else {

                    //show message
                    $message = __('Are you sure ?', 'buddystream_'.$buddystream_extension) . '
                    <a href="?page=buddystream_'.$buddystream_extension.'&settings=users&action=reset&user_id=' . $_GET['user_id'] . '&confirmed=1">Yes</a> | <a href="?page=buddystream_'.$buddystream_extension.'&settings=users">No</a>';
                    $message_type = "info";
                }
            }
        }
        ?>

        <blockquote>
            <p><?php _e('users description', 'buddystream');?></p>
        </blockquote>

        <?php if (isset($message)): ?>
            <div class="alert alert-<?php if (isset($message_type)) {
                echo $message_type;
            } else {
                echo "success";
            } ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <table class="table table-striped" cellpadding="0" cellspacing="0">

            <thead>
                <th>Username</th>
                <th><?php _e('Items imported', 'buddystream_'.$buddystream_extension); ?></th>
                <th><?php _e('Reset user', 'buddystream_'.$buddystream_extension); ?></th>
            </thead>

            <?php

            //get all users
            global $wpdb, $bp;
            $rowClass = "even";

            $users = call_user_func("buddystream" . ucfirst($buddystream_extension) . "Users");

            if ($users) {
                foreach ($users as $user_meta) {

                    //get userdata
                    $user_data = $wpdb->get_results("SELECT * FROM ".$wpdb->users." WHERE id=".$user_meta->user_id.";");
                    $user_data = $user_data[0];

                    //count imported items
                    $imported_items = call_user_func("buddystream" . ucfirst($buddystream_extension) . "CountItems", $user_meta->user_id);

                    //is import on for user?
                    if( ! call_user_func("buddystream" . ucfirst($buddystream_extension) . "ImportOn", $user_meta->user_id)){
                        $imported_items = __('Turned off by user', 'buddystream');
                    }

                    echo "
                    <tr>
                        <td><a href='" . $bp->root_domain . "/" . BP_MEMBERS_SLUG . "/" . $user_data->user_login . "'>" . $user_data->user_login . "</a></td>
                        <td width='90'><span class='label label-info'>" . $imported_items . "</span></td>
                        <td width='70'><a href='?page=buddystream_".$buddystream_extension."&settings=users&action=reset&user_id=" . $user_data->ID . "'><i class='icon-repeat'></i></a></td>
                    </tr>
                    ";
                }
            }
            ?>
        </table>
    </div>