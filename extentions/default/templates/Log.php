<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php include "AdminMenu.php"; ?>

<div class="buddystream_info_box">
<?php _e('buddystream log description','buddystream_lang'); ?></div>

 <table class="buddystream_table" cellspacing="0">
   <tr class="header">
       <td width="150"><?php _e('Date', 'buddystream_lang'); ?></td>
       <td><?php _e('Message', 'buddystream_lang'); ?></td>
  </tr>
  
  <?php
  
  global $wpdb;
  $class = "even";
  $logs = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."buddystream_log ORDER BY id DESC LIMIT 100");
  foreach ($logs as $log) {
    
      echo '
      <tr class="'.$class.'">
        <td>'.$log->date.'</td>
        <td class="buddystream_log_'.$log->type.'">'.$log->message.'</td>
      </tr>';
      
      
      if($class=="even"){$class="odd";}else{$class="even";}
 
  }
   ?>
  
</table>