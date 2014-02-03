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
        if ($_POST) {
            update_site_option('tweetstream_filter', trim(strip_tags(strtolower($_POST ['tweetstream_filter']))));
            update_site_option('tweetstream_filter_show', trim(strip_tags($_POST ['tweetstream_filter_show'])));
            update_site_option('tweetstream_filterexplicit', trim(strip_tags(strtolower($_POST ['tweetstream_filterexplicit']))));

            $message = __('Filters saved.', 'buddystream_twitter');
        }
        ?>

        <blockquote>
            <p>
                <?php _e('twitter filters description', 'buddystream_twitter');?>
            </p>
        </blockquote>

        <form method="post" action="">
            <table class="table table-striped" cellspacing="0">

                <thead>
                <tr>
                    <th><?php echo __('Twitter filters (optional)', 'buddystream_twitter');?></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>

                <tr>
                    <td><?php echo __('Filters (comma seperated)', 'buddystream_twitter');?></td>
                    <td><input type="text" name="tweetstream_filter"
                               value="<?php echo get_site_option('tweetstream_filter');?>" size="50"/></td>
                </tr>

                <tr class="odd">
                    <td><?php echo __('Explicit words (comma seperated)', 'buddystream_twitter');?></td>
                    <td><input type="text" name="tweetstream_filterexplicit"
                               value="<?php echo get_site_option('tweetstream_filterexplicit');?>" size="50"/></td>
                </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" class="btn btn-inverse"
                                     value="<?php _e('Save Changes', 'buddystream_facebook') ?>"/></p>
        </form>
    </div>
</div>
