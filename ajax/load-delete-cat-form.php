<?php
include '../assets/config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_cat_info="SELECT condoID, catName FROM cats WHERE catID='$id'";
  $result_cat_info=$conn->query($sql_cat_info);
  $row_cat_info=$result_cat_info->fetch_assoc();
  $condoNo=$row_cat_info['condoID'];
  $catName=htmlspecialchars($row_cat_info['catName'], ENT_QUOTES);
  echo "<input type='hidden' class='form-control' name='id' id='deleteID' value='$id' required>
  <div class='input-group'>
  <span class='input-group-addon room'>Condo</span>
  <input type='text' class='form-control' name='condo' id='deleteCondo' value='$condoNo' disabled>
  </div>
  <div class='input-group'>
  <span class='input-group-addon cat'>Cat Name</span>
  <input type='text' class='form-control' name='cat-name' id='deleteCatName' value='$catName' disabled>
  </div>";
}
?>
