function twitter_addTag() {   
    jQuery(document).ready(function() {

        if(jQuery('#whats-new').length){
            var field = '#whats-new';
        }
        else if(jQuery('#topic_title').length){
            var field = '#topic_title';
        }
        else if(jQuery('#reply_text').length){
            var field = '#reply_text';
        }

        content = jQuery(field).val();
        content = content.replace('#twitter ','');
        content = '#twitter '+content;

        jQuery('.twitter_share_counter').show();
        twitterCountMessage(field);

        jQuery(field).val(content);
    });
}

function twitterCountMessage(field) {
   jQuery(document).ready(function() {
       
        twitterCountListner();
       
        text = jQuery(field).val();
        text = text.replace('#twitter','');

        var textlength = parseInt(text.length);
        if (textlength >= 140) {
            jQuery('.twitter_share_counter').html('You reached the maximum allowed characters for twitter.');
        } else {
            jQuery('.twitter_share_counter').html((140-textlength));
            return true;
        }
    });
}

function twitterCountListner(){
    jQuery(document).ready(function() {
        if(jQuery('#whats-new').length){
            var field = '#whats-new';
        }

        else if(jQuery('#topic_title').length){
            var field = '#topic_title';
        }

        else if(jQuery('#reply_text').length){
            var field = '#reply_text';
        }

        jQuery(field).keyup(function(){
            text = jQuery(field).val();
            text = text.replace('#twitter','');
            var textlength = parseInt(text.length);

            var patt1=/#twitter/gi;
            if(jQuery(field).val().match(patt1)){
                jQuery('.twitter_share_counter').show();

                if(textlength > 140){
                    jQuery('.twitter_share_counter').html('You reached the maximum allowed characters for twitter.');
                }else{
                    jQuery('.twitter_share_counter').html((140-textlength));
                    if(140-textlength < 10){
                        jQuery('.twitter_share_counter').addClass('twitter_share_counter_red');
                    }else{
                        jQuery('.twitter_share_counter').removeClass('twitter_share_counter_red');
                    }
                return true;
                }
            }else{
                jQuery('.twitter_share_counter').removeClass('twitter_share_counter_red');
                jQuery('.twitter_share_counter').html('140');
                jQuery('.twitter_share_counter').hide();
            }
        });
    });
}