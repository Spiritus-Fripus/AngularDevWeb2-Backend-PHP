<?php
include ('header-init.php');

$json = file_get_contents('php://input');

$user = json_decode($json);

$passwordHash = password_hash($user->password, PASSWORD_DEFAULT);

$sql = $db->prepare("INSERT INTO user (email, password) VALUES (:email, :password)");

$sql->bindValue("email", $user->email);
$sql->bindValue("password", $passwordHash);

$sql->execute();

echo '{"message" : "inscription réussie"}';