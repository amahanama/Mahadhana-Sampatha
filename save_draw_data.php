<?php 
		require_once('includes/load.php');
	/*
	
		$mode               = $_POST['mode'];	
	    $lottery_name       = $_POST['lottery-name'];
	    $draw_date          = $_POST['draw_date'];
	    $draw_no            = $_POST['draw_no'];
	    $operator           = $_POST['operator'];
	     
	   
	   $panel_1   =  $_POST['Panel_1'];
	   $panel_2   =  $_POST['Panel_2'];
	   $panel_3   =  $_POST['Panel_3'];
	   $panel_4   =  $_POST['Panel_4'];
	   $panel_5   =  $_POST['Panel_5'];*/
	  // $data = $_POST['data'];
	   //json_decode($data);
	   
	   $mode               = $_POST['mode'];		   
	   $draw_date          = $_POST['draw_date'];
	   $draw_no            = $_POST['draw_no'];
	   $operator           = $_POST['operator'];
	   $auto_generated_pin = $_POST['auto_generated_pin'];	   
	   $panel_1=$panel_2=$panel_3=$panel_4=$panel_5=NULL;
	   $pin_1=$pin_2=$pin_3=$pin_4=$pin_5=NULL;
	   
	   	   
	   
	   if($mode == "TEST"){
		   
		   $panel_1   = $tp1['id'];		   
		   $panel_2   = $tp2['id'];
		   $panel_3   = $tp3['id'];
		   $panel_4   = $tp4['id'];
		   $panel_5   = $tp5['id'];	   
		   
		   
		   $pin_1   = $test_draw_inputs[0]['pin_no'];
		   $pin_2   = $test_draw_inputs[1]['pin_no'];
		   $pin_3   = $test_draw_inputs[2]['pin_no'];
		   $pin_4   = $test_draw_inputs[3]['pin_no'];
		   $pin_5   = $test_draw_inputs[4]['pin_no']; 
		   
		    
	   }else if($mode == "LIVE"){
		   $panel_1   = $lp1['id'];
		   $panel_2   = $lp2['id'];
		   $panel_3   = $lp3['id'];
		   $panel_4   = $lp4['id'];
		   $panel_5   = $lp5['id'];
		   
		   		   
		   $pin_1= $live_draw_inputs[0]['pin_no'];
		   $pin_2= $live_draw_inputs[1]['pin_no'];
		   $pin_3= $live_draw_inputs[2]['pin_no'];
		   $pin_4= $live_draw_inputs[3]['pin_no'];
		   $pin_5= $live_draw_inputs[4]['pin_no'];
	   }
	   
	   
	   
	   $win_1   = $_POST['win_no_1'];
	   $win_2   = $_POST['win_no_2'];
	   $win_3   = $_POST['win_no_3'];
	   $win_4   = $_POST['win_no_4'];
	   $win_5   = $_POST['win_no_5'];
	   $win_6   = $_POST['win_no_6'];
	   $win_eng = $_POST['win_eng']; 
		 
		   
		$query  = "INSERT INTO `draw`(";
        $query .="  `draw_date`, `draw_no`, `mode`, `operator`, `panel_1`, `panel_2`, `panel_3`, `panel_4`, `panel_5`, `pin_1`, `pin_2`, `pin_3`, `pin_4`, `pin_5`, `auto_generated_pin`, `winning_nos`, `english_letter`, `seed`, `created_by`";
        $query .=") VALUES (";
        $query .=" '{$draw_date}','{$draw_no}','{$mode}','{$operator}','{$panel_1}','{$panel_2}','{$panel_3}','{$panel_4}','{$panel_5}','{$pin_1}','{$pin_2}','{$pin_3}','{$pin_4}','{$pin_5}','{$auto_generated_pin}','{$winning_nos}', '{$english_letter}', '{$seed}','{$created_by}'";
        $query .=")";
		
		
		





/*
        $query  = "INSERT INTO `draw_allocation`";
        $query .=" (`draw_no`, `mode`, `draw_date`, `operator`,`panel_1`, `panel_2`, `panel_3`, `panel_4`, `panel_5`, `created_by`) ";
        $query .=" VALUES (";
        $query .=" '{$draw_no}', '{$mode}','{$draw_date}','{$operator}','{$panel_1}','{$panel_2}','{$panel_3}','{$panel_4}','{$panel_5}','ANURUDDHIKA'";
        $query .=")";*/
		console.log("Query :".json_decode($data));
		
        if($db->query($query)){
          //sucess
          $session->msg('s',"Panel has been assigned for11111111 ".$data);
          redirect('test_draw.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry failed to assign panel!');
          redirect('assign_draw.php', false);
        }
  
?>
