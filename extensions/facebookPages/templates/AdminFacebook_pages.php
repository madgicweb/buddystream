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
            echo $buddyStreamExtensions->tabLoader('facebook');
        ?>

        <?php

            $arraySwitches = array(
                'facestream_user_settings_syncpagesbp'
            );

            if ($_POST) {
                foreach($arraySwitches as $switch){
                    update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));
                }

                update_site_option('buddystream_facebookPages_setup', true);
                $message =  __('Settings saved.', 'buddystream_facebook');
            }
        ?>

        <blockquote>
            <p> <?php _e('facebook pages settings description','buddystream_facebook'); ?></p>
        </blockquote>

        <form method="post" action="">

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="table table-striped" cellpadding="0" cellspacing="0">

                <? if(get_site_option('facestream_application_secret')!="" && get_site_option('facestream_application_id')!="") { ?>

                    <thead>
                        <tr>
                            <th colspan="2"><?php _e('Facebook pages options', 'buddystream_facebook');?></th>
                        </tr>
                    </thead>

                    <tr>
                        <td><?php _e( 'Allow users to sync pages to BuddyPress?', 'buddystream_facebook' );?></td>
                        <td><input class="switch icons" type="checkbox" name="facestream_user_settings_syncpagesbp" id="facestream_user_settings_syncpagesbp"/></td>
                    </tr>

                <? } ?>

            </table>
            <input type="submit" class="btn btn-inverse" value="<?php _e('Save Changes', 'buddystream_facebook') ?>"/>

        </form>
        <script type="text/javascript">
            jQuery(".switch").slickswitch();
        </script>

        <?php
        foreach ($arraySwitches as $switch) {
            if (get_site_option($switch)) {
                echo'
                <script>
                    jQuery("#' . $switch . '").slickswitch("toggleOn");
                </script>
                ';
            } else {
                echo'
                <script>
                    jQuery("#' . $switch . '").slickswitch("toggleOff");
                </script>
                ';
            }
        }
        ?>
    </div>
</div>