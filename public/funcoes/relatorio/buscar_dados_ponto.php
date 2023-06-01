<?php

include('../../conexoes/conexao_mssql.php');

if ($_POST['tipo'] == 'select') {

  $matricula = $_POST['matricula'];
  $nome = $_POST['nome'];
  $inicio = $_POST['inicio'];
  $fim = $_POST['fim'];

  if ($nome == "") {
    $meio = "Mat_Dac = $matricula";
  } else {
    $meio = "Nome LIKE '$nome'";
  }

  $sql = "SET LANGUAGE 'Portuguese'
          SELECT	
            DATENAME(weekday, [data]) AS DiaSemana
            ,CONVERT(varchar, [data],103) as data
            ,CASE
            WHEN [feriado] = 1 THEN 'SIM'
            ELSE 'NÃO'
            END AS feriado
            ,[Mat_P]
            ,[Mat_Dac]
            ,[pis]
            ,[Nome]
            ,[DescricaoEscala]
            ,[Escala_Minutos]
            ,[Escala_Almoco_Minutos]
            ,[Escala6X1]
            ,[Escala5X2]
            ,[De_Situacao]
            ,[Qtd_Data]
            ,CONVERT(varchar, CONVERT(time, Login_Intelix),8) as Login_Intelix
            ,CONVERT(varchar, CONVERT(time, Logoff_Intelix),8) as Logoff_Intelix
            ,[entrada1]
            ,[saida1]
            ,[entrada2]
            ,[saida2]
            ,[obs]
            ,CASE
            WHEN CAST([Minutos_Expediente] AS INT) < 0 THEN 
              '-' + RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Minutos_Expediente] AS INT)) / 60), 2) + ':' +
              RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Minutos_Expediente] AS INT)) % 60), 2)
            ELSE 
              RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Minutos_Expediente] AS INT)) / 60), 2) + ':' +
              RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Minutos_Expediente] AS INT)) % 60), 2)
            END AS Minutos_Expediente
            
            ,CASE
            WHEN CAST([Minutos_Almoco] AS INT) < 0 THEN 
              '-' + RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Minutos_Almoco] AS INT)) / 60), 2) + ':' +
              RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Minutos_Almoco] AS INT)) % 60), 2)
            ELSE 
              RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Minutos_Almoco] AS INT)) / 60), 2) + ':' +
              RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Minutos_Almoco] AS INT)) % 60), 2)
            END AS Minutos_Almoco
            
            ,CASE
            WHEN CAST([Minutos_Expediente_Considerado] AS INT) < 0 THEN 
              '-' + RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Minutos_Expediente_Considerado] AS INT)) / 60), 2) + ':' +
              RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Minutos_Expediente_Considerado] AS INT)) % 60), 2)
            ELSE 
              RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Minutos_Expediente_Considerado] AS INT)) / 60), 2) + ':' +
              RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Minutos_Expediente_Considerado] AS INT)) % 60), 2)
            END AS Minutos_Expediente_Considerado
            
            ,CASE
            WHEN CAST([Banco_Horas_Dia] AS INT) < 0 THEN 
              '-' + RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Banco_Horas_Dia] AS INT)) / 60), 2) + ':' +
              RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Banco_Horas_Dia] AS INT)) % 60), 2)
            ELSE 
              RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Banco_Horas_Dia] AS INT)) / 60), 2) + ':' +
              RIGHT('0' + CONVERT(VARCHAR(2), ABS(CAST([Banco_Horas_Dia] AS INT)) % 60), 2)
            END AS Banco_Horas_Dia

            ,[PS_departamento_id]
            ,[PS_departamento]
            ,[CO_Funcao]
            ,[Funcao]
            ,[Nome_Gestor1]
            ,[Funcao_Gestor1]
            ,[Nome_Gestor2]
            ,[Funcao_Gestor2]
            ,[Nome_Gerente]
            ,[Funcao_Gerente]
            ,[Base_Origem]
          FROM [GerenciadorAcessos].[dbo].[Funcionarios_folha_ponto_novo]
            WHERE $meio 
            AND CONVERT(VARCHAR(10), CONVERT(date, data), 23) BETWEEN '$inicio' AND '$fim'
            ORDER BY nome";

  try {

    $conn_ponto = conect_topaceso();
    $statement = $conn_ponto->prepare($sql);
    $statement->execute();
    $conn_ponto = null;

    $resultado_query_ponto = $statement->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($resultado_query_ponto);

  } catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

  }
}
if ($_POST['tipo'] == 'nomes') {

  $nome = $_POST['nome'];

  $sql = "SELECT 
            PontoSecullumAJ.dbo.funcionarios.nome 
          FROM PontoSecullumAJ.dbo.funcionarios 
          WHERE PontoSecullumAJ.dbo.funcionarios.nome LIKE '%$nome%'
          UNION ALL
          SELECT 
            PontoSecullumKZ.dbo.funcionarios.nome 
          FROM PontoSecullumKZ.dbo.funcionarios 
          WHERE PontoSecullumKZ.dbo.funcionarios.nome LIKE '%$nome%'";


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