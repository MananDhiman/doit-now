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

    if($result -> num_rows > 0) {

      $data = $result -> fetch_assoc();
      $response["message"] = "Login Successfull";
      $response["status"] = true;
      
      echo json_encode($response);

      $cookie_name = "email";
      $cookie_value = $data["email"];
      setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
      setcookie("author_id", $data["id"], time() + (86400 * 30), "/"); // 86400 = 1 day


    } else {
      echo json_encode(['msg' => 'No Data', 'status' => false]);
    } 
  }
?>