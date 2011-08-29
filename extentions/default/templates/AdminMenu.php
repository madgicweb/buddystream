<div class="buddystream_Adminmenu">
    <a href="?page=buddystream_admin" <?php if($_GET['settings']==""){ echo 'class="activetab"'; }?>><?php _e("Dashboard","buddystream_lang");?></a>
    <a href="?page=buddystream_admin&settings=powercentral" <?php if($_GET['settings']=="powercentral"){ echo 'class="activetab"'; }?>><?php _e("Powercentral","buddystream_lang");?></a>     
    <a href="?page=buddystream_admin&settings=cronjob" <?php if($_GET['settings']=="cronjob"){ echo 'class="activetab"'; }?>><?php _e("Cronjob settings","buddystream_lang");?></a>     
    <a href="?page=buddystream_admin&settings=log" <?php if($_GET['settings']=="log"){ echo 'class="activetab"'; }?>><?php _e("Logs ","buddystream_lang");?></a>     
    <a href="?page=buddystream_admin&settings=version2" class="tab_v2">V2.1.2</a>     
</div>