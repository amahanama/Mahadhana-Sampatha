<?php
  $page_title = 'Add Lottery';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
  if(isset($_POST['add'])){

   $req_fields = array('lottery-name');
   validate_fields($req_fields);

   if(find_by_groupName($_POST['lottery-name']) === false ){
     $session->msg('d','<b>Sorry!</b> Entered Lottery Name already in database!');
     redirect('add_lottery.php', false);
   }
   
   
   if(empty($errors)){
         $name = remove_junk($db->escape($_POST['lottery-name']));         
         $status = remove_junk($db->escape($_POST['status']));

        $query  = "INSERT INTO lotteries (";
        $query .="lottery_name,status";
        $query .=") VALUES (";
        $query .=" '{$name}','{$status}'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s',"Lottery has been created! ");
          redirect('add_lottery.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry failed to create Lottery!');
          redirect('add_lottery.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('add_lottery.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h3>Add New Lottery</h3>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="add_lottery.php" class="clearfix">
        <div class="form-group">
              <label for="name" class="control-label">Lottery Name</label>
              <input type="name" class="form-control" name="lottery-name">
        </div>
       
        <div class="form-group">
          <label for="status">Status</label>
            <select class="form-control" name="status">
              <option value="1">Active</option>
              <option value="0">Deactive</option>
            </select>
        </div>
        <div class="form-group clearfix">
                <button type="submit" name="add" class="btn btn-info">Add Lottery</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>
