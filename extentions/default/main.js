jQuery(document).ready(function(jQuery) {

    jQuery(".bs_lightbox").prettyPhoto();
    
    jQuery('.activity').ajaxComplete(function() {
        jQuery(".bs_lightbox").prettyPhoto();
    });

jQuery('#activity-filter-select select').change(function() {
        setTimeout("jQuery(\".bs_lightbox\").prettyPhoto()",1500);
    });
});