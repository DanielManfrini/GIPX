<?php

include('../../conexoes/conexao_mysql_PDO.php');

$id = $_POST['id'];
$senha_antiga = $_POST['senha_antiga'];
$nova_senha = $_POST['nova_senha'];

try {

    $sql_verifica_senha = "SELECT * FROM usuarios_novo WHERE idusuarios = $id AND senha = '$senha_antiga'";
    $statement = $conn->prepare($sql_verifica_senha);
    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) <= 0) {

        $retorno = ['erro' => True, 'msg' => "Senha incorreta!"];

    } else {

        try {

            $sql_update = "UPDATE usuarios_novo SET senha = '$nova_senha' WHERE idusuarios = $id";
            $statement = $conn->prepare($sql_update);
            $statement->execute();

            $retorno = ['erro' => False];

        } catch (PDOException $e) {

            // Captura o erro e manipule-o aqui
            $errorMessage = $e->getMessage();
            header('Content-Type: application/json');
            echo json_encode($errorMessage);

        }
    }

} catch (PDOException $e) {

    // Captura o erro e manipule-o aqui
    $errorMessage = $e->getMessage();
    header('Content-Type: application/json');
    echo json_encode($errorMessage);

}

header('Content-Type: application/json');
echo json_encode($retorno);

?>