<?php

include("header-init.php");

try {

    // Prend les données brutes de la requête
    // $json = file_get_contents('php://input');
    $json = $_POST['article'];

    // Le convertit en objet PHP
    $article = json_decode($json);

    $idArticle = null;

    if (!isset($_GET['id'])) {
        echo '{"message" : "L\'URL doit contenir l\'id de l\'article"}';
    }

    $idArticle = $_GET['id'];

    if (strlen($article->nom) < 3) {
        echo '{"message" : "Le nom doit avoir un minimum de 3 caractères"}';
        http_response_code(400);
        exit;
    }
    if (strlen($article->nom) > 100) {
        echo '{"message" : "Le nom doit avoir un maximum de 100 caractères"}';
        http_response_code(400);
        exit;
    }
    if ($article->prix <= 0) {
        echo '{"message" : "Le prix doit être positif"}';
        http_response_code(400);
        exit;
    }


    $newFilesName = "";

    if (isset($_FILES['image'])) {
        $image = $_FILES['image'];
        $filesName = $image['name'];
        $extension = strtolower(pathinfo($filesName, PATHINFO_EXTENSION));

        if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
            echo '{"message" : "L\'extension du fichier doit être jpg, jpeg ou png"}';
            http_response_code(400);
            exit;
        }

        $newFilesName = date('Y-m-d-H-i-s') . '-' . $filesName;

        move_uploaded_file($image['tmp_name'], 'upload/' . $newFilesName);
    }

    // si aucune image n'a été selecitonné et que l'image n'a pas été supprimé
    // on affecte pas la potentielle image existante

    if ($newFilesName == '' && !$article->imgDelete) {
        $sql = "UPDATE article SET nom = :nom , description = :description, prix = :prix WHERE id = :id";

        $stmt = $db->prepare($sql);

        $stmt->bindvalue('nom', $article->nom);
        $stmt->bindvalue('description', $article->description);
        $stmt->bindvalue('prix', $article->prix);
        $stmt->bindValue('id', $idArticle);
    } else {
        $sql = "UPDATE article SET nom = :nom , description = :description, prix = :prix, image = :image WHERE id = :id";

        $stmt = $db->prepare($sql);

        $stmt->bindvalue('nom', $article->nom);
        $stmt->bindvalue('description', $article->description);
        $stmt->bindvalue('prix', $article->prix);
        $stmt->bindValue('image', $newFilesName);
        $stmt->bindValue('id', $idArticle);
    }

    $stmt->execute();

    echo '{"message" : "article modifié"}';
    http_response_code(200);
} catch (Exception $error) {
    echo '{"message" : "' . $error->getMessage() . '"}';
    http_response_code(500);
}
