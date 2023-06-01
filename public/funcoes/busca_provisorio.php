<?php

include('../conexoes/conexao_mssql.php');

function select($sql, $mat)
{
    $conn = conect_topaceso();
    $statement = $conn->prepare($sql);
    $statement->bindParam('matricula', $mat);
    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    stop($conn);
    return $result;


}

// Receber dados
$mat = filter_input(INPUT_GET, 'matricula', FILTER_SANITIZE_NUMBER_INT);

if (!empty($mat)) {

    $sql = "SELECT TOP(1) CartoesProvisorios.NumeroCartao 
            FROM CartoesProvisorios
            INNER JOIN	Funcionarios ON CartoesProvisorios.COD_PESSOA = Funcionarios.COD_PESSOA
            WHERE Matricula = :matricula";

    $resultado_query_cartao = select($sql, $mat);

    $retorno = ['erro' => False, 'dados' => $resultado_query_cartao];

} else {

    $retorno = ['erro' => True];

}

echo json_encode($retorno);

?>