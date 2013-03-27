<div class="buddystreamAdminmenu">
    <a href="?page=buddystream_youtube" <?php if ($_GET['settings'] == "") {
        echo 'class="activetab"';
    }?>>General settings</a>
    <a href="?page=buddystream_youtube&settings=statitics" <?php if ($_GET['settings'] == "statitics") {
        echo 'class="activetab"';
    }?>>Statistics</a>
    <a href="?page=buddystream_youtube&settings=users" <?php if ($_GET['settings'] == "users") {
        echo 'class="activetab"';
    }?>>Users</a>
</div>