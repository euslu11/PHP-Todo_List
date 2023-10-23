<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-secondary">
    <div class="container mt-5">
        <div class="row justify-content">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center bg-danger text-white">Registration</h2>
                    </div>
                    <div class="card-body">
                    <svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                         <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                    </svg>
                        <form action="register.php" method="POST">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="password_confirm">Repeat Password </label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                              <button type="submit" class="btn btn-outline-primary">Register</button>
                  
                        </form>                      
                    </div>
                </div>
                <div class="text-center mt-3 bg-dark text-white">
                                <p>Do you already have an account? <a href="login.php">Login</a></p>
                            </div>
            </div>            
        </div>
    </div>

</body>
</html>

<?php

$database_file = 'db.sqlite';
$db = new SQLite3($database_file);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {

        echo "Passwords do not match. Please enter the same password twice.";
    } 

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username, SQLITE3_TEXT);
    $stmt->bindParam(':password', $hashed_password, SQLITE3_TEXT);
    $result = $stmt->execute();

    if ($result) {

        header("Location: login.php");
        exit();
    } else {

        echo "registration error.";
    }
}

$db->close();
?>
