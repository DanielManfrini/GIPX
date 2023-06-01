<?php

include('../../conexoes/conexao.php');

$sql = "SELECT 
            IFNULL(Ip,'') AS ip , 
            IFNULL(Nome,'') AS nome,
            IFNULL(Descricao,'') AS descricao,
            CASE 
                WHEN status = 0 THEN 'NÃO'
            ELSE 'SIM'
            END AS status
        FROM archerx.mapa_tabela_ip
        ORDER BY id";

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