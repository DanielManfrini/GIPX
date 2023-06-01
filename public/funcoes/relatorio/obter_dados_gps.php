<?php

include('../../conexoes/conexao.php');

$sql = "SELECT 
            IFNULL(baia.baia,'') AS baia , 
            IFNULL(setor.setor,'') AS setor ,
            IFNULL(ramal_baia.ramal,'') AS ramal , 
            IFNULL(hosts.hostname,'') AS hostname , 
            IFNULL(hosts.serial,'') AS serial , 
            IFNULL(monitores.serie,'') AS serie ,
            IFNULL(qdu.qdu_serial,'') AS qdu_serial ,
            IFNULL(usuarios.nome,'') AS nome , 
            IFNULL(acs_uni.data,'') AS data 
        FROM acs_uni 
            LEFT JOIN baia ON acs_uni.id_baia = baia.id
            LEFT JOIN ramal_baia  ON acs_uni.id_ramal_baia = ramal_baia.id
            LEFT JOIN hosts ON acs_uni.id_hostname = hosts.id
            LEFT JOIN monitores ON acs_uni.id_monitores = monitores.id
            LEFT JOIN usuarios ON acs_uni.Id_funcionario = usuarios.login
            LEFT JOIN setor ON acs_uni.id_setor = setor.id
            LEFT JOIN qdu ON acs_uni.id_qdu = qdu.id";

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