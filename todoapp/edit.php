<?php
include('connect.php');
$db = connectDatabase();

startSession();

if (!isset($_SESSION['user_id'])) {

    redirectToLoginPage();
}

if (isset($_GET['todo_id'])) {
    $todo_id = $_GET['todo_id'];

    $query = "SELECT * FROM todos WHERE id = :todo_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':todo_id', $todo_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $todo = $result->fetchArray(SQLITE3_ASSOC);

    if (!$todo) {
   
        echo "Not Found";
        exit();
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_task'])) {
    $edited_task = $_POST['edited_task'];


    $query = "UPDATE todos SET task = :edited_task WHERE id = :todo_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':edited_task', $edited_task, SQLITE3_TEXT);
    $stmt->bindParam(':todo_id', $todo_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    if ($result) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-secondary">
    <div class="container mt-3">
        <div class="row justify-content">
            <div class="col-md-4">
                <h2 class="text-center bg-danger text-white">Edit Page</h2>
                <form action="edit.php?todo_id=<?php echo $todo_id; ?>" method="POST">
                    <div class="form-group">
                        <label for="edited_task" class="text-white bg-danger">Edit Todo</label>
                        <input type="text" class="form-control" id="edited_task" name="edited_task" placeholder="Edit To-Do" value="<?php echo $todo['task']; ?>" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-outline-success text-white" name="edit_task">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
