
<div class="buddystreamAdminmenu">
    <a href="?page=buddystream_facebook" <?php if($_GET['settings']==""){ echo 'class="activetab"'; }?>>General settings</a>
    <a href="?page=buddystream_facebook&settings=filters" <?php if($_GET['settings']=="filters"){ echo 'class="activetab"'; }?>>Filters</a>
    <a href="?page=buddystream_facebook&settings=statitics" <?php if($_GET['settings']=="statitics"){ echo 'class="activetab"'; }?>>Statistics</a>
    <a href="?page=buddystream_facebook&settings=users" <?php if($_GET['settings']=="users"){ echo 'class="activetab"'; }?>>Users</a>
</div>