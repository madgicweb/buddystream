<h3><?php _e('Youtube Album', 'buddystream_youtube')?></h3>
<?php if (bp_has_activities('object=youtube&per_page=35')): ?>

    <?php if (bp_get_activity_pagination_links()): ?>
        <div class="buddystream_album_navigation">
            <?php echo bp_get_activity_pagination_links(); ?>
        </div>
    <?php endif; ?>

    <div class="buddystream_album">
        <?php
        while (bp_activities()) {
            bp_the_activity();
            $content = strip_tags(bp_get_activity_content_body(), "<a><img>");
            echo $content;
        }
        ?>
    </div>

    <?php if (bp_get_activity_pagination_links()): ?>
        <div class="buddystream_album_navigation">
            <?php echo bp_get_activity_pagination_links(); ?>
        </div>
    <?php endif; ?>

<?php else: ?>
    <?php _e('User has no video\'s (yet)', 'buddystream_youtube'); ?>
<?php endif; ?>