<?php include_once('includes/load.php'); ?>

<?php
$req_fields = array('username','password' );
validate_fields($req_fields);
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

if(empty($errors)){
  $user_id = authenticate($username, $password);
  if($user_id){
    //create session with id
     $session->login($user_id);
    //Update Sign in time
     updateLastLogIn($user_id);
	date_default_timezone_set('Asia/Kolkata');
	$log_time = date('Y-m-d h:i:sa');	
	$user = current_user();	
	
	$log_msg =  remove_junk(ucfirst($user['name']))." is succesfully Login to the Mahadhana Sampatha Digital Draw System at ".$log_time;					
	wh_log($log_msg);
     //$session->msg("s", "Welcome to Panel Member Registration System");
     redirect('admin.php',false);

  } else {
    $session->msg("d", "Sorry Username/Password incorrect.");
    redirect('index.php',false);
  }

} else {
   $session->msg("d", $errors);
   redirect('index.php',false);
}

?>
