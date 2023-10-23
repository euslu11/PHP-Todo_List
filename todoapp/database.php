<?php

$database_file = 'db.sqlite';


$db = new SQLite3($database_file);


$query_todos = 'CREATE TABLE IF NOT EXISTS todos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    task TEXT,
    completed INTEGER DEFAULT 0
)';

$db->exec($query_todos);

$query_users = 'CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT
)';

$db->exec($query_users);

$db->close();

echo 'Veritabanı ve tablolar oluşturuldu!';
?>
