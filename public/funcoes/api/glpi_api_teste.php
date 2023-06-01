<?php
$login = 'daniel.lopes';
$password = 'Daniel102009';



// Iniciar a sessão para obter o token de autenticação
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://172.10.20.53/glpi/apirest.php/initSession');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    array(
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($login . ':' . $password),
        'App-Token: Ey5Ez7HBnpdDnOelp3bSEezjUP6Jkl3sjqVq4kxL'
    )
);

$response = curl_exec($ch);
curl_close($ch);

echo $response;

if ($response[2] !== "E") {

    $retorno = ['erro' => False];

    header('Content-Type: application/json'); // Definimos o cabeçalho e devolvemos os dados ao cliente.
    echo json_encode($retorno);

    die();

} else {

    // Lidar com erros na chamada curl
    $retorno = ['erro' => True, 'mensagem' => 'Login ou senha incorretos!'];

    header('Content-Type: application/json'); // Definimos o cabeçalho e devolvemos os dados ao cliente.
    echo json_encode($retorno);

    die();

}
