&nbsp;
<?php include "TemplateHeader.php"; ?>

<?php
global $bp;
$buddyStreamExtensions = new BuddyStreamExtensions();

if ($_POST['submit']) {

    //reset the importer queue
    update_site_option("buddystream_importers_queue", "");

    //set the new importer queue
    foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {
        if (is_array($extension) && isset($_POST['buddystream_' . $extension['name'] . '_power']) && $_POST['buddystream_' . $extension['name'] . '_power'] == "on") {
            $importerQueue[] = $extension['name'];
        }
    }

    update_site_option("buddystream_importers_queue", implode(",", $importerQueue));

    $message = __('Settings saved.', 'buddystream');
}
?>


<br><br>
<div id="buddystream" class="container">

    <div class="span9">

        <?php include "AdminMenu.php"; ?>

        <blockquote class="pull-left">
            <p>  <?php _e('powercentral description', 'buddystream_lang'); ?>       </p>
        </blockquote>
    </div>

    <div class="span9">


        <form id="settings_form" action="" method="post">


            <?php if ($message): ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php
            //loop throught extensions directory and get all extensions
            foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {

                if (is_array($extension) && !$extension['parent']) {

                    //define vars
                    define('buddystream_' . $extension['name'] . '_power', "");

                    if ($_POST) {
                        delete_site_option('buddystream_' . $extension['name'] . '_power');
                        update_site_option('buddystream_' . $extension['name'] . '_power', trim($_POST['buddystream_' . $extension['name'] . '_power']));
                    }

                    echo '

                        <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>' . __(ucfirst($extension['displayname']), 'buddystream') . '</th>
                        </tr>
                        </thead>

                        <tr>
                            <td>Core</td>
                            <td width="30">
                                <input id="buddystream_' . $extension['name'] . '" class="switch icons" type="checkbox" name="buddystream_' . $extension['name'] . '_power" />
                            </td>
                        </tr>';

                    //get parent subextensions
                    $subExtensions = $buddyStreamExtensions->getExtensionsWithParent($extension['name']);
                    foreach ($subExtensions as $subExtension) {

                        if ($_POST) {
                            delete_site_option('buddystream_' . $subExtension['name'] . '_power');
                            update_site_option('buddystream_' . $subExtension['name'] . '_power', trim($_POST['buddystream_' . $subExtension['name'] . '_power']));
                        }

                        define('buddystream_' . $subExtension['name'] . '_power', "");

                        echo '<tr>
                                    <td>' . str_replace($extension['name'], '', $subExtension['displayname']) . '</td>
                                    <td width="30"><input id="buddystream_' . $subExtension['name'] . '" class="switch icons" type="checkbox" name="buddystream_' . $subExtension['name'] . '_power" /></td>
                                    </tr>';


                    }

                    echo '</table>';
                }
            }

            ?>

            <div style="float:left; clear:both;">
                <input type="submit" name="submit" class="btn btn-inverse" value="<?php _e('Save Changes') ?>"/>
                <input type="button" onclick="buddystreamTurnAllOn()" class="btn btn-inverse"
                       value="<?php _e('Turn all on', 'buddystream_lang') ?>"/>
                <input type="button" onclick="buddystreamTurnAllOff()" class="btn btn-inverse"
                       value="<?php _e('Turn all off', 'buddystream_lang') ?>"/>
            </div>
        </form>

    </div>
</div>


<?php
//flip switches
$runscript = "";
foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {
    if (get_site_option('buddystream_' . $extension['name'] . '_power')) {
        $runscript .= 'jQuery("#buddystream_' . $extension['name'] . '").slickswitch("toggleOn");';
    } else {
        $runscript .= 'jQuery("#buddystream_' . $extension['name'] . '").slickswitch("toggleOff");';
    }
}
?>


<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".switch").slickswitch();
        <?php echo $runscript;?>
    });

    function buddystreamTurnAllOn() {
        <?php
            foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {
                echo 'jQuery("#buddystream_'.$extension['name'].'").slickswitch("toggleOn");';
            }
         ?>
    }

    function buddystreamTurnAllOff  () {
        <?php
            foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {
                echo 'jQuery("#buddystream_'.$extension['name'].'").slickswitch("toggleOff");';
            }
         ?>
    }

</script>