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

        if ($_POST) {
            update_site_option('facestream_filter', trim(strip_tags(strtolower($_POST ['facestream_filter']))));
            update_site_option('facestream_filter_show', trim(strip_tags($_POST ['facestream_filter_show'])));
            update_site_option('facestream_filterexplicit', trim(strip_tags(strtolower($_POST ['facestream_filterexplicit']))));

            $message = __('Filters saved.', 'buddystream_facebook');
        }
        ?>

        <blockquote>
            <p><?php _e('facebook filters description', 'buddystream_facebook'); ?></p>
        </blockquote>

        <form method="post" action="">
            <table class="table table-striped" cellspacing="0">

                <thead>
                <tr>
                    <th><?php _e('Facebook filters (optional)', 'buddystream_facebook');?></th>
                    <th></th>
                </tr>
                </thead>

                <tr>
                    <td><?php _e('Filters (comma seperated)', 'buddystream_facebook');?></td>
                    <td>
                        <input type="text" name="facestream_filter"
                               value="<?php echo get_site_option('facestream_filter');?>" size="50"/>
                    </td>
                </tr>

                <tr class="odd">
                    <td><?php _e('Explicit words filters (comma seperated)', 'buddystream_facebook');?></td>
                    <td>
                        <input type="text" name="facestream_filterexplicit"
                               value="<?php echo get_site_option('facestream_filterexplicit');?>" size="50"/>
                    </td>
                </tr>
            </table>
            <input type="submit" class="btn btn-inverse" value="<?php _e('Save Changes') ?>"/>
        </form>

    </div>
</div>