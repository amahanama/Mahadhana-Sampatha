<?php
  
	require_once('includes/load.php');

		 
		$mode               = $_POST['mode'];	
		$lottery_name       = $_POST['lottery-name'];
		$draw_date          = $_POST['draw_date'];
		$draw_no            = $_POST['draw_no'];
		$operator           = $_POST['operator'];
	   	   
		$panel_1   =  $_POST['Panel_1'];
		$panel_2   =  $_POST['Panel_2'];
		$panel_3   =  $_POST['Panel_3'];
		$panel_4   =  $_POST['Panel_4'];
		$panel_5   =  $_POST['Panel_5'];
		
		$id=$_POST['form_id'];
				 

		$query  = "UPDATE draw_allocation SET ";
		$query .= "draw_no='{$draw_no}',mode='{$mode}',draw_date='{$draw_date}', ";
		$query .= "operator='{$operator}',panel_1='{$panel_1}',panel_2='{$panel_2}', ";
		$query .= "panel_3='{$panel_3}',panel_4='{$panel_4}',panel_5='{$panel_5}' ";
		$query .= "WHERE id='{$db->escape($id)}'";
		
		
				
        if($db->query($query)){			
          //sucess
          $session->msg('s',"Allocation has been updated! ");
          redirect('panel_alocation_list.php', false);
		  header("refresh: 3");
        } else {
          //failed
          $session->msg('d',' Sorry failed to update allocation!');
          redirect('update_allocation.php', false);
        }
   /*} else {
     $session->msg("d", $errors);
      redirect('assign_draw.php',false);
   }*/
 //}
?>
