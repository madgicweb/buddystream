<div id="buddystream" class="container">

    <?php
        if (!isset($_GET['network'])) {
            echo "<h3>" . __('Social networks', 'buddystream_lang') . "</h3>";
            echo __('Social networks setup description', 'buddystream_lang') . "<br/><br/>";
        }
    ?>

            <ul class="nav">
                <?php
                    //get the active
                    $buddyStreamExtension = new BuddyStreamExtensions();
                    foreach ($buddyStreamExtension->getExtensionsConfigs() as $extension) {
                        if (get_site_option('buddystream_' . $extension['name'] . '_power') == "on" && get_site_option('buddystream_' . $extension['name'] . '_setup') && !$extension['parent']) {
                            echo '<li><a href="?network=' . $extension['name'] . '" id="' . ucfirst($extension['displayname']) . '">' . ucfirst($extension['name']) . '</a></li>';
                            $activeExtensions[] = $extension['name'];
                        }
                    }
                ?>
            </ul>

    <br/>

    <?php
        if (isset($_GET['network'])) {
            include(BP_BUDDYSTREAM_DIR . "/extensions/" . $_GET['network'] . "/templates/UserSettings.php");
        }
    ?>

</div>