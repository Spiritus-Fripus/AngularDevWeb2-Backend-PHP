<?php

include ("header-init.php");

$idArticleToDelete = $_GET["id"];

$sql = "DELETE FROM article WHERE id = :id";

$stmt = $db->prepare($sql);
$stmt->bindValue(":id", $idArticleToDelete);
$stmt->execute();

echo '{"message": "l\'article a bien été supprimé"}';