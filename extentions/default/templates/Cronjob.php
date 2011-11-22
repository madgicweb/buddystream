<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css"/>
<?php include "AdminMenu.php"; 

    //check if the licensekey is still valid
    if(!$_POST && get_site_option('buddystream_license_key')){
        $response = buddystreamCheckLicense(get_site_option('buddystream_license_key'));
        
        if($response != false){
            //expired
            if($response->code == "EXPIRED"){
                delete_site_option("buddystream_cronservices_uniquekey");
                echo '<div class="buddystream_error_box">'.$response->message.'</div>';
            }
            //something is wrong
            if($response->code == "ERROR"){
                delete_site_option("buddystream_cronservices_uniquekey");
                delete_site_option("buddystream_license_key");
                echo '<div class="buddystream_error_box">'.$response->message.'</div>';
            }

            //check ok
            if($response->code == "OK"){
                //save the uniquekey
                update_site_option("buddystream_cronservices_uniquekey", $reponse->uniqueKey);       
            }
        }else{
            echo '<div class="buddystream_error_box">Something went wrong when tying to check the license key.</div>';
        }
    }
    
    //check the licensekey on the buddystream servers
    if($_POST){        
        if(!empty($_POST['buddystream_license_key'])){
            $response = buddystreamCheckLicense($_POST['buddystream_license_key']);
            
            if($response != false){
                //expired
                if($response->code == "EXPIRED"){
                    delete_site_option("buddystream_cronservices_uniquekey");
                    delete_site_option("buddystream_license_key");
                    echo '<div class="buddystream_error_box">'.$response->message.'</div>';
                }

                //something is wrong
                if($response->code == "ERROR"){
                    delete_site_option("buddystream_cronservices_uniquekey");
                    delete_site_option("buddystream_license_key");
                    echo '<div class="buddystream_error_box">'.$response->message.'</div>';
                }

                //check ok
                if($response->code == "OK"){
                    //save the uniquekey
                    update_site_option("buddystream_cronservices_uniquekey", $reponse->uniqueKey);       
                    update_site_option("buddystream_license_key", $_POST['buddystream_license_key']);       
                    echo '<div class="buddystream_info_box_green">'.__("Cronservice sucessfully configured.","buddystream_lang").'</div>';
                }
            
            }else{
            echo '<div class="buddystream_error_box">Something went wrong when tying to check the license key.</div>';
        }
            
        }else{
            delete_site_option("buddystream_cronservices_uniquekey");
            delete_site_option("buddystream_license_key");
        }
    }
    ?>

    <div class="buddystream_info_box">       
    <?php _e('cronjob description','buddystream_lang'); ?> 
    </div>

    <form method="post" action="">
          <table class="buddystream_table" cellspacing="0">

            <?php if(!get_site_option("buddystream_license_key")): ?>  
                 <tr class="header">
                    <td colspan="2"><?php _e('Own cron','buddystream_twitter');?></td>
                 </tr>

                 <tr>
                    <td><?php _e('Cron command:', 'buddystream_twitter');?></td>
                    <td><input type="text" name="cronurl" value="<?php echo "*/10 * * * * wget " . plugins_url() . "/buddystream/import.php -O /dev/null -q";?>" size="150" /></td>
                 </tr>
            <?php endif; ?>
                 
            <tr class="header">
                <td colspan="2"><?php _e('BuddyStream Cron Service','buddystream_twitter');?></td>
            </tr>

            <tr>
                <td><?php _e('License key', 'buddystream_twitter');?></td>
                <td><input type="text" name="buddystream_license_key" value="<?php echo get_site_option("buddystream_license_key");?>" size="150" /></td>
            </tr>

        </table>
       <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /></p>
    </form>