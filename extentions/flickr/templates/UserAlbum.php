<h3><?php _e('Flickr Album', 'buddystream_flickr')?></h3>
<?php if( bp_has_activities('object=flickr&per_page=35')): ?>
    <div class="buddystream_flickr_album">
        <div class="buddystream_flickr_album_navigation">
        <?php echo bp_get_activity_pagination_links(); ?>
    </div>
    <?php    
        while ( bp_activities() ) { 
            bp_the_activity(); 
            $content = bp_get_activity_content_body();
            $content = strip_tags($content,"<img><a>");
            echo $content;
        }
    ?>
    </div>
    <div class="buddystream_flickr_album_navigation">
        <?php echo bp_get_activity_pagination_links(); ?>
    </div>

<?php else: ?>
    <?php  _e('User has no photo\'s (yet)','buddystream_flickr'); ?>
<?php endif; ?>