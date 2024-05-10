<?php

include("header-init.php");

include("extract-jwt.php");

if (!$user->admin) {
    echo '{"message" : "vous n\'etes pas administrateur"}';
    http_response_code(403);
    exit;
}

try {

    // Prend les données brutes de la requête
    // $json = file_get_contents('php://input');
    $json = $_POST['article'];

    // Le convertit en objet PHP
    $article = json_decode($json);

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




    $sql = "INSERT INTO article (nom, description,prix, image) VALUES (:nom , :description , :prix, :image)";

    $stmt = $db->prepare($sql);

    $stmt->bindvalue('nom', $article->nom);
    $stmt->bindvalue('description', $article->description);
    $stmt->bindvalue('prix', $article->prix);
    $stmt->bindValue('image', $newFilesName);

    $stmt->execute();

    echo '{"message" : "article ajouté"}';
    http_response_code(201);
} catch (Exception $error) {

    echo '{"message" : "' . $error->getMessage() . '"}';
    http_response_code(500);
}
