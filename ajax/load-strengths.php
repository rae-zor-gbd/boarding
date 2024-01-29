<?php
include '../assets/config.php';
if (isset($_POST['medName'])) {
  $medName=mysqli_real_escape_string($conn, trim($_POST['medName']));
  $sql_strengths="SELECT CONCAT(strength, IF(unit='%', '', ' '), unit) AS strength FROM medications m JOIN medications_strengths s USING (medID) WHERE medName='$medName' ORDER BY s.unit, s.strength";
  $result_strengths=$conn->query($sql_strengths);
  while ($row_strengths=$result_strengths->fetch_assoc()) {
    $strength=strtoupper(htmlspecialchars($row_strengths['strength'], ENT_QUOTES));
    echo "<option value='$strength'></option>";
  }
}
?>