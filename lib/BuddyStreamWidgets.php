<?php

/**
 * BuddyStream Conect Widget
 * Shows widget with direct icons or links to the social networks settings
 */

class BuddyStream_Connect_Widget extends WP_Widget
{

    public function __construct() {
        $widget_ops = array();
        $control_ops = array();
        return $this->WP_Widget('BuddyStream_Connect_Widget', 'BuddyStream Connect Widget');
    }

    public function form($instance) {

        $defaults = array();
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <p>
        <label for="<?php echo $this->get_field_id('widget_networks'); ?>"><?php _e('Show networks:','buddystream_lang');?></label>

            <?php

            $extentions = new BuddyStreamExtentions();
            $extentions = $extentions->getExtentionsConfigs();

            foreach($extentions as $extention){
                $checked = "";

                if($instance[$extention['name']])
                {
                    $checked = "checked";
                }
                echo '<p><input type="checkbox" name="'.$this->get_field_name($extention['name']).'" id="'.$this->get_field_id($extention['name']).'" value="'.$instance[$extention['name']].'" '.$checked.'> '. ucfirst($extention['displayname']) .'</p>';
            }
            ?>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('widget_title'); ?>"><?php _e('Widget title:','buddystream_lang');?></label>
            <input type="text" name="<?php echo $this->get_field_name('widget_title') ?>" id="<?php echo $this->get_field_id('widget_title') ?>" value="<?php echo $instance['widget_title'] ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('widget_description'); ?>"><?php _e('Widget description:','buddystream_lang');?></label>
            <textarea name="<?php echo $this->get_field_name('widget_description') ?>" id="<?php echo $this->get_field_id('widget_description') ?>" rows="8" cols="28"><?php echo $instance['widget_description'] ?></textarea>
        </p>

    <?
    }

    function update($new_instance, $old_instance){

        // used when the user saves their widget options
        $instance = $old_instance;
        $instance['widget_title'] = $new_instance['widget_title'];
        $instance['widget_description'] = $new_instance['widget_description'];

        $extentions = new BuddyStreamExtentions();
        $extentions = $extentions->getExtentionsConfigs();

        foreach($extentions as $extention){

           if(isset($new_instance[$extention['name']])){
                $instance[$extention['name']] = 1;
           }else{
               $instance[$extention['name']] = "";
           }
        }

        return $instance;
    }

    function widget($args, $instance) {

        global $bp;

        // used when the sidebar calls in the widget
        echo '
        <h3 class="widgetTitle">'.$instance['widget_title'].'</h3>
        <p>'.nl2br($instance['widget_description']).'</p>
        ';

        $extentions = new BuddyStreamExtentions();
        $extentions = $extentions->getExtentionsConfigs();

        foreach($extentions as $extention){

            if($instance[$extention['name']]){

                $link = $bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-networks/?network='.$extention['name'];
                if(!is_user_logged_in()){
                    $link = BP_REGISTER_SLUG;
                }

                echo '<a href="'. $link. '" class="buddystream_widget_icons admin_icon '.$extention['name'].'" title="'.__('Connect your','buddystream_lang').' ' . ucfirst($extention['displayname']) . ' ' . __('account ','buddystream_lang').'"></a>';
            }

        }

    }
}