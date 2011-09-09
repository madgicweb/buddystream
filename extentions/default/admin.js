jQuery(document).ready(function() {
    jQuery(".buddystream_Adminmenu a").hover(
      function () {
        description = jQuery(this).attr('name');
        if (description != "") {
            jQuery("#tab_description_content").html(description);
        }
      },
      function () {
          jQuery("#tab_description_content").html('');
      }
    );
});