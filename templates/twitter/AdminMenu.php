
<div class="buddystreamAdminmenu">
    <a href="?page=buddystream_twitter" <? if($_GET['settings']==""){ echo 'class="activetab"'; }?>><?= __('General settings','buddystream_lang');?></a>
    <a href="?page=buddystream_twitter&settings=filters" <? if($_GET['settings']=="filters"){ echo 'class="activetab"'; }?>><?= __('Filters','buddystream_lang');?></a>
    <a href="?page=buddystream_twitter&settings=statitics" <? if($_GET['settings']=="statitics"){ echo 'class="activetab"'; }?>><?= __('Statistics','buddystream_lang');?></a>
    <a href="?page=buddystream_twitter&settings=users" <? if($_GET['settings']=="users"){ echo 'class="activetab"'; }?>><?= __('Users','buddystream_lang');?></a>
</div>