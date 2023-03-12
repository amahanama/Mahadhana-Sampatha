<?php
  $page_title = 'Edit draw';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);

  //Display all catgories.
  $draw = find_by_id('draw',(int)$_GET['id']);
  $draw_date =$draw['draw_date'];
  $draw_no =$draw['draw_no'];
  $mode =$draw['mode'];
  $operator =$draw['operator'];
  $panel_1 =$draw['panel_1'];
  $panel_2 =$draw['panel_2'];
  $panel_3 =$draw['panel_3'];
  $panel_4 =$draw['panel_4'];
  $panel_5 =$draw['panel_5'];
  $pin_1 =$draw['pin_1'];
  $pin_2 =$draw['pin_2'];
  $pin_3 =$draw['pin_3'];
  $pin_4 =$draw['pin_4'];
  $pin_5 =$draw['pin_5'];  
  $auto_generated_pin =$draw['auto_generated_pin'];
  $winning_nos =$draw['winning_nos'];
  $english_letter =$draw['english_letter'];
  $seed =$draw['seed']; 

   if(!$draw){
    $session->msg("d","Missing draw id.");
    redirect('draw.php');
  } 
 ?>

<?php include_once('layouts/header.php');  
include_once('fpdf/fpdf.php');?>
 <?php 
	$draw_date =$draw['draw_date'];
  $draw_no =$draw['draw_no'];
  $mode =$draw['mode'];
  $operator =$draw['operator'];
  $panel_1 =$draw['panel_1'];
  $panel_2 =$draw['panel_2'];
  $panel_3 =$draw['panel_3'];
  $panel_4 =$draw['panel_4'];
  $panel_5 =$draw['panel_5'];
  
  $pin_1 =$draw['pin_1'];
  $pin_2 =$draw['pin_2'];
  $pin_3 =$draw['pin_3'];
  $pin_4 =$draw['pin_4'];
  $pin_5 =$draw['pin_5'];  
  
  $auto_generated_pin =$draw['auto_generated_pin'];
  $winning_nos =$draw['winning_nos'];
  $english_letter =$draw['english_letter'];
  $seed =$draw['seed'];
  
  $pane1_member_1 = find_by_id('panel_member',(int)$panel_1);//name,nic,designation
  $pane1_member_2 = find_by_id('panel_member',(int)$panel_2);
  $pane1_member_3 = find_by_id('panel_member',(int)$panel_3);
  $pane1_member_4 = find_by_id('panel_member',(int)$panel_4);
  $pane1_member_5 = find_by_id('panel_member',(int)$panel_5);
  
 /* echo "<h2>" .$pane1_member_1['name']. $pane1_member_1['nic']. "</h2>";
  
 */


 $pdf = new FPDF();
 $pdf->AddPage();
 $pdf->SetFont('Arial','B',16);
 $pdf->Cell(40,10,'Hello World!');
 $pdf->Output();

  
  
 ?>
 
 <!--
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
		 
   </div>
   <div class="col-md-12">
     <div class="draw draw-default">
       <div class="draw-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editing <?php echo remove_junk(ucfirst($draw['member_name']));?></span>
        </strong>
       </div>
       <div class="draw-body">
         <form method="post" action="edit_draw.php?id=<?php echo (int)$draw['id'];?>" enctype="multipart/form-data">
            
       </form>
       </div>
     </div>
   </div>
</div>

-->

<?php include_once('layouts/footer.php'); ?>
