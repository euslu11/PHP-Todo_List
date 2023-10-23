<?php
session_start();

$username = $_SESSION['username'];

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();
}

$database_file = 'db.sqlite';
$db = new SQLite3($database_file);


$user_id = $_SESSION['user_id'];


$search_term = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $search_term = $_POST['search'];
    $query = "SELECT todos.id AS todo_id, todos.task AS todo_task, todos.completed AS todo_completed
              FROM todos
              WHERE todos.user_id = :user_id AND todos.task LIKE :search";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':search', '%' . $search_term . '%', SQLITE3_TEXT);
} else {
    $query = "SELECT todos.id AS todo_id, todos.task AS todo_task, todos.completed AS todo_completed
              FROM todos
              WHERE todos.user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, SQLITE3_INTEGER);
}

$result = $stmt->execute();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $task = $_POST['task'];


    $query = "INSERT INTO todos (user_id, task) VALUES (:user_id, :task)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindParam(':task', $task, SQLITE3_TEXT);
    $result = $stmt->execute();
    if ($result) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $todo_id = $_POST['todo_id'];

    if ($action === 'delete') {

        $query = "DELETE FROM todos WHERE id = :todo_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':todo_id', $todo_id, SQLITE3_INTEGER);
        $result = $stmt->execute();
    } elseif ($action === 'edit') {

        header("Location: edit.php?todo_id=$todo_id");
        exit();
    }

    header("Location: index.php");
    exit();
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do APP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
</head>
<body class="bg-secondary">  
    <div class="container mt-3">
        <div class="row justify-content mx-6">
            <div class="col-md-6">
                <h2 class="text-center bg-danger text-white">To-Do APP</h2>
                <form action="index.php" method="POST" class="mb-3">
                    <div class="input-group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16">
                     <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5H3z"/>
                      <path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z"/>
                    </svg>
                        <input type="text" class="form-control" name="task" placeholder="Add To-Do" required>
                        <div class="input-group-append">                           
                            <button type="submit" class="btn btn-warning ml-2" name="add_task">ADD</button>                            
                        </div>
                    </div>
                </form>
                <form action="index.php" method="POST">
                    <div class="input-group mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16">
                        <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5H3z"/>
                        <path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z"/>
                    </svg>                   
                        <input type="text" class="form-control" name="search" placeholder="Search To-Do" value="<?= $search_term ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-warning ml-2">SEARCH</button>
                        </div>                       
                    </div>                    
                </form>
                </form>
                <table class="table table-bordered ">
     
                    <tbody >
                        <?php
                        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                            echo "<tr>";                                                      
                            echo "<td>" . $row['todo_task'] . "</td>";                       
                            echo "<td>";
                            echo '<form action="index.php" method="POST" class="d-inline">';
                            echo '<input type="hidden" name="todo_id" value="' . $row['todo_id'] . '">';
                            echo '<input class="form-check-input ml-1  mt-2" type="checkbox" value="" id="flexCheckDefault">';
                            echo '<button type="submit" class="btn btn-outline-danger text-white ml-5 " name="action" value="delete">Delete</button>';
                            echo '<button type="submit" class="btn btn-outline-info text-white ml-2" name="action" value="edit">Edit</button>';                            
                            echo '</form>';
                        
                        }
                        ?>
                    </tbody>
                </table>
                <a class="btn btn-outline-danger text-white" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
 
</html>
