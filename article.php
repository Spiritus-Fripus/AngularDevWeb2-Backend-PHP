<?php

include("header-init.php");

try {

    if (!isset ($_GET['id'])){
        http_response_code(400);
        echo '{"message" : "Il manque l\'identifiant dans l\'url"}';
        exit();
    }

    $idArticle = $_GET['id'];

    $sql = "SELECT * FROM article WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":id", $idArticle);
    $stmt->execute();

    $article = $stmt->fetch();

    if (!$article) {
        http_response_code(404);
        echo '{"message" : "Article not found"}';
        exit();
    }


} catch (PDOexception $e) {
    die($e->getMessage());
}

echo json_encode($article);
