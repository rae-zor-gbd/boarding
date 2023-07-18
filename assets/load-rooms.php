<?php
include 'config.php';
$sql_all_dogs="SELECT dogID, roomID, dogName, foodType, feedingInstructions FROM dogs ORDER BY roomID, dogName";
$result_all_dogs=$conn->query($sql_all_dogs);
while ($row_all_dogs=$result_all_dogs->fetch_assoc()) {
    $boardingRoomID=$row_all_dogs['roomID'];
    $boardingName=$row_all_dogs['dogName'];
    $boardingFoodType=$row_all_dogs['foodType'];
    $boardingFeedingInstructions=$row_all_dogs['feedingInstructions'];
    echo "<tr>
    <td>$boardingRoomID</td>
    <td>$boardingName</td>
    <td>$boardingFoodType</td>
    <td>$boardingFeedingInstructions</td>
    <td>TBD</td>
    <td style='text-align:right;'>
    <button type='button' class='button-edit' id='edit-button' data-toggle='modal' data-target='#editModal' data-id='TBD' title='Edit'></button>
    <button type='button' class='button-delete' id='delete-button' data-toggle='modal' data-target='#deleteModal' data-id='TBD' title='Delete'></button>
    </td>
    </tr>";
}
?>
