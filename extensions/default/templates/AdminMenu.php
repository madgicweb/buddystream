<div class="navbar navbar-inverse navbar-static-top">
    <div class="navbar-inner">
        <a class="brand" href="#"><img src="<?php echo BP_BUDDYSTREAM_URL . '/images/buddystream.png';?>"
                                       width="20"/></a>
        <ul class="nav">
            <li <?php if (!isset($_GET['settings'])) {
                echo 'class="active"';
            }?>>
                <a id="buddystream_admin" href="?page=buddystream_admin"> <i
                        class="icon-th-large"></i> <?php _e("Dashboard", "buddystream_lang");?></a>
            </li>

            <li <?php if (isset($_GET['settings']) && $_GET['settings'] == "powercentral") {
                echo 'class="active"';
            }?>>
                <a id="buddystream_powercentral" href="?page=buddystream_admin&settings=powercentral"><i
                        class="icon-off"></i> <?php _e("Powercentral", "buddystream_lang");?></a>
            </li>

            <li <?php if (isset($_GET['settings']) && $_GET['settings'] == "importcentral") {
                echo 'class="active"';
            }?>>
                <a id="buddystream_synccentral" href="?page=buddystream_admin&settings=synccentral"><i
                        class="icon-download"></i> <?php _e("Sync central", "buddystream_lang");?></a>
            </li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Settings <b
                        class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li <?php if (isset($_GET['settings']) && $_GET['settings'] == "general") {
                        echo 'class="active"';
                    }?>>
                        <a id="buddystream_general"
                           href="?page=buddystream_admin&settings=general"><?php _e("General settings", "buddystream_lang");?></a>
                    </li>
                    <li <?php if (isset($_GET['settings']) && $_GET['settings'] == "cronjob") {
                        echo 'class="active"';
                    }?>>
                        <a id="buddystream_cronjob"
                           href="?page=buddystream_admin&settings=cronjob"><?php _e("Cronjob settings", "buddystream_lang");?></a>
                    </li>
                </ul>
            </li>

            <li <?php if (isset($_GET['settings']) && $_GET['settings'] == "log") {
                echo 'class="active"';
            }?>>
                <a id="buddystream_log" href="?page=buddystream_admin&settings=log"><i
                        class="icon-list"></i> <?php _e("Logs ", "buddystream_lang");?></a>
            </li>
        </ul>
    </div>
</div>
<br><br>