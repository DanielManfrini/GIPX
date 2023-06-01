<?php

include('../../conexoes/conexao_mssql.php');

$lacre = $_POST['lacre'];

$sql = "SELECT	
            Funcionarios.nome, 
            Manutencao, 
            Inativo, 
            Estoque, 
            Treinamento, 
            Headsets_marcas.Marca, 
            Headsets_vendedores.Vendedor, 
            Headsets_defeitos.Defeito, 
            headsets_garantia.Inicio, 
            headsets_garantia.Fim 
        FROM Headsets 
            LEFT JOIN Funcionarios ON Funcionarios.Matricula = Headsets.EmPosse 
            LEFT JOIN Headsets_marcas ON Headsets_marcas.id = Headsets.Id_marca 
            LEFT JOIN Headsets_vendedores ON Headsets_vendedores.id = Headsets.Id_vendedor 
            LEFT JOIN Headsets_defeitos ON Headsets_defeitos.id = Headsets.Id_defeito 
            LEFT JOIN headsets_garantia ON headsets_garantia.Id_headset = Headsets.Id 
        WHERE Lacre = $lacre";

$conn = conect_gerenciador();
$statement = $conn->prepare($sql);
$statement->execute();
$conn = null;

$result = $statement->fetchAll(PDO::FETCH_ASSOC);


header('Content-Type: application/json');
echo json_encode($result);

?>