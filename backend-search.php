<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
require_once("includes/config.php");
//require_once('includes/load.php');
//$link = mysqli_connect("localhost", "root", "", "mahadana_sampatha");

$link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

//SELECT MAX(draw_no) FROM `draw` WHERE mode='TEST';
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
if(isset($_REQUEST["term"])){
    // Prepare a select statement
	$search_key=$_REQUEST["term"];
    $sql = "SELECT p.id, p.member_name, p.designation, p.nic, p.work_place FROM panel_member p WHERE p.member_name LIKE ? ";
	$sql1 = "SELECT p.id, p.member_name, p.designation, p.nic, p.work_place FROM panel_member p WHERE p.nic LIKE ? ";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_term);
        
        // Set parameters
        $param_term = '%'.$_REQUEST["term"] . '%';
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            // Check number of rows in the result set
            if(mysqli_num_rows($result) > 0){
                // Fetch result rows as an associative array
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    echo "<p>" .$row["id"]."; " .$row["member_name"]. "; <br/> ". $row["nic"] .";<br/> ". $row["work_place"] .";<br/>". $row["designation"] .";<br/></p>";
                }
            } else{
				if($stmt = mysqli_prepare($link, $sql1)){
					// Bind variables to the prepared statement as parameters
					mysqli_stmt_bind_param($stmt, "s", $param_term);
					
					// Set parameters
					$param_term = '%'.$_REQUEST["term"] . '%';
        
					// Attempt to execute the prepared statement
					if(mysqli_stmt_execute($stmt)){
						$result = mysqli_stmt_get_result($stmt);
            
					// Check number of rows in the result set
					if(mysqli_num_rows($result) > 0){
						// Fetch result rows as an associative array
						while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
							echo "<p>" .$row["id"]." ; " .$row["member_name"]. "; <br/> ". $row["nic"] .";<br/> ". $row["work_place"] .";<br/>". $row["designation"] .";<br/></p>";
						}
					} else{
						echo "<p>No matches found</p>";
					}
					}
				}
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
}
 
// close connection
mysqli_close($link);
?>