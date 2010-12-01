
<div class="buddystreamAdminmenu">
    <a href="?page=buddystream_twitter" <?php if($_GET['settings']==""){ echo 'class="activetab"'; }?>><?php echo __('General settings','buddystream_lang');?></a>
    <a href="?page=buddystream_twitter&settings=filters" <?php if($_GET['settings']=="filters"){ echo 'class="activetab"'; }?>><?php echo __('Filters','buddystream_lang');?></a>
    <a href="?page=buddystream_twitter&settings=statitics" <?php if($_GET['settings']=="statitics"){ echo 'class="activetab"'; }?>><?php echo __('Statistics','buddystream_lang');?></a>
    <a href="?page=buddystream_twitter&settings=users" <?php if($_GET['settings']=="users"){ echo 'class="activetab"'; }?>><?php echo __('Users','buddystream_lang');?></a>
</div>