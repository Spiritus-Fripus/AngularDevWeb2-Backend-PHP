<?php

include('header-init.php');

$json = file_get_contents('php://input');

$user = json_decode($json);

$sql = $db->prepare("SELECT * FROM user WHERE email = :email AND password = :password");

$sql->bindValue('email', $user->email);
$sql->bindValue('password', $user->password);
$sql->execute();

$userDb = $sql->fetch();

// si user n'existe pas
if (!$userDb) {
    echo '{"message" : "login ou password incorect"}';
    http_response_code(403);
    exit();
}

function base64UrlEncode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

$payload = json_encode([
    'id' => $userDb['id'],
    'admin' => $userDb['admin'],
    'email' => $userDb['email'],
]);


// Encoder en Base64 URL-safe
$base64UrlHeader = base64UrlEncode($header);
$base64UrlPayload = base64UrlEncode($payload);

// Créer la signature
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'votre_cle_secrete', true);
$base64UrlSignature = base64UrlEncode($signature);

// Assembler le token
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

echo '{"jwt" : "' . $jwt . '"}';
