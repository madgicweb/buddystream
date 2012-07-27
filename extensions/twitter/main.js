
jQuery('.twitter_share_button').hover(
  function () {
      
      var position = jQuery('.twitter_share_button').position();
      
      jQuery('.twitter_hoverbox').css('left', position.left-85);
      jQuery('.twitter_hoverbox').css('top', position.top+36);
      jQuery('.twitter_hoverbox').html(this.id);
      jQuery('.twitter_hoverbox').show();
  }, 
  function () {
    jQuery('.twitter_hoverbox').hide();
  }
);


function twitter_addTag() {   

        if(jQuery('#whats-new').length){
            var field = '#whats-new';
        }
        else if(jQuery('#topic_title').length){
            var field = '#topic_title';
        }
        else if(jQuery('#reply_text').length){
            var field = '#reply_text';
        }

        var content;
        content = jQuery(field).val();
        content = content.replace('#twitter ','');
        content = '#twitter '+content;
        jQuery(field).val(content);
        
        twitter_counter(field);
        jQuery('.twitter_share_counterbox').show();
}


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
            twitter_counter(field);
        });

    });
    
 
    function twitter_counter(field){

            var text;
            text = jQuery(field).val();
            
            var networksArray = buddystreamNetworks.split(',');
            jQuery.each(networksArray, function(key, value) { 
                text = text.replace(value+' ','');
            });
            
            var textlength = parseInt(text.length);
            
            var patt1=/#twitter/gi;
            if(jQuery(field).val().match(patt1)){
                jQuery('.twitter_share_counterbox').show();
                
                var counterlength = 141-textlength;
                var htmltext      = counterlength;
             
                if(counterlength < 100){
                    htmltext = '0'+htmltext;
                }
                
                if(counterlength < 10){
                    htmltext = '0'+htmltext;
                }
                
                if(counterlength < 1){
                    htmltext = '000';
                }
                
                jQuery('.twitter_share_counter').html(htmltext);

                if(textlength > 141){
                    
                        var position = jQuery('#whats-new-submit').position();
                        jQuery('.twitter_hoverbox').css('left', position.left);
                        jQuery('.twitter_hoverbox').css('top', position.top+38);
                        jQuery('.twitter_hoverbox').css('background', 'red');
                        jQuery('.twitter_hoverbox').html('You reached the maximum allowed characters for Twitter, your message will be cutoff.');
                        jQuery('.twitter_hoverbox').show();
                        
                        jQuery('.twitter_share_counter').addClass('twitter_share_counter_red');
                }else{
                        jQuery('.twitter_share_counter').removeClass('twitter_share_counter_red');
                        jQuery('.twitter_hoverbox').hide(); 
                }
                
            }else{
                jQuery('.twitter_share_counter').removeClass('twitter_share_counter_red');
                jQuery('.twitter_share_counter').html('140');
                jQuery('.twitter_share_counterbox').hide();
                jQuery('.twitter_hoverbox').hide(); 
            }
        }