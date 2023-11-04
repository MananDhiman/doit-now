<?php
  $user_email = $_POST['email'];
  $user_password = $_POST['password'];

  if(isset($user_email) && isset($user_password)) {
    
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');

    
    require_once './config.php';

    $sql = "SELECT * FROM users WHERE email='$user_email' AND password='$user_password';";
    $result = $conn->query($sql);

    // if($result->num_rows > 0){
    //   foreach($result as $row){
    //     echo $row['password'];
    //   }
    // }

    // to display in api
    if($result -> num_rows > 0) {

      // $data = $result -> fetch_all(MYSQLI_ASSOC);
      $data = $result -> fetch_assoc();
      echo json_encode($data);

    } else {
      echo json_encode(['msg' => 'No Data!', 'status' => false]);
    } 
  }
?>