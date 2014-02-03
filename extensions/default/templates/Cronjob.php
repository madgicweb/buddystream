&nbsp;
<?php
//check if the licensekey is still valid
if (!$_POST && get_site_option('buddystream_license_key')) {
    $response = "";
    $response = buddystreamCheckLicense(get_site_option('buddystream_license_key'));

    if ($response != false) {
        //expired
        if ($response->code == "EXPIRED") {
            delete_site_option("buddystream_cronservices_uniquekey");
            $message = $response->message;
        }
        //something is wrong
        if ($response->code == "ERROR") {
            delete_site_option("buddystream_cronservices_uniquekey");
            delete_site_option("buddystream_license_key");
            $message = $response->message;
        }

        //check ok
        if ($response->code == "OK") {
            //save the uniquekey
            update_site_option("buddystream_cronservices_uniquekey", $response->uniqueKey);
        }
    } else {
        $message = "Something went wrong when tying to check the license key.";
    }
}

//check the licensekey on the buddystream servers
if ($_POST) {
    if (!empty($_POST['buddystream_license_key'])) {
        $response = buddystreamCheckLicense($_POST['buddystream_license_key']);

        if ($response != false) {
            //expired
            if ($response->code == "EXPIRED") {
                delete_site_option("buddystream_cronservices_uniquekey");
                delete_site_option("buddystream_license_key");
                $message = $response->message;
            }

            //something is wrong
            if ($response->code == "ERROR") {
                delete_site_option("buddystream_cronservices_uniquekey");
                delete_site_option("buddystream_license_key");
                $message = $response->message;
            }

            //check ok
            if ($response->code == "OK") {
                //save the uniquekey
                update_site_option("buddystream_cronservices_uniquekey", $reponse->uniqueKey);
                update_site_option("buddystream_license_key", $_POST['buddystream_license_key']);
                $message = __("Cronservice sucessfully configured.", "buddystream_lang");
            }

        } else {
            $message = "Something went wrong when tying to check the license key.";
        }

    } else {
        delete_site_option("buddystream_cronservices_uniquekey");
        delete_site_option("buddystream_license_key");
    }
}
?>

<?php include "TemplateHeader.php"; ?>

<br><br>
<div id="buddystream" class="container">

    <div class="span9">

        <?php include "AdminMenu.php"; ?>

        <blockquote class="pull-left">
            <p><?php _e('cronjob description', 'buddystream_lang'); ?> </p>
        </blockquote>
    </div>

    <div class="span9">
        <form method="post" action="">

            <?php if ($message): ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <table class="table table-striped">

                <?php if (!get_site_option("buddystream_license_key")): ?>

                    <thead>
                    <tr>
                        <th><?php _e('Own cronjob', 'buddystream_lang');?></th>
                    </tr>
                    </thead>

                    <tr>
                        <td><?php _e('Cron command:', 'buddystream_twitter');?></td>
                        <td><input type="text" name="cronurl"
                                   value="<?php echo "*/10 * * * * wget --spider " . BP_BUDDYSTREAM_URL . "import.php --connect-timeout=600 --read-timeout=600 --tries=1 --recursive --level=1 -nd --delete-after";?>"
                                   size="150"/></td>
                    </tr>
                <?php endif; ?>


                <thead>
                <tr>
                    <th><?php _e('BuddyStream Cronservice', 'buddystream_lang');?></th>
                </tr>
                </thead>

                <tr>
                    <td><?php _e('License key', 'buddystream_twitter');?></td>
                    <td><input type="text" name="buddystream_license_key"
                               value="<?php echo get_site_option("buddystream_license_key");?>" size="150"/></td>
                </tr>

            </table>
            <div style="float:left; clear:both;">
                <input type="submit" name="submit" class="btn btn-inverse" value="<?php _e('Save Changes') ?>"/>
            </div>
        </form>
    </div>
</div>
