<?php

include("header-init.php");
include("extract-jwt.php");

try {
    $sql = "SELECT * FROM article";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $articles = $stmt->fetchAll();
} catch (PDOexception $e) {
    die($e->getMessage());
}

echo json_encode($articles);
