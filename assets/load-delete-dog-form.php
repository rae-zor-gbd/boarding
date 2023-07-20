<?php
include 'config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_dog_info="SELECT roomID, dogName FROM dogs WHERE dogID='$id'";
  $result_dog_info=$conn->query($sql_dog_info);
  $row_dog_info=$result_dog_info->fetch_assoc();
  $roomNo=$row_dog_info['roomID'];
  $dogName=htmlspecialchars($row_dog_info['dogName'], ENT_QUOTES);
  echo "<input type='hidden' class='form-control' name='id' id='deleteID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon owner'>Room</span>
  <input type='text' class='form-control' name='room' id='deleteRoom' value='$roomNo' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon dog'>Dog Name</span>
  <input type='text' class='form-control' name='dog-name' id='deleteDogName' value='$dogName' disabled>
  </div>";
}
?>
