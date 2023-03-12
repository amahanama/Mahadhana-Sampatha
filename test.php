<?php
  $page_title = 'Edit panel';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(4);
?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
  $("#random_no").keydown(function(){
    $("#random_no").css("background-color", "#e7feff");
  });
  
  $("#random_no").keyup(function(){
    $("#txt_count").val($("#random_no").val().length+ " digits entered.");
	//alert($("#random_no").val().length);
  });
  
  $("#random_no").on("input", function() {
	  if (/^0/.test(this.value)) {
		this.value = this.value.replace(/^0/, "")
	  }
	})
});

	function cancelData(){
		  location.reload();
	}

</script>
<?php
  //Display all catgories.
  $panel = find_by_id('panel_member',(int)$_GET['id']);   
  $p_id=(int)$_GET['id'];
  $d_no=$_GET['d_no'];  
  $mode=$_GET['mode']; 
  $alocation_id=  $_GET['alocation_id']; 
  $pin_no=get_panel_input_val($d_no,$p_id, $mode);
  $mob=find_M_by_id('contact_number',(int)$_GET['id']);
  $home=find_HM_by_id('contact_number',(int)$_GET['id']);
  $url =$_SERVER['REQUEST_URI'];
    
if(isset($_POST['test'])){		 
	$member_name   = remove_junk($db->escape($_POST['member_name']));
	$designation   = remove_junk($db->escape($_POST['designation']));
	$nic   = remove_junk($db->escape($_POST['nic']));
	$work_place   = remove_junk($db->escape($_POST['work_place']));
	$email   = remove_junk($db->escape($_POST['email']));
	$photo   = $_FILES["choosefile"]["name"];//remove_junk($db->escape($_POST['choosefile']));	
	$status   = remove_junk($db->escape($_POST['status']));
	$home  = remove_junk($db->escape($_POST['hm_tp']));
	$mob  = remove_junk($db->escape($_POST['mobile_no']));
	date_default_timezone_set('Asia/Colombo');
	$current_date = (date('Y-m-d H:i:s'));
	$random_no=remove_junk($db->escape($_POST['random_no']));
	
		  
  if(empty($errors)){
		date_default_timezone_set('Asia/Kolkata');
		$log_time = date('Y-m-d h:i:sa');
		$user = current_user();			
		//$log_msg = "Panel Member input has been entered for panel member ".$p_id ." by " . remove_junk(ucfirst($user['name']))." at ".$log_time;
		 
		 //Delete existing record
		 
		$delete_sql = "DELETE FROM panel_inputs WHERE `draw_no`={$d_no} AND  `mode`='{$mode}' AND `panel_id`={$p_id}" ;
		$db->query($delete_sql);
  
        $sql = "INSERT INTO panel_inputs (";
        $sql .=" draw_no, mode, panel_id,pin_no, created_by, created_date";
        $sql .=") VALUES (";
        $sql .=" '{$d_no}','{$mode}',{$p_id},'{$random_no}','{$user['name']}','{$log_time}'";
        $sql .=");";
		

		
     if($db->query($sql)){	
		//wh_log($log_msg);		
       $session->msg("s", "Random Number has entered successfully.");
       //redirect('panel_inputs_list.php',false);
	   redirect('add_panel_input_form.php?id='.$alocation_id,false);
     } else {
       $session->msg("d", "Sorry! Failed to Update".$db->affected_rows());
       redirect('panel_inputs_list.php',false);
     }
	 
	
  } else {
    $session->msg("d", $errors);
    redirect('panel_inputs_list.php',false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
		 
   </div>
   <div class="col-md-12">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Add Random number for  <?php echo remove_junk(ucfirst($panel['member_name']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="test.php?id=<?php echo (int)$panel['id'];?>&d_no=<?php echo (int)$d_no;?>&mode=<?php echo$mode;?>&alocation_id=<?php echo$alocation_id; ?>">           		   
		    <div class="col-md-6">		
				<div class="form-group">
					<label for="member_name">Full Name</label>
					<input type="text" class="form-control" disabled name="member_name" value="<?php echo remove_junk(ucfirst($panel['member_name']));?>">
				</div>
				<div class="form-group">
					<label for="nic">NIC</label>
					<input type="text" class="form-control" disabled name="nic" value="<?php echo remove_junk(ucfirst($panel['nic']));?>">
				</div>  
				<div class="form-group">
				  <label for="hm_tp">Home Telephone No</label>
					 <input type="text" class="form-control" disabled name="hm_tp" value="<?php echo remove_junk(ucfirst($home['contact_number']));?>">
				</div>			
				<div class="form-group">
				  <label for="email">Email</label>
					 <input type="text" class="form-control" disabled name="email" value="<?php echo remove_junk(ucfirst($panel['email']));?>">
				</div>
				<div class="form-group">
						<label for="random_no">Random Number</label>
						<input type="text" name="random_no" id="random_no" style="font-weight:bold;font-size:20px;" maxlength="20" minlength="10"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" class="form-control" name ="random_no"  value="<?php echo $pin_no['pin_no'];?>" onkeypress="return isNumberKey(event)">
						<input type="text" name="txt_count" id="txt_count" disabled>
				</div>
			</div>
		
			<div class="col-md-6">
				<div class="form-group">
					<label for="designation">Current Designation</label>
					<input type="text" class="form-control" name ="designation" disabled value="<?php echo remove_junk(ucfirst($panel['designation']));?>">
				</div>
				<div class="form-group">
				  <label for="work_place">Working Place</label>
					 <input type="text" class="form-control" name="work_place" disabled value="<?php echo remove_junk(ucfirst($panel['work_place']));?>">
				</div>
				<div class="form-group">
				  <label for="mobile_no">Mobile No</label>
					 <input type="text" class="form-control" name="mobile_no" disabled value="<?php echo remove_junk(ucfirst($mob['contact_number']));?>">
				</div>
				<div class="form-group">
				   <label for="status">Status</label>
				  <select class="form-control" name="status" disabled>
					<option <?php if($panel['status'] === '1') echo 'selected="selected"';?> value="1"> Active </option>
					<option <?php if($panel['status'] === '0') echo 'selected="selected"';?> value="0">Deactive</option>
				  </select>
				</div>
			</div>		
		<div class="col-md-12">		
			<div class="form-group">
				<button type="submit" name="test" class="btn btn-primary" style="width:15%">Save</button>
				<button type="button" name="cancel" id="cancel" class="btn btn-primary" style="width:15%" onclick="cancelData()">Cancel</button>
			</div>
		</div>
           
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
