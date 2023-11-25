<?php
  $user_email = $_POST['email'];
  $user_password = $_POST['password'];
  

  if(isset($user_email) && isset($user_password)) {

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');

    require_once './config.php';
    
    if(isset($_POST['name'])) {

      $user_name = $_POST['name'];
      $sql = "SELECT * FROM users WHERE email='$user_email';";

      if($conn -> query($sql) -> num_rows == 0) {

        $password = password_hash($user_password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users VALUES(NULL, '$user_email', '$password', '$user_name');";

        $result = $conn -> query($sql);

        if($result === true) {
          $response["message"] = "Account Created";
          $response["status"] = true;
        }


      } else {
        $response["message"] = "Account with same email exists";
        $response["status"] = false;
      }

      echo json_encode($response);
      return;      
    }

    $sql = "SELECT * FROM users WHERE email='$user_email';";
    $result = $conn->query($sql);

    if($result -> num_rows > 0) {

      $data = $result -> fetch_assoc();

      if(password_verify($user_password, $data["password"])) {

        $response["message"] = "Login Successfull";
        $response["status"] = true;

        $cookie_name = "email";
        $cookie_value = $data["email"];
        setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
        setcookie("author_id", $data["id"], time() + (86400 * 30), "/"); // 86400 = 1 day

      } else  {
        $response["message"] = "Incorrect Password";
        $response["status"] = false;
      }
      
      
      echo json_encode($response);

    } else {
      echo json_encode(['msg' => 'No Data', 'status' => false]);
    } 
  }
?>