<?php
include 'config.php';
$sql_all_dogs="SELECT dogID, roomID, dogName, foodType, feedingInstructions FROM dogs ORDER BY roomID, dogName";
$result_all_dogs=$conn->query($sql_all_dogs);
while ($row_all_dogs=$result_all_dogs->fetch_assoc()) {
  $boardingDogID=$row_all_dogs['dogID'];
  $boardingRoomID=$row_all_dogs['roomID'];
  $boardingName=$row_all_dogs['dogName'];
  $boardingFoodType=$row_all_dogs['foodType'];
  $boardingFeedingInstructions=$row_all_dogs['feedingInstructions'];
  echo "<tr id='row-dog-$boardingDogID'>
  <td>$boardingRoomID</td>
  <td>$boardingName</td>
  <td>$boardingFoodType</td>
  <td>$boardingFeedingInstructions</td>
  <td>";
  $sql_dog_meds="SELECT dogMedID, dogID, medName, strength, dosage, frequency FROM dogs d JOIN dogs_medications m USING (dogID) WHERE dogID='$boardingDogID' ORDER BY FIELD(frequency,'AM','2X','3X','PM','As Needed'), medName, strength";
  $result_dog_meds=$conn->query($sql_dog_meds);
  if ($result_dog_meds->num_rows>0) {
    echo "";
  } else {
    echo "<em class='text-muted'>None</em>";
  }
  echo "</td>
  <td style='text-align:right;'>
  <button type='button' class='button-edit' id='edit-dog-button' data-toggle='modal' data-target='#editDogModal' data-id='$boardingDogID' data-backdrop='static' title='Edit'></button>
  <button type='button' class='button-delete' id='delete-dog-button' data-toggle='modal' data-target='#deleteDogModal' data-id='$boardingDogID' data-backdrop='static' title='Delete'></button>
  </td>
  </tr>";
}
?>
