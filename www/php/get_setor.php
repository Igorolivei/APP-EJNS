<?php
include 'connect.php';
//$q = $_GET['q'];
// Protect against form submission variables.

try
{
 $sql = "SELECT * FROM setor";
 $result = $pdo->query($sql);
}
catch (Exception $e)
{
 echo 'Error fetching data: ' . $e->getMessage();
 exit();
} 
?>
<html>
<body>
<table border='1'>
		<tr>
			<th>Table ID</th>
			<th>Nome</th>
		</tr>
<? while ($row = $result->fetch()) 
{
 echo "<tr>";
 echo "<td>" . $row['id_setor'] . "</td>";
 echo "<td>" . $row['descr'] . "</td>";
 echo "</tr>";
 }
?>
</table>";
</html>