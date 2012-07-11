<?php

/*
 * Class for loading extententions
 */

class BuddyStreamExtensions{

    /**
     * Include the core extensions files.
     */
    function loadExtensions(){

        $handle = opendir(BP_BUDDYSTREAM_DIR."/extensions");
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && $file != ".DS_Store") {
                    if (file_exists(BP_BUDDYSTREAM_DIR."/extensions/" . $file . "/core.php")) {
                        include(BP_BUDDYSTREAM_DIR."/extensions/" . $file . "/core.php");
                    }
                }
            }
        }
    }

    /**
     * Check if extension exists
     * @param $name
     * @return bool
     */
    function extensionExist($name){
        if (file_exists(BP_BUDDYSTREAM_DIR."/extensions/" . strtolower($name) . "/core.php")) {
            return true;
        }

        return false;
    }

    /**
     * Get all extension with parent
     * @param $name
     * @return array
     */
    function getExtensionsWithParent($name){

        $extensions = array();

        $configs = $this->getExtensionsConfigs();

        foreach($configs as $config){
           if($config['parent']){
               if($config['parent'] == $name){
                   $extensions[] = $config;
               }
           }
        }

        return $extensions;
    }
    

    /**
    * Load the extensions configs
    * @return array
    */
    function getExtensionsConfigs(){

        $config = array();
        $handle = opendir(BP_BUDDYSTREAM_DIR."/extensions");
        
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && $file != ".DS_Store") {
                    if (file_exists(BP_BUDDYSTREAM_DIR."/extensions/" . $file . "/config.ini")) {
                        $config[] = parse_ini_file(BP_BUDDYSTREAM_DIR."/extensions/" . $file . "/config.ini");
                    }
                }
            }
        }

        return $config;
    }
    

    /**
        * Page loader for extensions
        */

    function pageLoader($extension){

        $config = parse_ini_file(BP_BUDDYSTREAM_DIR."/extensions/".$extension."/config.ini");

        if( ! isset($_GET["settings"])){
            $page = ucfirst($config['defaultpage']);
        }else{
            $page = ucfirst($_GET["settings"]);
        }

        if( $_GET['child'] ){
            $extension = $_GET['child'];
        }

        include BP_BUDDYSTREAM_DIR."/extensions/".$extension."/templates/Admin".$page.".php";
    }

    /**
    * Userpage loader for extensions
    */

    function userPageLoader($extension, $page = 'settings'){

        global $bp;

        if ($bp->displayed_user->id != $bp->loggedin_user->id && $page != "album") {
                header('location:' . get_site_url());
        }

        add_action(
            'bp_template_title',
            'buddystream_'.$extension.'_'.$page.'_screen_title'
        );

        add_action(
            'bp_template_content',
            'buddystream_'.$extension.'_'.$page.'_screen_content'
        );

        bp_core_load_template(
            apply_filters(
                'bp_core_template_plugin',
                'members/single/plugins'
            )
        );
    }

    /**
    * Tabs loader for extensions
    */

    function tabLoader($extension, $parent = false){


        $tabs = '';
        $tabs .= '<div class="buddystream_Adminmenu">';
        if (file_exists(BP_BUDDYSTREAM_DIR."/extensions/".$extension."/config.ini")) {

            $config = parse_ini_file(BP_BUDDYSTREAM_DIR."/extensions/".$extension."/config.ini");

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

                $tabs.= '<a href="?page=buddystream_'.$extension.'&settings='.$tab.'" '.$class.'>'.__(ucfirst(str_replace("_", " ", $tab)), 'buddystream_lang').'</a>';
            }


            //also find any other extension that has this extension as parent
            $childeren = $this->getExtensionsWithParent($extension);

            foreach($childeren as $child ){

                $arrTabs = explode(",", $child['pages']);
                foreach($arrTabs as $tab){

                    $tab = trim($tab);
                    $class = "";

                    if(isset($_GET['settings']) && $_GET['settings'] == $tab){
                        $class = 'class="activetab"';
                    }

                    $tabs.= '<a href="?page=buddystream_'.$extension.'&settings='.$tab.'&child='.$child['name'].'" '.$class.'>'.__(ucfirst(str_replace("_", " ", $tab)),'buddystream_lang').'</a>';
                }

            }

        }
           
        $tabs.= '<a href="http://buddystream.net/manuals/'.$config['name'].'" target="_blanc" class="tab_manual">'.__(ucfirst('Setup manual'),'buddystream_lang').'</a>';
            $tabs.= '<a href="#" class="tab_v2">V'.BP_BUDDYSTREAM_VERSION.'</a>';
            
            $tabs.= '<span class="tab_description"><span id="tab_description_content"></span></span>';

        $tabs .='</div>';


        return $tabs;
    }
}