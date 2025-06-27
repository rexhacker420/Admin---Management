<?php
require 'db.php'; // $pdo connection

$status = $_POST['status'];
$name = $_POST['name'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // secure hash
$email = $_POST['email'];
$address = $_POST['address'];
$device = $_POST['device'];

$stmt = $pdo->prepare("INSERT INTO users (status, name, username, password, email, address, device) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$status, $name, $username, $password, $email, $address, $device]);

header("Location: index.php"); // replace with your file
exit;
?>