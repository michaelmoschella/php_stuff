<?php
/********************************************** 
register-parent.php

Takes info registed by parents and inserts into
the User table Parent table, as well as
the Moderator table if needed
***********************************************/
  $p_email = $_POST['Parent_Email'];
  $p_pass = $_POST['Parent_Pass'];
  $p_pass_confirm  = $_POST['Parent_Confirm_Pass'];
  $p_role = $_POST['p_role'];
  $p_name = $_POST['Parent_Name'];
  $p_phone = $_POST['Parent_Phone_Number'];
  $p_username = $_POST['p_username'];

  $myconnection = mysqli_connect('localhost', 'root', '')
    or die ('Could not connect: ' . mysqli_error());
  $mydb = mysqli_select_db ($myconnection, 'DB2') or die ('Could not select database');

  # determine next available id
  $max_id_query = "SELECT MAX(uID) FROM User";
  $result1 = mysqli_query($myconnection, $max_id_query) or die ('Query failed: ' . mysqli_error($myconnection));
  $row = mysqli_fetch_row($result1);
  if ($row){
    $p_id = $row[0] + 1;
  } else {
    $p_id = 1;
  }
  mysqli_free_result($result1);

  # insert into user table
  if ($p_role == 'None') {
    $p_role = 'Parent';
  }
  $insert_user_query = "INSERT INTO User VALUES ({$p_id}, \"{$p_name}\", \"{$p_email}\", \"{$p_phone}\", \"{$p_username}\", \"{$p_pass}\", \"{$p_role}\");";
  $result2 = mysqli_query($myconnection, $insert_user_query) or die ('Query failed: ' . mysqli_error($myconnection));

  #inser into parent table
  $insert_parent_query = "INSERT INTO Parent VALUES ({$p_id});";
  $result2 = mysqli_query($myconnection, $insert_parent_query) or die ('Query failed: ' . mysqli_error($myconnection));

  # insert into moderator table
  if ($p_role === 'Moderator') {
    $insert_moderator_query = "INSERT INTO Moderator VALUES($p_id);";
    $result3 = mysqli_query($myconnection, $insert_moderator_query) or die ('Query failed: ' . mysqli_error($myconnection));
  }
  $object = new stdClass();
  $object->status = 1; // 1 indicates success
  $object->p_username = $p_username;

  $the_json = json_encode($object);

  echo($the_json);
  echo("<h3><a href='../Phase2.html'>Back to main page</a></h3>");// DELETE ME

  mysqli_close($myconnection);
  exit;
?>
