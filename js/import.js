jQuery(document).ready(function($) {
    jQuery('body').append('<div id="buddystream_cron_loader" style="display:none;">...</div>');
    jQuery('#buddystream_cron_loader').load('/?buddystreamcron=run');
});