&nbsp;
<?php include "TemplateHeader.php"; ?>

<?php
global $bp;
$buddyStreamExtensions = new BuddyStreamExtensions();

if ($_POST['submit']) {
    $message = __('Settings saved.', 'buddystream');
}
?>


<br><br>
<div id="buddystream" class="container">

    <div class="span9">

        <?php include "AdminMenu.php"; ?>

        <blockquote class="pull-left">
            <p>  <?php _e('synccentral description', 'buddystream_lang'); ?>       </p>
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

                if (is_array($extension) && !$extension['parent'] && $extension['synctypes'] && get_site_option('buddystream_' . $extension['name'] . '_power')) {

                    echo '
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>' . __(ucfirst($extension['displayname']), 'buddystream') . '</th>
                                <th></th>
                            </tr>
                        </thead>';

                    $arrSyncTypes = explode(",", str_replace(" ","",$extension['synctypes']));
                    foreach($arrSyncTypes as $syncType){

                        //define vars
                        define('buddystream_' . $extension['name'] . '_'.$syncType, "");

                        if ($_POST) {
                            delete_site_option('buddystream_' . $extension['name'] . '_'.$syncType);
                            update_site_option('buddystream_' . $extension['name'] . '_'.$syncType, trim($_POST['buddystream_' . $extension['name'] . '_'.$syncType]));
                        }

                        echo '
                        <tr>
                            <td>'.ucfirst($syncType).'</td>
                            <td width="30">
                                <input id="buddystream_' . $extension['name'] . '_'.$syncType.'" class="switch icons" type="checkbox" name="buddystream_' . $extension['name'] . '_'.$syncType.'" />
                            </td>
                        </tr>';
                    }


                    //get parent subextensions
                    $subExtensions = $buddyStreamExtensions->getExtensionsWithParent($extension['name']);
                    foreach ($subExtensions as $subExtension) {

                        if($subExtension['synctypes']){

                            echo'
                            <thead>
                            <tr>
                                <th>' . __(ucfirst($subExtension['displayname']), 'buddystream') . '</th>
                                <th></th>
                            </tr>
                            </thead>';

                            $arrSyncTypes = explode(",", str_replace(" ","",$subExtension['synctypes']));
                            foreach($arrSyncTypes as $syncType){

                                //define vars
                                define('buddystream_' . $subExtension['name'] . '_'.$syncType, "");

                                if ($_POST) {
                                    delete_site_option('buddystream_' . $subExtension['name'] . '_'.$syncType);
                                    update_site_option('buddystream_' . $subExtension['name'] . '_'.$syncType, trim($_POST['buddystream_' . $subExtension['name'] . '_'.$syncType]));
                                }

                                echo '
                                <tr>
                                    <td>'.ucfirst($syncType).'</td>
                                    <td width="30">
                                        <input id="buddystream_' . $subExtension['name'] . '_'.$syncType.'" class="switch icons" type="checkbox" name="buddystream_' . $subExtension['name'] . '_'.$syncType.'" />
                                    </td>
                                </tr>';
                            }
                        }
                    }

                    echo '</table>';
                }
            }

            ?>

            <div style="float:left; clear:both;">
                <input type="submit" name="submit" class="btn btn-inverse" value="<?php _e('Save Changes') ?>"/>
            </div>
        </form>

    </div>
</div>


<?php
if ($_POST) {
    //reset the importer queue
    update_site_option("buddystream_importers_queue", "");

    //set the new importer queue
    foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {

       if (is_array($extension) && get_site_option('buddystream_' . $extension['name'] . '_power') == "on" && get_site_option('buddystream_' . $extension['name'] . '_import') == "on") {
            $importerQueue[] = $extension['name'];
        }
    }

    update_site_option("buddystream_importers_queue", implode(",", $importerQueue));
}

//flip switches
$runscript = "";
foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {

    $arrSyncTypes = explode(",", str_replace(" ","",$extension['synctypes']));
    foreach($arrSyncTypes as $syncType){

        if (get_site_option('buddystream_' . $extension['name'] . '_'.$syncType)) {
            $runscript .= 'jQuery("#buddystream_' . $extension['name'] . '_'.$syncType.'").slickswitch("toggleOn");';
        } else {
            $runscript .= 'jQuery("#buddystream_' . $extension['name'] . '_'.$syncType.'").slickswitch("toggleOff");';
        }
    }

    foreach ($buddyStreamExtensions->getExtensionsWithParent($extension['name']) as $subExtension) {

        $arrSyncTypes = explode(",", str_replace(" ","",$subExtension['synctypes']));
        foreach($arrSyncTypes as $syncType){

            if (get_site_option('buddystream_' . $subExtension['name'] . '_'.$syncType)) {
                $runscript .= 'jQuery("#buddystream_' . $subExtension['name'] . '_'.$syncType.'").slickswitch("toggleOn");';
            } else {
                $runscript .= 'jQuery("#buddystream_' . $subExtension['name'] . '_'.$syncType.'").slickswitch("toggleOff");';
            }
        }
    }

}
?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".switch").slickswitch();
        <?php echo $runscript;?>
    });
</script>