<div class="buddystream_Adminmenu">
    <a id="buddystream_admin" href="?page=buddystream_admin" <?php if(!isset($_GET['settings'])){ echo 'class="activetab"'; }?>><?php _e("Dashboard","buddystream_lang");?></a>
    <a id="buddystream_powercentral" href="?page=buddystream_admin&settings=powercentral" <?php if(isset($_GET['settings']) && $_GET['settings']=="powercentral"){ echo 'class="activetab"'; }?>><?php _e("Powercentral","buddystream_lang");?></a>     
    <a id="buddystream_general" href="?page=buddystream_admin&settings=general" <?php if(isset($_GET['settings']) && $_GET['settings']=="general"){ echo 'class="activetab"'; }?>><?php _e("General settings","buddystream_lang");?></a>     
    <a id="buddystream_cronjob" href="?page=buddystream_admin&settings=cronjob" <?php if(isset($_GET['settings']) && $_GET['settings']=="cronjob"){ echo 'class="activetab"'; }?>><?php _e("Cronjob settings","buddystream_lang");?></a>     
    <a id="buddystream_log" href="?page=buddystream_admin&settings=log" <?php if(isset($_GET['settings']) && $_GET['settings']=="log"){ echo 'class="activetab"'; }?>><?php _e("Logs ","buddystream_lang");?></a>     
    <a href="#" class="tab_v2">V<?php echo BP_BUDDYSTREAM_VERSION;?></a>     
   
    <?php if(get_bloginfo('version') > '3.2'): ?>
    <a href="?page=buddystream_admin&action=starttour" class="tab_tour">Start tour!</a>
    <?php endif; ?> 
</div>