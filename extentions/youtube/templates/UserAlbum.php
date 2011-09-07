<h3><?php _e('Youtube Album', 'buddystream_youtube')?></h3>
<?php if( bp_has_activities('object=youtube&per_page=35')): ?>
    <div class="buddystream_youtube_album">
        <div class="buddystream_youtube_album_navigation">
        <?php echo bp_get_activity_pagination_links(); ?>
    </div>
    <?php    
        while ( bp_activities() ) { 
            bp_the_activity(); 
            $content = bp_get_activity_content_body();
            $content = strip_tags($content,"<img><a><div>");
            echo $content;
        }
    ?>
    </div>
    <div class="buddystream_youtube_album_navigation">
        <?php echo bp_get_activity_pagination_links(); ?>
    </div>

<?php else: ?>
    <?php  _e('User has no video\'s (yet)','buddystream_youtube'); ?>
<?php endif; ?>

<div class="buddystream_youtube_album_hoverbox"></div>

<script>
        jQuery(document).ready(function() {
            
            jQuery(".bs_lightbox").attr("title","");
            jQuery(".buddystream_activity_container").mouseenter(function() {  
                
                parentPosition = jQuery(this).find('img').position();
                parentOffset = jQuery(this).find('img').offset();
                
                content = jQuery(this).find(".youtube_container_message").html();
                
                 jQuery('.buddystream_youtube_album_hoverbox').html(content);
                 jQuery('.buddystream_youtube_album_hoverbox').css('top', (parentOffset.top-145));
                 jQuery('.buddystream_youtube_album_hoverbox').css('left', parentPosition.left+110);
                 jQuery('.buddystream_youtube_album_hoverbox').show();
                
            }).mouseleave(function() {
                jQuery('.buddystream_youtube_album_hoverbox').hide();
            });
         });
</script>