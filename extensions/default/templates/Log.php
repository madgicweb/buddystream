&nbsp;
<?php include "TemplateHeader.php"; ?>

<br><br>
<div id="buddystream" class="container">

    <div class="span9">

        <?php include "AdminMenu.php"; ?>

        <blockquote class="pull-left">
            <p><?php _e('buddystream log description', 'buddystream_lang'); ?></p>
        </blockquote>

        <table class="table table-striped">
            <thead>
            <tr>
                <th><?php _e('Date', 'buddystream_lang'); ?></th>
                <th><?php _e('Message', 'buddystream_lang'); ?></th>
                <th><?php _e('Type', 'buddystream_lang'); ?></th>
            </tr>
            </thead>

            <?php
            global $wpdb;
            $logs = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "buddystream_log ORDER BY id DESC LIMIT 20");

            foreach ($logs as $log) {
                echo '<tr>
                    <td><i class="icon-time"></i> ' . $log->date . '</td>
                    <td>' . $log->message . '</td>
                    <td><span class="label label-' . $log->type . '">' . $log->type . '</span></td>
                  </tr>';
            }
            ?>
        </table>
    </div>
</div>