jQuery(document).ready(function(jQuery) {

    jQuery('.activity').ajaxStop(function() { 
         setTimeout("buddystreamLoadColorBox();", 1500);
    });
    
    buddystreamLoadColorBox();
 });
 
function buddystreamLoadColorBox(){
    jQuery(".buddystream_activity_container .bs_lightbox").colorbox();
    jQuery(".buddystream_activity_container .bs_lightbox[href*='http://www.youtube.com/embed/']").colorbox( { iframe:true, innerWidth:625, innerHeight:444 } );
    jQuery(".buddystream_activity_container .bs_lightbox[href*='http://player.vimeo.com/']").colorbox( { iframe:true, innerWidth:625, innerHeight:444 } );
}