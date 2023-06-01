<?php

include('../../conexoes/conexao.php');

$sql = "SELECT 
            IFNULL(login_plansul,'') AS login_plansul, 
            IFNULL(login_caixa,'') AS login_caixa,
            IFNULL(nome,'') AS nome,
            IFNULL(nivel_acesso,'') AS nivel_acesso
        FROM archerx.usuarios_novo";

$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);

?>