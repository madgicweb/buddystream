jQuery(document).ready(function (jQuery) {

    jQuery('.activity').ajaxStop(function () {
        setTimeout("buddystreamLoadBuddyBox();", 1500);
    });

    buddystreamLoadBuddyBox();

    jQuery(".buddystream_share_button.mylocation").click(function() {
        navigator.geolocation.getCurrentPosition(buddystreamUseLocation);
        jQuery(".buddystream_location_type").html('location');
    });

    jQuery(".buddystream_share_button.foursquare").click(function() {
        navigator.geolocation.getCurrentPosition(buddystreamUseLocation);
        jQuery(".buddystream_location_type").html('foursquare');
    });


    jQuery(".buddystream_location_button.cancel").click(function() {
        jQuery(".buddystream_show_location").hide();
        jQuery(".buddystream_location_button").hide();
    });

    jQuery(".buddystream_location_button.use").click(function() {
        jQuery(".buddystream_show_location").hide();
        jQuery(".buddystream_location_button").hide();

        if(jQuery(".buddystream_location_type").html() == "location"){
            location_addTag();
        }

        if(jQuery(".buddystream_location_type").html() == "foursquare"){
            foursquare_addTag();
        }

    });
});


function buddystreamUseLocation(position){

    var icon     = buddystream_url + '/images/marker.png';
    var bsLat    = position.coords.latitude;
    var bsLong   = position.coords.longitude;
    var locName  = "";

    var latlng = new google.maps.LatLng(bsLat, bsLong);

    geocoder = new google.maps.Geocoder();

    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                locName = results[0].address_components[2].long_name;

                buddystreamSetLocationCookie(bsLat + "#" + bsLong + "#" + locName);

                var mapUrl = 'http://maps.googleapis.com/maps/api/staticmap?center=' + bsLat + ',' + bsLong + '&zoom=13&size=540x150&sensor=false&markers=icon%3A' + icon + '%7C' + bsLat + ',' + bsLong + '&format=png32';
                var mapImg = '<img src="' + mapUrl + '">';

                jQuery(".buddystream_location_map").html(mapImg);
                jQuery(".buddystream_show_location").show();
                jQuery(".buddystream_location_button").show();
            }
        }
    });

}

//create a cookie for location
function buddystreamSetLocationCookie(value)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate()+1);
    document.cookie= "buddystream_location="+value+"; expires="+exdate +"; path=/";
}


function buddystreamLoadBuddyBox() {
    if (jQuery.fn.buddybox) {
        jQuery(".bs_lightbox").buddybox();
        jQuery(".bs_lightbox[href*='http://www.youtube.com/embed/']").buddybox({ iframe: true, innerWidth: 625, innerHeight: 444 });
        jQuery(".bs_lightbox[href*='http://player.vimeo.com/']").buddybox({ iframe: true, innerWidth: 625, innerHeight: 444 });
    }
}

/*
 * Hoverbox functionality
 */

jQuery(document).ready(function (jQuery) {
    jQuery('.buddystream_share_button.linkedin').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.linkedin');
    }, function () {
        buddystreamHideHoverBox();
    });
    jQuery('.buddystream_share_button.facebook').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.facebook');
    }, function () {
        buddystreamHideHoverBox();
    });
    jQuery('.buddystream_share_button.facebookpage').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.facebookpage');
    }, function () {
        buddystreamHideHoverBox();
    });
    jQuery('.buddystream_share_button.twitter').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.twitter');
    }, function () {
        buddystreamHideHoverBox();
    });
    jQuery('.buddystream_share_button.tumblr').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.tumblr');
    }, function () {
        buddystreamHideHoverBox();
    });
    jQuery('.buddystream_share_button.foursquare').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.foursquare');
    }, function () {
        buddystreamHideHoverBox();
    });


    jQuery('.buddystream_share_button.mylocation').hover(function () {
        buddystreamShowHoverBox('.buddystream_share_button.mylocation');
    }, function () {
        buddystreamHideHoverBox();
    });
});

function buddystreamShowHoverBox(className) {

    var button = jQuery('' + className + '');
    var position = button.position();
    var buttonHeight = button.height() + 5;
    var helpText = button.attr('id');

    jQuery('.buddystream_hoverbox').css('left', position.left);
    jQuery('.buddystream_hoverbox').css('top', position.top + buttonHeight);

    jQuery('.buddystream_hoverbox').html(helpText);
    jQuery('.buddystream_hoverbox').show();
}

function buddystreamHideHoverBox() {
    jQuery('.buddystream_hoverbox').hide();
    jQuery('.buddystream_hoverbox').html('');
}


/*
 * Adding hastags functions
 */


function location_addTag() {
    if (jQuery('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (jQuery('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (jQuery('#reply_text').length) {
        var field = '#reply_text';
    }
    var content;
    content = jQuery(field).val();
    content = content.replace('#location ', '');
    content = '#location ' + content;

    jQuery(field).val(content);
}


function foursquare_addTag() {

    if (jQuery('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (jQuery('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (jQuery('#reply_text').length) {
        var field = '#reply_text';
    }

    var content;

    //we need a location hashtag

    content = jQuery(field).val();
    content = content.replace('#foursquare ', '');
    content = '#foursquare ' + content;

    jQuery(field).val(content);
}

function facebook_addTag() {

    if (jQuery('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (jQuery('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (jQuery('#reply_text').length) {
        var field = '#reply_text';
    }

    var content;
    content = jQuery(field).val();
    content = content.replace('#facebook ', '');
    content = '#facebook ' + content;

    jQuery(field).val(content);
}

function facebookpage_addTag() {

    if (jQuery('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (jQuery('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (jQuery('#reply_text').length) {
        var field = '#reply_text';
    }
    var content;
    content = jQuery(field).val();
    content = content.replace('#facebookpage ', '');
    content = '#facebookpage ' + content;

    jQuery(field).val(content);
}


function linkedin_addTag() {

    if (jQuery('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (jQuery('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (jQuery('#reply_text').length) {
        var field = '#reply_text';
    }

    var content;
    content = jQuery(field).val();
    content = content.replace('#linkedin ', '');
    content = '#linkedin ' + content;
    jQuery(field).val(content);

    jQuery('.linkedin_share_counterbox').show();
    linkedin_counter(field);
}


jQuery(document).ready(function () {
    if (jQuery('#whats-new').length) {
        var field = '#whats-new';
    }

    else if (jQuery('#topic_title').length) {
        var field = '#topic_title';
    }

    else if (jQuery('#reply_text').length) {
        var field = '#reply_text';
    }

    jQuery(field).keyup(function () {
        linkedin_counter(field);
    });
});


function linkedin_counter(field) {

    var text;
    text = jQuery(field).val();

    var networksArray = buddystreamNetworks.split(',');
    jQuery.each(networksArray, function (key, value) {
        text = text.replace(value + ' ', '');
    });

    var textlength = parseInt(text.length);
    var patt1 = /#linkedin/gi;

    if (jQuery(field).val().match(patt1)) {
        jQuery('.linkedin_share_counterbox').show();

        var counterlength = 700 - textlength;
        var htmltext = counterlength;

        if (counterlength < 100) {
            htmltext = '0' + htmltext;
        }

        if (counterlength < 10) {
            htmltext = '0' + htmltext;
        }

        if (counterlength < 1) {
            htmltext = '000';
        }

        jQuery('.linkedin_share_counter').html(htmltext);

        if (textlength > 700) {

            var position = jQuery('#whats-new-submit').position();
            jQuery('.buddystream_hoverbox').css('left', position.left);
            jQuery('.buddystream_hoverbox').css('top', position.top + 38);
            jQuery('.buddystream_hoverbox').css('background', 'red');
            jQuery('.buddystream_hoverbox').html('You reached the maximum allowed characters for linkedin, your message will be cutoff.');
            jQuery('.buddystream_hoverbox').show();

            jQuery('.linkedin_share_counter').addClass('linkedin_share_counter_red');
        } else {
            jQuery('.linkedin_share_counter').removeClass('linkedin_share_counter_red');
            jQuery('.buddystream_hoverbox').hide();
        }

    } else {
        jQuery('.linkedin_share_counter').removeClass('linkedin_share_counter_red');
        jQuery('.linkedin_share_counter').html('700');
        jQuery('.linkedin_share_counterbox').hide();
        jQuery('.buddystream_hoverbox').hide();
    }
}

function tumblr_addTag() {

    if (jQuery('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (jQuery('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (jQuery('#reply_text').length) {
        var field = '#reply_text';
    }

    var content;
    content = jQuery(field).val();
    content = content.replace('#tumblr ', '');
    content = '#tumblr ' + content;
    jQuery(field).val(content);

}


jQuery(document).ready(function () {
    if (jQuery('#whats-new').length) {
        var field = '#whats-new';
    }

    else if (jQuery('#topic_title').length) {
        var field = '#topic_title';
    }

    else if (jQuery('#reply_text').length) {
        var field = '#reply_text';
    }

});

function twitter_addTag() {

    if (jQuery('#whats-new').length) {
        var field = '#whats-new';
    }
    else if (jQuery('#topic_title').length) {
        var field = '#topic_title';
    }
    else if (jQuery('#reply_text').length) {
        var field = '#reply_text';
    }

    var content;
    content = jQuery(field).val();
    content = content.replace('#twitter ', '');
    content = '#twitter ' + content;
    jQuery(field).val(content);

    twitter_counter(field);
    jQuery('.twitter_share_counterbox').show();
}


jQuery(document).ready(function () {
    if (jQuery('#whats-new').length) {
        var field = '#whats-new';
    }

    else if (jQuery('#topic_title').length) {
        var field = '#topic_title';
    }

    else if (jQuery('#reply_text').length) {
        var field = '#reply_text';
    }


    jQuery(field).keyup(function () {
        twitter_counter(field);
    });

});


function twitter_counter(field) {

    var text;
    text = jQuery(field).val();

    var networksArray = buddystreamNetworks.split(',');
    jQuery.each(networksArray, function (key, value) {
        text = text.replace(value + ' ', '');
    });

    var textlength = parseInt(text.length);

    var patt1 = /#twitter/gi;
    if (jQuery(field).val().match(patt1)) {
        jQuery('.twitter_share_counterbox').show();

        var counterlength = 141 - textlength;
        var htmltext = counterlength;

        if (counterlength < 100) {
            htmltext = '0' + htmltext;
        }

        if (counterlength < 10) {
            htmltext = '0' + htmltext;
        }

        if (counterlength < 1) {
            htmltext = '000';
        }

        jQuery('.twitter_share_counter').html(htmltext);

        if (textlength > 141) {

            var position = jQuery('#whats-new-submit').position();
            jQuery('.buddystream_hoverbox').css('left', position.left);
            jQuery('.buddystream_hoverbox').css('top', position.top + 38);
            jQuery('.buddystream_hoverbox').css('background', 'red');
            jQuery('.buddystream_hoverbox').html('You reached the maximum allowed characters for Twitter, your message will be cutoff.');
            jQuery('.buddystream_hoverbox').show();

            jQuery('.twitter_share_counter').addClass('twitter_share_counter_red');
        } else {
            jQuery('.twitter_share_counter').removeClass('twitter_share_counter_red');
            jQuery('.buddystream_hoverbox').hide();
        }

    } else {
        jQuery('.twitter_share_counter').removeClass('twitter_share_counter_red');
        jQuery('.twitter_share_counter').html('140');
        jQuery('.twitter_share_counterbox').hide();
        jQuery('.buddystream_hoverbox').hide();
    }
}




