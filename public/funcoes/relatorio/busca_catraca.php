<?php
use LDAP\Result;

include('../../conexoes/conexao_mssql.php');

if ($_POST['tipo'] == 'select') {
    $matricula = $_POST['matricula'];
    $nome = $_POST['nome'];
    $inicio = $_POST['inicio'];
    $fim = $_POST['fim'];

    if ($nome == "") {
        $meio = "WHERE Funcionarios.Matricula = $matricula";
    }else{
        $meio = "WHERE Funcionarios.Nome LIKE '$nome'";
    }

    $sql = "SET LANGUAGE 'Portuguese'
            SELECT	
                DATENAME(weekday, Bilhetes.DataHora) AS semana,
                CASE 
                    WHEN Bilhetes.Tipo = 11 THEN 'SAÍDA' 
                    WHEN Bilhetes.Tipo = 10 THEN 'ENTRADA'	
                END AS tipo, 
                Funcionarios.Matricula as matricula, 
                Funcionarios.Nome	as nome,
                Bilhetes.NumInner	as catraca,
                CONVERT(varchar, Bilhetes.DataHora,103) as data,
                CONVERT(varchar, Bilhetes.DataHora,8) as hora
             
            FROM Bilhetes  
                INNER JOIN Funcionarios ON Funcionarios.COD_PESSOA = Bilhetes.COD_PESSOA  
                INNER JOIN Pessoas ON Funcionarios.COD_PESSOA = Pessoas.COD_PESSOA  
            ".$meio."
                AND CONVERT(date, Bilhetes.DataHora,103) BETWEEN '$inicio' AND '$fim' ORDER BY Data ASC";

    $conn = conect_topaceso();
    $statement = $conn->prepare($sql);
    $statement->execute();
    $conn = null;

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($result);
}

if ($_POST['tipo'] == 'nomes') {

    $nome = $_POST['nome'];

    $sql = "SELECT Nome FROM Funcionarios WHERE Nome LIKE '%$nome%'";
    $conn = conect_topaceso();
    $statement = $conn->prepare($sql);
    $statement->execute();
    $conn = null;

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Fechar a conexão com o banco de dados
    $dados = array();

    // Retornar os nomes como um array JSON
    header('Content-Type: application/json');
    echo json_encode($result);
}

?>