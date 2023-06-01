<?php
// pegar o dia
if (!isset($_POST['data'])) {
  $data = date('Y-m-d');
} else {
  $data = $_POST['data'];
}

include('../conexoes/conexaoPOSTSQL.php');
$sql = "SELECT data_abertura,tipoatendimento FROM public.relatorio18 WHERE date(data_abertura) = '$data'";
try {
  $cursor = $postgres->prepare($sql);
  $cursor->execute();

  $quantidade_linhas = $cursor->rowCount();
} catch (PDOException $e) {
  echo "Erro na conexão: " . $e->getMessage();
}
$sql_chamados = "SELECT tipoatendimento, COUNT(*) as num_ocorrencias FROM public.relatorio18 WHERE date(data_abertura) = '$data' GROUP BY tipoatendimento;";
try {
  $cursor = $postgres->prepare($sql_chamados);
  $cursor->execute();

  $resultado = $cursor->fetchAll(PDO::FETCH_ASSOC);

  $tipo = array();
  $chamados = array();

  foreach ($resultado as $row) {

    $tipo[] = $row["tipoatendimento"];
    $chamados[] = $row["num_ocorrencias"];
  }

  $json_tipo = json_encode($tipo);
  $json_chamados = json_encode($chamados);
} catch (PDOException $e) {
  echo "Erro na conexão: " . $e->getMessage();
}
$sql_tecnicos = "SELECT responsavel, COUNT(*) as num_ocorrencias FROM public.relatorio18 WHERE date(data_abertura) = '$data' AND responsavel IS NOT NULL GROUP BY responsavel;";
try {
  $cursor = $postgres->prepare($sql_tecnicos);
  $cursor->execute();

  $resultado = $cursor->fetchAll(PDO::FETCH_ASSOC);

  $tecnicos = array();
  $chamados = array();

  foreach ($resultado as $row) {

    $tecnicos[] = $row["responsavel"];
    $chamados_tecnicos[] = $row["num_ocorrencias"];
  }

  $json_tecnicos = json_encode($tecnicos);
  $json_chamados_tecnicos = json_encode($chamados_tecnicos);
} catch (PDOException $e) {
  echo "Erro na conexão: " . $e->getMessage();
}
$sql_supervisores = "SELECT solicitante, COUNT(*) as num_ocorrencias FROM public.relatorio18 WHERE date(data_abertura) = '$data' AND solicitante IS NOT NULL GROUP BY solicitante;";
try {
  $cursor = $postgres->prepare($sql_supervisores);
  $cursor->execute();

  $resultado = $cursor->fetchAll(PDO::FETCH_ASSOC);

  $supervisores = array();
  $chamados_supervisores = array();

  foreach ($resultado as $row) {

    $supervisores[] = $row["solicitante"];
    $chamados_supervisores[] = $row["num_ocorrencias"];
  }

  $json_supervisores = json_encode($supervisores);
  $json_chamados_supervisores = json_encode($chamados_supervisores);
} catch (PDOException $e) {
  echo "Erro na conexão: " . $e->getMessage();
}
$cursor->closeCursor();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Archerx integration</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="refresh" content="600" />

  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Sweet Alert -->
  <link type="text/css" href="../../bibli/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

  <!-- Sweet Alerts 2 -->
  <script src="../../bibli/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="../../bibli/sweetalert/dist/sweetalert.min.js"></script>
  <!-- Estilos -->
  <link rel="stylesheet" type="text/css" href="/archerx/css/header-style.css">
  <link rel="stylesheet" href="/archerx/css/dashboards/chamados-style.css">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
  }
  include('../includes/navbar.php');

  ?>
  <div class="pq">
    <div class="quantidade">
      <p>
      <form method="post" action="chamados.php">
        <input type="date" name="data" id="data" value="<?php echo $data ?>" />
        <input type="submit" value="Buscar">
      </form>
      </p>
      <h2>QUANTIDADE DE CHAMADOS ABERTOS.</h2>
      <br>
      <p>
      <h1>
        <?php echo $quantidade_linhas ?>
      </h1>
      </p>
    </div>
    <div class="tecnicos" id="tecnicos"></div>
    <script type="text/javascript">
      // Carrega a biblioteca do Google Charts
      google.charts.load('current', {
        'packages': ['corechart']
      });

      // Chama a função para desenhar o gráfico quando a biblioteca do Google Charts estiver carregada
      google.charts.setOnLoadCallback(drawChart);

      // Função para desenhar o gráfico
      function drawChart() {
        // Cria um novo objeto DataTable do Google Charts, e adiciona as colunas ET e Contagem
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'resposavel');
        data.addColumn('number', 'atendimentos');

        // Adiciona as linhas com os valores de ET e Contagem que foram obtidos do PHP
        var resposavel = <?php echo $json_tecnicos; ?>;
        var atendimentos_tecnicos = <?php echo $json_chamados_tecnicos; ?>;
        console.log(atendimentos_tecnicos);
        for (var i = 0; i < resposavel.length; i++) {
          data.addRow([resposavel[i], atendimentos_tecnicos[i]]);
        }

        // Opções de configuração do gráfico
        var options = {
          title: 'CHAMADOS ATENDIDOS POR TÉCNICOS.',
          width: 1200,
          height: 500,
          legend: {
            position: 'none'
          },
          hAxis: {
            title: 'TÉCNICO'
          },
          vAxis: {
            title: 'CHAMADOS'
          }
        };

        // Cria um novo objeto de gráfico de barras do Google Charts, e o anexa ao elemento com o ID "chart_div"
        var chart = new google.visualization.ColumnChart(document.getElementById('tecnicos'));
        chart.draw(data, options);
      }
    </script>
    <div class="supervisores" id="supervisores"></div>
    <script type="text/javascript">
      // Carrega a biblioteca do Google Charts
      google.charts.load('current', {
        'packages': ['corechart']
      });

      // Chama a função para desenhar o gráfico quando a biblioteca do Google Charts estiver carregada
      google.charts.setOnLoadCallback(drawChart);

      // Função para desenhar o gráfico
      function drawChart() {
        // Cria um novo objeto DataTable do Google Charts, e adiciona as colunas ET e Contagem
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'supervisores');
        data.addColumn('number', 'atendimentos');

        // Adiciona as linhas com os valores de ET e Contagem que foram obtidos do PHP
        var supervisores = <?php echo $json_supervisores; ?>;
        var atendimentos_supervisores = <?php echo $json_chamados_supervisores; ?>;
        for (var i = 0; i < supervisores.length; i++) {
          data.addRow([supervisores[i], atendimentos_supervisores[i]]);
        }

        // Opções de configuração do gráfico
        var options = {
          title: 'CHAMADOS ABERTOS POR SUPERVISORES.',
          width: 1200,
          height: 500,
          legend: {
            position: 'none'
          },
          hAxis: {
            title: 'SUPERVISORES'
          },
          vAxis: {
            title: 'CHAMADOS'
          }
        };

        // Cria um novo objeto de gráfico de barras do Google Charts, e o anexa ao elemento com o ID "chart_div"
        var chart = new google.visualization.ColumnChart(document.getElementById('supervisores'));
        chart.draw(data, options);
      }
    </script>
    <div class="tipo" id="tipo"></div>
    <script type="text/javascript">
      // Carrega a biblioteca do Google Charts
      google.charts.load('current', {
        'packages': ['corechart']
      });

      // Chama a função para desenhar o gráfico quando a biblioteca do Google Charts estiver carregada
      google.charts.setOnLoadCallback(drawChart);

      // Função para desenhar o gráfico
      function drawChart() {
        // Cria um novo objeto DataTable do Google Charts, e adiciona as colunas ET e Contagem
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'tipo');
        data.addColumn('number', 'atendimentos');

        // Adiciona as linhas com os valores de ET e Contagem que foram obtidos do PHP
        var tipo = <?php echo $json_tipo; ?>;
        var atendimentos = <?php echo $json_chamados; ?>;
        for (var i = 0; i < tipo.length; i++) {
          data.addRow([tipo[i], atendimentos[i]]);
        }

        // Opções de configuração do gráfico
        var options = {
          title: 'QUANTIDADE DE CHAMADOS POR TIPO.',
          width: 1200,
          height: 500,
          legend: {
            position: 'none'
          },
          hAxis: {
            title: 'TIPO'
          },
          vAxis: {
            title: 'CHAMADOS'
          }
        };

        // Cria um novo objeto de gráfico de barras do Google Charts, e o anexa ao elemento com o ID "chart_div"
        var chart = new google.visualization.ColumnChart(document.getElementById('tipo'));
        chart.draw(data, options);
      }
    </script>
  </div>
  <div class="footer"></div>
</body>

</html>