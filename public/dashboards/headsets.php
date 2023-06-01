<?php

// pegar o dia
if (!isset($_POST['data_inicial'])) {
  $data_inicial = date('Y-m-d');
} else {
  $data_inicial = $_POST['data_inicial'];
}
if (!isset($_POST['data_final'])) {
  $data_final = date('Y-m-d');
} else {
  $data_final = $_POST['data_final'];
}

include('../conexoes/conexao_mssql.php');

$conexao_gerenciador = conect_gerenciador();

try { // ATIVOS

  $sql_ativos = "SELECT COUNT(*) as ativos FROM HEADSETS WHERE Estoque=0 AND Inativo = 0 AND Manutencao = 0 AND EmPosse IS NOT NULL";
  $statement = $conexao_gerenciador->prepare($sql_ativos);
  $statement->execute();

  $resultado_ativos = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

  echo "Erro na conexão: " . $e->getMessage();
}

try { // ESTOQUE

  $sql_estoque = "SELECT COUNT(*) as estoque FROM HEADSETS WHERE Estoque=1 AND Inativo = 0 AND Manutencao = 0 AND Emprestado = 0 AND Treinamento = 0 AND Id_marca != 4";
  $statement = $conexao_gerenciador->prepare($sql_estoque);
  $statement->execute();

  $resultado_estoque = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

  echo "Erro na conexão: " . $e->getMessage();
}

try { // MANUTENCAO
  $sql_manutençao = "SELECT COUNT(*) as manutencao FROM HEADSETS WHERE Manutencao=1 AND Inativo=0 ";
  $statement = $conexao_gerenciador->prepare($sql_manutençao);
  $statement->execute();

  $resultado_manutencao = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

  echo "Erro na conexão: " . $e->getMessage();
}

try { // TROCAS
  $sql_trocas = "SELECT COUNT(*) as trocas FROM headsets_trocas WHERE Id_motivo = 2 AND Data BETWEEN '$data_inicial' AND '$data_final'";
  $statement = $conexao_gerenciador->prepare($sql_trocas);
  $statement->execute();

  $resultado_trocas = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

  echo "Erro na conexão: " . $e->getMessage();
}

try { // ENTREGAS 
  $sql_entregas = "SELECT COUNT(*) as entregas FROM headsets_trocas WHERE Id_motivo = 1 AND Id_headset_novo NOT IN (SELECT Id_headset FROM Treinamento) AND Data BETWEEN '$data_inicial' AND '$data_final'";
  $statement = $conexao_gerenciador->prepare($sql_entregas);
  $statement->execute();

  $resultado_entregas = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

  echo "Erro na conexão: " . $e->getMessage();
}

try { // INTEGRACAO 
  $sql_treinamento = "SELECT COUNT(*) as treinamento FROM Treinamento WHERE EmUso = 1 AND Data BETWEEN '$data_inicial' AND '$data_final'";
  $statement = $conexao_gerenciador->prepare($sql_treinamento);
  $statement->execute();

  $resultado_treinamento = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

  echo "Erro na conexão: " . $e->getMessage();
}

try { // EMPRÉSTIMO
  $sql_emprestimo = "SELECT COUNT(*) as emprestimo FROM HEADSETS WHERE Emprestado = 1";
  $statement = $conexao_gerenciador->prepare($sql_emprestimo);
  $statement->execute();

  $resultado_emprestimo = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

  echo "Erro na conexão: " . $e->getMessage();
}

try { // DEVOLVIDOS 
  $sql_devolvidos = "SELECT COUNT(*) as devolvidos FROM Funcionarios WHERE HeadDevolvido = 1 AND DataDemissao BETWEEN '$data_inicial' AND '$data_final'";
  $statement = $conexao_gerenciador->prepare($sql_devolvidos);
  $statement->execute();

  $resultado_devolvidos = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

  echo "Erro na conexão: " . $e->getMessage();
}

try { // NÃO RECOLHIDOS
  $sql_nao_devolvidos = "SELECT COUNT(*) AS demitidos FROM Funcionarios WHERE Situacao = 1 AND RecebeuHead = 1 AND HeadDevolvido = 0 AND Matricula NOT IN (Select id_funcionario FROM Descontos WHERE Id_funcionario = Funcionarios.Matricula AND Tipo = 3) AND DataDemissao BETWEEN '$data_inicial' AND '$data_final'";
  $statement = $conexao_gerenciador->prepare($sql_nao_devolvidos);
  $statement->execute();

  $resultado_nao_devolvidos = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

  echo "Erro na conexão: " . $e->getMessage();
}

try { // DESCONTOS POR NAO DEVOLVER
  $sql_descontos_devolucao = "SELECT COUNT(*) FROM Descontos WHERE Tipo IN (2,3)  AND Data BETWEEN '$data_inicial' AND '$data_final'";
  $statement = $conexao_gerenciador->prepare($sql_descontos_devolucao);
  $statement->execute();

  $resultado_descontos_devolucao = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

  echo "Erro na conexão: " . $e->getMessage();
}

try { // DESCONTOS
  $sql_descontos = "SELECT COUNT(*) FROM Descontos WHERE Tipo = 1 AND Data BETWEEN '$data_inicial' AND '$data_final'";
  $statement = $conexao_gerenciador->prepare($sql_descontos);
  $statement->execute();

  $resultado_descontos = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {

  echo "Erro na conexão: " . $e->getMessage();
}

$conexao_gerenciador = null;

$dados_estoque_manutencao = array(
  'MANUTENCAO' => $resultado_manutencao[0]['manutencao'],
  'ESTOQUE' => $resultado_estoque[0]['estoque'],
  'ATIVOS' => $resultado_ativos[0]['ativos']
);

$dados_movimentacoes = array(
  'TROCAS' => $resultado_trocas[0]['trocas'],
  'ENTREGAS' => $resultado_entregas[0]['entregas'],
  'INTEGRACAO' => $resultado_treinamento[0]['treinamento'],
  'EMPRESTIMO' => $resultado_emprestimo[0]['emprestimo']
);

$dados_resto = array(
  'DEVOLVIDOS' => $resultado_devolvidos[0]['devolvidos'],
  'NAO_DEVOLVIDOS' => $resultado_nao_devolvidos[0]['nao_devolvidos'],
  'DESCONTOS_DEVOLUCAO' => $resultado_descontos_devolucao[0]['nao_devolvidos'],
  'DESCONTOS' => $resultado_emprestimo[0]['emprestimo']
);

$dados = array(
  'estoque' => $dados_estoque_manutencao,
  'movimentacoes' => $dados_movimentacoes,
  'resto' => $dados_resto
);

$json_estoque_manutencao = json_encode($dados_estoque_manutencao);

$json_movimentacoes = json_encode(($dados_movimentacoes));

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Archerx integration</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="refresh" content="180" />

  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Sweet Alert -->
  <link type="text/css" href="../../bibli/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

  <!-- Sweet Alerts 2 -->
  <script src="../../bibli/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="../../bibli/sweetalert/dist/sweetalert.min.js"></script>

  <!-- Estilos -->
  <link rel="stylesheet" type="text/css" href="/archerx/css/header-style.css">
  <link rel="stylesheet" href="/archerx/css/dashboards/dash_headsets-style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  <?php

  if (!isset($_COOKIE['login'])) {

    // Armazena o caminho atual na sessão
    session_start();
    $_SESSION['caminho_atual'] = $_SERVER['REQUEST_URI'];

    echo "<script> 
  swal({
        title: 'Erro!',
        text: 'Você não está logado.',
        icon: 'error',
        button: false,
        timer: 1500
      }).then(function() {
        window.location.href = '/archerx/public/login.php';
      });
</script>";
    exit;
  };

  include('../includes/navbar.php');

  ?>
  <div class="container">
    <div class="container_interno">
      <table>
        <th colspan="4" class="form">
          <form method="post" action="headsets.php">
            <label for="data_inicial">
              <h4>Data inicial:</h4>
            </label>
            <input type="date" name="data_inicial" id="data_inicial" value="<?php echo $data_inicial ?>" />

            <label for="data_final">
              <h4>Data final:</h4>
            </label>
            <input type="date" name="data_final" id="data_final" value="<?php echo $data_final ?>" />

            <input type="submit" value="Buscar">
          </form>
        </th>
        <tr>
          <th colspan="2">VISÃO GERAL</th>
          <th colspan="2">MOVIMENTAÇÕES</th>
        </tr>
        <tr>
          <td class="canvas"><canvas id="estoque_manutencao"></canvas></td>
          <td>
            <table class="tabela">
              <td>
                <?php echo "ATIVOS: " . $resultado_ativos[0]['ativos'] . "" ?>
              </td>
              <tr>
                <td>
                  <?php echo "ESTOQUE: " . $resultado_estoque[0]['estoque'] . "" ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo "MANUTENÇÃO: " . $resultado_manutencao[0]['manutencao'] . "" ?>
                </td>
              </tr>
            </table>
          </td>
          <td class="canvas"><canvas id="movimentacoes"></canvas></td>
          <td>
            <table class="tabela">
              <td>
                <?php echo "TROCAS: " . $resultado_trocas[0]['trocas'] . "" ?>
              </td>
              <tr>
                <td>
                  <?php echo "ENTREGAS: " . $resultado_entregas[0]['entregas'] . "" ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo "INTEGRAÇÃO: " . $resultado_treinamento[0]['treinamento'] . "" ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo "EMPRÉSTIMO: " . $resultado_emprestimo[0]['emprestimo'] . "" ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <th colspan="2"> RECOLHIMENTO </th>
          <th colspan="2"> DESCONTOS </th>
        </tr>
        <tr>
          <td>
            <?php echo "HEADSETS DEVOLVIDOS: " . $resultado_devolvidos[0]['devolvidos'] . "" ?>
          </td>
          <td>
            <?php echo "HEADSETS NÃO RECOLHIDOS: " . $resultado_nao_devolvidos[0]['nao_devolvidos'] . "" ?>
          </td>
          <td>
            <?php echo "DESCONTOS POR NÃO DEVOLUÇÃO: " . $resultado_descontos_devolucao[0]['nao_devolvidos'] . "" ?>
          </td>
          <td>
            <?php echo "DESCONTOS POR DANOS OU PERCA: " . $resultado_descontos[0]['descontos'] . "" ?>
          </td>
        </tr>
      </table>
    </div>
  </div>
  <div class="footer"></div>
  <script>
    // ESTOQUE  
    var dados = <?php echo $json_estoque_manutencao; ?>;

    var data = {
      labels: Object.keys(dados),
      datasets: [{
        data: Object.values(dados),
        backgroundColor: [
          'rgb(255, 121, 60)',
          'rgb(12, 35, 67)',
          'rgb(15,125,162)'
        ]
      }]
    };
    var options = {
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var dataset = data.datasets[tooltipItem.datasetIndex];
            var label = data.labels[tooltipItem.index];
            var value = dataset.data[tooltipItem.index];
            return label + ': ' + value;
          }
        }
      }
    }


    var myChart = new Chart(document.getElementById('estoque_manutencao'), {
      type: 'pie',
      data: data,
      options: options
    });
  </script>
  <script>
    // MOVIMENTACOES  
    var dados = <?php echo $json_movimentacoes; ?>;

    var data = {
      labels: Object.keys(dados),
      datasets: [{
        data: Object.values(dados),
        backgroundColor: [
          'rgb(255, 121, 60)',
          'rgb(12, 35, 67)',
          'rgb(15,125,162)',
          'rgb(52, 52, 52)'
        ]
      }]
    };
    var options = {
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var dataset = data.datasets[tooltipItem.datasetIndex];
            var label = data.labels[tooltipItem.index];
            var value = dataset.data[tooltipItem.index];
            return label + ': ' + value;
          }
        }
      }
    }


    var myChart = new Chart(document.getElementById('movimentacoes'), {
      type: 'pie',
      data: data,
      options: options
    });
  </script>
</body>

</html>