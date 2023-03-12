<?php 
// Include the database config file 
require_once('includes/load.php');
 
if(!empty($_POST["mode"])){ 
    // Fetch state data based on the specific country 
	$sql  = " SELECT  panel_1, panel_2, panel_3, panel_4, panel_5 ";
    $sql .= " FROM draw_allocation ";
	$sql .= " WHERE  draw_no=(SELECT MAX(draw_no) FROM draw_allocation) AND mode='".$_POST['mode']."' AND status='Y'";
	
	
	$result = $db->query($sql);
		
    if($result && $db->affected_rows() > 0){
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['panel_1'].'">'.$row['panel_1'].'</option>'; 
			echo findPanelById($row['panel_1']);
        } 
    }else{ 
        echo '<option value="">State not available'.$sql.'</option>'; 
    } 
}elseif(!empty($_POST["state_id"])){ 
    // Fetch city data based on the specific state 
    $query = "SELECT * FROM cities WHERE state_id = ".$_POST['state_id']." AND status = 1 ORDER BY city_name ASC"; 
    $result = $db->query($query); 
     
    // Generate HTML of city options list 
    if($result->num_rows > 0){ 
        echo '<option value="">Select city</option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['city_id'].'">'.$row['city_name'].'</option>'; 
        } 
    }else{ 
        echo '<option value="">City not available</option>'; 
    } 
} 
?>

