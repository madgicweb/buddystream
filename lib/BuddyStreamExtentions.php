<?php

/*
 * Class for loading extententions
 */

class BuddyStreamExtentions{
 
    /**
     * Include the core extentions files. 
     */
    function loadExtentions(){

        $handle = opendir(WP_PLUGIN_DIR . "/buddystream/extentions");
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (file_exists(WP_PLUGIN_DIR."/buddystream/extentions/" . $file . "/core.php")) {
                        include(WP_PLUGIN_DIR."/buddystream/extentions/" . $file . "/core.php");
                    }
                }
            }
        }
    }
    

    /**
    * Load the extentions configs
    * @return array
    */
    function getExtentionsConfigs(){

        $config = array();
        $handle = opendir(WP_PLUGIN_DIR . "/buddystream/extentions");
        
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (file_exists(WP_PLUGIN_DIR."/buddystream/extentions/" . $file . "/config.ini")) {
                        $config[] = parse_ini_file(WP_PLUGIN_DIR."/buddystream/extentions/" . $file . "/config.ini");
                    }
                }
            }
        }

        return $config;
    }
    

    /**
        * Page loader for extentions
        */

    function pageLoader($extention){

        $config = parse_ini_file(WP_PLUGIN_DIR."/buddystream/extentions/".$extention."/config.ini");

        if(!isset($_GET["settings"])){
            $page = ucfirst($config['defaultpage']);
        }else{
            $page = ucfirst($_GET["settings"]);
        }

        include WP_PLUGIN_DIR."/buddystream/extentions/".$extention."/templates/Admin".$page.".php";
    }

    /**
    * Userpage loader for extentions
    */

    function userPageLoader($extention, $page = 'settings'){

        global $bp;

        if ($bp->displayed_user->id != $bp->loggedin_user->id && $page != "album") {
                header('location:' . get_site_url());
        }

        add_action(
            'bp_template_title',
            'buddystream_'.$extention.'_'.$page.'_screen_title'
        );

        add_action(
            'bp_template_content',
            'buddystream_'.$extention.'_'.$page.'_screen_content'
        );

        bp_core_load_template(
            apply_filters(
                'bp_core_template_plugin',
                'members/single/plugins'
            )
        );
    }

    /**
    * Tabs loader for extentions
    */

    function tabLoader($extention){
        
        
        $tabs = '';
        $tabs .= '<div class="buddystream_Adminmenu">';
        if (file_exists(WP_PLUGIN_DIR."/buddystream/extentions/".$extention."/config.ini")) {

            $config = parse_ini_file(WP_PLUGIN_DIR."/buddystream/extentions/".$extention."/config.ini");
            $arrTabs = explode(",", $config['pages']);

            foreach($arrTabs as $tab){
                $tab = trim($tab);
                $class = "";

                if(isset($_GET['settings']) && $_GET['settings'] == $tab){
                    $class = 'class="activetab"';
                }elseif(!isset($_GET['settings'])){
                    if($config['defaultpage'] == $tab){
                        $class = 'class="activetab"';
                    }
                }
                $tabs.= '<a href="?page=buddystream_'.$extention.'&settings='.$tab.'" '.$class.'>'.__(ucfirst($tab),'buddystream_lang').'</a>';
            }     
        }
           
        $tabs.= '<a href="http://buddystream.net/manuals/'.$config['name'].'" target="_blanc" class="tab_manual">'.__(ucfirst('Setup manual'),'buddystream_lang').'</a>';
            $tabs.= '<a href="#" class="tab_v2">V'.BP_BUDDYSTREAM_VERSION.'</a>';
            
            $tabs.= '<span class="tab_description"><span id="tab_description_content"></span></span>';

        $tabs .='</div>';
        
 
        return $tabs;
    }
}