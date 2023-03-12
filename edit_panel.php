<?php
  $page_title = 'Edit panel';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  //Display all catgories.
  $panel = find_by_id('panel_member',(int)$_GET['id']);
  //$panel = findPanelById((int)$_GET['id']);  
  
  $id=(int)$_GET['id'];
  $mob=find_M_by_id('contact_number',(int)$_GET['id']);
  $home=find_HM_by_id('contact_number',(int)$_GET['id']);
    
	
  if(!$panel){
    $session->msg("d","Missing panel id.");
    redirect('panel.php');
  }
  
  
?>

<?php
if(isset($_POST['edit_panel'])){	
	 
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
	
	
		
  
  if(empty($errors)){
								
	    $sql = " UPDATE panel_member p ";
		$sql .= " LEFT JOIN contact_number c ON c.panel_member_id = p.id";
	    $sql .= " LEFT JOIN contact_number d ON d.panel_member_id = p.id";
		$sql .= " SET p.member_name='{$member_name}', p.designation='{$designation}', p.nic='{$nic}', ";
        $sql .= " p.work_place='{$work_place}', p.email='{$email}',p.status='{$status}', p.updated='{$current_date}',";
		
		if($photo != "")
			$sql .= "p.photo='{$photo}',";
		
		$sql .= " c.contact_number='{$home}',d.contact_number='{$mob}'";	
		$sql .= " WHERE p.id='{$panel['id']}' ";		
		$sql .= " AND c.contact_type= 'home' ";			
		$sql .= " AND d.contact_type= 'mobile' ";
	  
        $result = $db->query($sql);
		
		echo $sql;
		
     if($result && $db->affected_rows() > 0) {
		 
		 /**
		Upload Image File
		**/		
		$filename = $_FILES["choosefile"]["name"];
		$tempname = $_FILES["choosefile"]["tmp_name"];  
        $folder = "uploads/".$filename;
		
		 // Add the image to the "image" folder"

        if (move_uploaded_file($tempname, $folder)) {
            $msg = "Image uploaded successfully";
        }else{
            $msg = "Failed to upload image";
		}
		
		date_default_timezone_set('Asia/Kolkata');
		$log_time = date('Y-m-d h:i:sa');
		$user = current_user();			
		$log_msg = "Panel Member has been updated with NIC NO ".$nic ." by " . remove_junk(ucfirst($user['name']))." at ".$log_time;
		
		wh_log($log_msg);
		
       $session->msg("s", "Successfully updated panel with NIC NO ".$nic);
       redirect('panel.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update".$db->affected_rows());
       redirect('panel.php',false);
     }
	 
	
  } else {
    $session->msg("d", $errors);
    redirect('panel.php',false);
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
           <span>Editing <?php echo remove_junk(ucfirst($panel['member_name']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_panel.php?id=<?php echo (int)$panel['id'];?>" enctype="multipart/form-data">
           		   
		     <div class="col-md-6">
			 
		  
            <div class="form-group">
                <label for="member_name">Full Name</label>
                <input type="text" class="form-control" name="member_name" value="<?php echo remove_junk(ucfirst($panel['member_name']));?>">
            </div>
            <div class="form-group">
                <label for="nic">NIC</label>
                <input type="text" class="form-control" name="nic" value="<?php echo remove_junk(ucfirst($panel['nic']));?>">
            </div>
            
            
			<div class="form-group">
              <label for="hm_tp">Home Telephone No</label>
                 <input type="text" class="form-control" name="hm_tp" value="<?php echo remove_junk(ucfirst($home['contact_number']));?>" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;">
            </div>
			
			<div class="form-group">
              <label for="email">Email</label>
                 <input type="text" class="form-control" name="email" value="<?php echo remove_junk(ucfirst($panel['email']));?>">
            </div>
			<div class="form-group">
				<label for="photo">Profile Image</label>				
				<input type="file" name="choosefile" value="<?php echo remove_junk(ucfirst($panel['photo']));?>" style=" border: 1px solid blue;backgroundColor:gray;" />
				
				<!-------------------------------------------------------------- VIEW UPLOADED IMAGES ------------------------------>
				<?php
					//get current directory
					$working_dir = getcwd();
					
					//get image directory
					$img_dir = $working_dir."/uploads/";
					
					//change current directory to image directory
					chdir($img_dir);
					
					//using glob() function get images 
					$files = glob("*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}", GLOB_BRACE );
					
					//again change the directory to working directory
					chdir($working_dir);
					?>
				<img src="<?php echo "uploads/" . $panel['photo'] ?>" style="height: 150px; width: 150px;"/>
	
				<!----------------------------------------------------------------------------------------------------------------------->
            </div>
            <div class="form-group">
			<button type="submit" name="edit_panel" class="btn btn-primary">Update panel</button>
			</div>

        </div>
		
		 <div class="col-md-6">
            <div class="form-group">
                <label for="designation">Current Designation</label>
                <input type="text" class="form-control" name ="designation"  value="<?php echo remove_junk(ucfirst($panel['designation']));?>">
            </div>
            <div class="form-group">
              <label for="work_place">Working Place</label>
                 <input type="text" class="form-control" name="work_place" value="<?php echo remove_junk(ucfirst($panel['work_place']));?>">
            </div>
			<div class="form-group">
              <label for="mobile_no">Mobile No</label>
                 <input type="text" class="form-control" name="mobile_no" value="<?php echo remove_junk(ucfirst($mob['contact_number']));?>" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;">
            </div>
			<div class="form-group">
			   <label for="status">Status</label>
              <select class="form-control" name="status">
                <option <?php if($panel['status'] === '1') echo 'selected="selected"';?> value="1"> Active </option>
                <option <?php if($panel['status'] === '0') echo 'selected="selected"';?> value="0">Deactive</option>
              </select>
            </div>
        </div>		   
           
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
