<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
 
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
 <div class="col-md-12">
    <div class="panel">
      <div class="jumbotron text-center" style="background-color: #F0FFFF;font-family:Calibri;">
         <h1>Welcome</h1>
		 <h2>to</h2>
		 <h1>Mahadhana Sampatha</h1><br/>
         <h1 style="font-size:50px;">Digital Draw Application</h1>
		</div>
    </div>
 </div>
</div>
<?php include_once('layouts/footer.php'); ?>
