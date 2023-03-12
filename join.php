<?php

require_once('includes/load.php');
	
	$draw_no       =$_POST['draw_no'];
	$draw_date     =$_POST['draw_date'];
	$rand_no       =$_POST['rand_no'];
	$mode          =$_POST['mode'];
	$operator      =$_POST['operator'];
	$panel_1       =$_POST['panel_1'];
	$panel_2       =$_POST['panel_2'];
	$panel_3       =$_POST['panel_3'];
	$panel_4       =$_POST['panel_4'];
	$panel_5       =$_POST['panel_5'];
	$pin_1         =$_POST['pin_1'];
	$pin_2         =$_POST['pin_2'];
	$pin_3         =$_POST['pin_3'];
	$pin_4         =$_POST['pin_4'];
	$pin_5         =$_POST['pin_5'];
	$winnings      =$_POST['winnings'];
	$eng_letter    =$_POST['eng_letter'];
	$hash_val      =$_POST['hash_val'];
	
	
	/**	
	INSERT INTO `draw`(`mode`, `operator`, `panel_1`, `panel_2`, `panel_3`, `panel_4`, `panel_5`, `pin_1`, `pin_2`, `pin_3`, `pin_4`, `pin_5`, `auto_generated_pin`, `winning_nos`, `english_letter`, `seed`, `created_by`, `created_date`) VALUES ()
					
					**/
		
	$query = "INSERT INTO `draw`(`draw_no`, `draw_date`, `mode`, `operator`, `panel_1`, `panel_2`, `panel_3`, `panel_4`, `panel_5`, `pin_1`, `pin_2`, `pin_3`, `pin_4`, `pin_5`, `auto_generated_pin`, `winning_nos`, `english_letter`, `seed`, `created_by`) ";
	$query .= "VALUES ('{$draw_no}', '{$draw_date}','{$mode}','{$operator}','{$panel_1}','{$panel_2}','{$panel_3}','{$panel_4}','{$panel_5}','{$pin_1}','{$pin_2}','{$pin_3}','{$pin_4}','{$pin_5}','{$rand_no}','{$winnings}','{$eng_letter}','{$hash_val}','{$operator}')";
	//console.log("Query :".json_decode($query));
	
	
		if($db->query($query)){			
          //sucess
          $session->msg('s',"Draw Data has been saved successfully! ");
          redirect('panel_alocation_list.php', false);
		  header("refresh: 3");
        } else {
          //failed
          $session->msg('d',' Sorry failed to save Draw Data!');
          redirect('update_allocation.php', false);
        }
	

?>