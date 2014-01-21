<?php

/**
 * BuddyStream Connect Widget
 * Shows widget with direct icons or links to the social networks settings
 */

class BuddyStream_Connect_Widget extends WP_Widget
{

    /**
     * Widget constructor
     */
    public function __construct()
    {
        return $this->WP_Widget('buddyStream_connect_widget', 'BuddyStream Connect Widget');
    }


    /**
     * Widget form
     * @param array $instance
     * @return string|void
     */
    public function form($instance)
    {
        $defaults = array();
        $instance = wp_parse_args((array)$instance, $defaults); ?>

    <p>
        <label
            for="<?php echo $this->get_field_id('widget_networks'); ?>"><?php _e('Show networks:', 'buddystream_lang'); ?></label>

        <?php
        $extensions = new BuddyStreamExtensions();
        $extensions = $extensions->getExtensionsConfigs();

        foreach ($extensions as $extension) {

            if(get_site_option('buddystream_'.$extension['name'].'_power')){

                if( ! $extension["parent"]){
                    $checked = "";
                    if ($instance[$extension['name']]) {
                        $checked = "checked";
                    }
                    echo '<p><input type="checkbox" name="' . $this->get_field_name($extension['name']) . '" id="' . $this->get_field_id($extension['name']) . '" value="' . $instance[$extension['name']] . '" ' . $checked . '> ' . ucfirst($extension['displayname']) . '</p>';
                }
            }
        }
        ?>
    </p>

    <p>
        <label
            for="<?php echo $this->get_field_id('widget_title'); ?>"><?php _e('Widget title:', 'buddystream_lang');?></label>
        <input type="text" name="<?php echo $this->get_field_name('widget_title') ?>"
               id="<?php echo $this->get_field_id('widget_title') ?>" value="<?php echo $instance['widget_title'] ?>" />
    </p>
    <p>
        <label
            for="<?php echo $this->get_field_id('widget_description'); ?>"><?php _e('Widget description:', 'buddystream_lang');?></label>
        <textarea name="<?php echo $this->get_field_name('widget_description') ?>"
                  id="<?php echo $this->get_field_id('widget_description') ?>" rows="8"
                  cols="28"><?php echo $instance['widget_description'] ?></textarea>
    </p>

    <?php
    }


    /**
     * Update widget
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    function update($new_instance, $old_instance)
    {

        // used when the user saves their widget options
        $instance = $old_instance;
        $instance['widget_title'] = $new_instance['widget_title'];
        $instance['widget_description'] = $new_instance['widget_description'];

        $extensions = new BuddyStreamExtensions();
        $extensions = $extensions->getExtensionsConfigs();

        foreach ($extensions as $extension) {

            if (isset($new_instance[$extension['name']])) {
                $instance[$extension['name']] = 1;
            } else {
                $instance[$extension['name']] = "";
            }
        }

        return $instance;
    }


    /**
     * Display widget
     * @param array $args
     * @param array $instance
     */
    function widget($args, $instance)
    {
        global $bp;

        extract($args, EXTR_SKIP);

        // used when the sidebar calls in the widget
        echo $before_widget;
        echo $before_title . $instance['widget_title']  . $after_title;

        echo '<p>' . nl2br($instance['widget_description']) . '</p>';

        $extensions = new BuddyStreamExtensions();
        $extensions = $extensions->getExtensionsConfigs();

        foreach ($extensions as $extension) {

            if( get_site_option('buddystream_'.$extension['name'].'_power')){
                if ($instance[$extension['name']]) {

                    $link = $bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=' . $extension['name'];
                    if (!is_user_logged_in()) {
                        $link = BP_REGISTER_SLUG;
                    }

                    echo '<a href="' . $link . '" class="buddystream_widget_icons admin_icon ' . $extension['name'] . '" title="' . __('Connect your', 'buddystream_lang') . ' ' . ucfirst($extension['displayname']) . ' ' . __('account ', 'buddystream_lang') . '"></a>';
                }
            }
        }

        echo $after_widget;
    }
}