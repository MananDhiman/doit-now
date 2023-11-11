<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  if(!isset($_COOKIE['author_id'])) {
    // redirect to login
    echo 'not logged in';
    return;
  }

  if(!isset($_SERVER['REQUEST_METHOD'])) {
    // err message
    echo 'some internal error';
    return;
  }

  $req_method = $_SERVER['REQUEST_METHOD'];
  $author_id = $_COOKIE['author_id'];

  require_once './config.php';
  header('Content-Type: application/json');

  if($req_method == "GET") {
    // echo "GETTING";

    $sql = "SELECT * FROM todos WHERE author_id='$author_id' ORDER BY id DESC LIMIT 25;";

    $result = $conn->query($sql);

    if($result -> num_rows > 0) {
      $data = $result -> fetch_all(MYSQLI_ASSOC);

      $response["status"] = true;
      $response["data"] = $data;
      echo json_encode($response);

    } else {
      echo json_encode(['msg' => 'No Data', 'status' => false]);
    }
  }
  elseif($req_method == "POST") {

    if(!isset($_POST['todo'])) {
      // cannot add. try again later
      echo 'cannot add. try again later';
      return;
    }

    $new_todo = $_POST['todo'];

    $sql = "INSERT INTO todos VALUES (null, '$author_id', '$new_todo');";

    $result = $conn->query($sql);

    if ($result === TRUE) {
      $response["message"] = "Todo Added Successfully";
      $response["status"] = true;
      $response["data"] = $new_todo;
    } else {
      $response["message"] = "cannot add. try again later";
      $response["status"] = false;
    }

    echo json_encode($response);

  }
  elseif($req_method == "PUT") { 
    
    parse_str(file_get_contents("php://input"),$vars);
    
    if(!isset($vars['todo'])
      || !isset($vars['todo_id'])) {
      // cannot edit. try again later
      echo 'cannot edit. try again later';
      return;
    }

    
    $todo = $vars['todo'];
    $todo_id = $vars['todo_id'];

    $sql = "UPDATE todos SET title='$todo' WHERE id='$todo_id';";

    $result = $conn->query($sql);

    if ($result === TRUE) {

      $data['todo'] = $todo;
      $data['todo_id'] = $todo_id;

      $response["message"] = "Todo Updated Successfully";
      $response["status"] = true;
      $response["data"] = $data;
    } else {
      $response["message"] = "cannot update. try again later";
      $response["status"] = false;
    }

    echo json_encode($response);
  }
  elseif($req_method == "DELETE") {

    parse_str(file_get_contents("php://input"),$vars);
    
    if(!isset($vars['todo_id'])) {
      // cannot delete. try again later
      echo 'cannot delete. try again later';
      return;
    }

    $todo_id = $vars['todo_id'];

    $sql = "DELETE FROM todos WHERE id='$todo_id';";

    $result = $conn->query($sql);

    if ($result === TRUE) {

      $response["message"] = "Todo Deleted Successfully";
      $response["status"] = true;
      
    } else {
      $response["message"] = "cannot delete. try again later";
      $response["status"] = false;
    }

    echo json_encode($response);
  } 
  else {
    // err message
    echo 'some internal error';
  }

?>