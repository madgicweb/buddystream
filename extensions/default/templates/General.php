&nbsp;
<?php
$arraySwitches = array(
    'buddystream_social_albums',
    'buddystream_social_albums_profile_navigation',
    'buddystream_group_sharing',
    'buddystream_nocss',
    'buddystream_nobuddybox',
    'buddystream_nolocation'
);

//save the settings
if ($_POST['submit']) {
    foreach ($arraySwitches as $switch) {
        delete_site_option($switch);
        update_site_option($switch, $_POST[$switch]);
    }

    $message = __('Settings saved.', 'buddystream_lang');
}
?>
<?php include "TemplateHeader.php"; ?>

<br><br>
<div id="buddystream" class="container">

    <div class="span9">

        <?php include "AdminMenu.php"; ?>

        <blockquote class="pull-left">
            <p><?php _e('general settings description', 'buddystream_lang'); ?></p>
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
                <thead>
                <tr>
                    <th><?php _e('General settings', 'buddystream_lang');?></th>
                </tr>
                </thead>

                <tr>
                    <td><?php _e('Enable social abums feature.', 'buddystream_lang');?></td>
                    <td width="30"><input class="switch icons" type="checkbox" name="buddystream_social_albums"
                                          id="buddystream_social_albums"/></td>
                </tr>

                <tr>
                    <td><?php _e('Move social albums under profile navigation.', 'buddystream_lang');?></td>
                    <td width="30"><input class="switch icons" type="checkbox"
                                          name="buddystream_social_albums_profile_navigation"
                                          id="buddystream_social_albums_profile_navigation"/></td>
                </tr>

                <tr>
                    <td><?php _e('Enable sharing on groups and forums.', 'buddystream_lang');?></td>
                    <td width="30"><input class="switch icons" type="checkbox" name="buddystream_group_sharing"
                                          id="buddystream_group_sharing"/></td>
                </tr>

                <tr>
                    <td><?php _e('Disable all BuddyStream CSS includes.', 'buddystream_lang');?></td>
                    <td width="30"><input class="switch icons" type="checkbox" name="buddystream_nocss"
                                          id="buddystream_nocss"/></td>
                </tr>

                <tr>
                    <td><?php _e('Disable BuddyStream buddybox (popup for images and video\'s).', 'buddystream_lang');?></td>
                    <td width="30"><input class="switch icons" type="checkbox" name="buddystream_nobuddybox"
                                          id="buddystream_nobuddybox"/></td>
                </tr>

                <tr>
                    <td><?php _e('Disable location feature in BuddyStream.', 'buddystream_lang');?></td>
                    <td width="30"><input class="switch icons" type="checkbox" name="buddystream_nolocation"
                                          id="buddystream_nolocation"/></td>
                </tr>

            </table>

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
foreach ($arraySwitches as $switch) {
    if (get_site_option($switch) == "on") {
        $runscript .= 'jQuery("#' . $switch . '").slickswitch("toggleOn");';
    } else {
        $runscript .= 'jQuery("#' . $switch . '").slickswitch("toggleOff");';
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
            foreach ($arraySwitches as $switch) {
                echo 'jQuery("#'.$switch.'").slickswitch("toggleOn");';
            }
         ?>
    }

    function buddystreamTurnAllOff() {
        <?php
            foreach ($arraySwitches as $switch) {
                echo 'jQuery("#'.$switch.'").slickswitch("toggleOff");';
            }
         ?>
    }

</script>