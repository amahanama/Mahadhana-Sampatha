 <?php
$mysqli = new mysqli("localhost", "root", "", "mahadana_sampatha");
if($mysqli->connect_error) {
  exit('Could not connect');
}

$sql = "SELECT MAX(draw_no) from draw WHERE mode='TEST';";

$stmt = $mysqli->prepare($sql);
//$stmt->bind_param("s", $_GET['str']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($draw_no);
$stmt->fetch();
$stmt->close();

echo "<table>";
echo "<tr>";
echo "<th>CustomerID</th>";
echo "<td>" . $draw_no . "</td>";
echo "</tr>";
echo "</table>";
?>