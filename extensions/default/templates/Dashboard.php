&nbsp;<?php include "TemplateHeader.php"; ?>

<br><br>
<div id="buddystream" class="container">

    <div class="span9">

        <?php include "AdminMenu.php"; ?>

        <blockquote class="pull-left">
            <p>    <?php _e('dashboard description', 'buddystream_lang'); ?></p>
        </blockquote>
    </div>

    <div class="span9">

        <table class="table table-striped">
            <thead>
            <tr>
                <th>  <?php _e('Latest news', 'buddystream_lang');?></th>
            </tr>
            </thead>
            <?php
            $maxItems = 5;
            $count = 0;

            $feedItems = fetch_feed('http://buddystream.net/feed/');
            if (is_wp_error($feedItems)) {
                echo "<tr><td>No newsitems found.</td></tr>";
            } else {
                foreach ($feedItems->get_items() as $feedItem) {
                    $count++;

                    if ($count <= $maxItems) {
                        echo '<tr><td><a href="' . $feedItem->get_permalink() . '" title="' . $feedItem->get_title() . '" target="_blanc">' . $feedItem->get_date('j-m-Y') . ' - ' . $feedItem->get_title() . '</a></td></tr>';
                    }
                }
            }
            ?>
        </table>
    </div>
</div>
