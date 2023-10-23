<?php
function connectDatabase() {
    $db = new SQLite3('db.sqlite');
    return $db;
}

function startSession() {
    session_start();
}

function redirectToLoginPage() {
    header("Location: login.php");
    exit();
}

function logout() {
    session_unset();
    session_destroy();
    redirectToLoginPage();
}
?>
