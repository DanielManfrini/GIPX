<?php

if (!isset($_COOKIE['login'])) {
  echo "<script language='javascript' type='text/javascript'>
  alert('você não está logado!');window.location
  .href='login.html';</script>";
  die();
}

?>

<!DOCTYPE html>
  <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="refresh" content="180" />
        <link rel="stylesheet" type="text/css" href="/documents/scripts/archerx/css/header-style.css">
        <link rel="stylesheet" href="/documents/scripts/archerx/css/dashboard-style.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <title>Archerx integration</title>
    </head>
  <body>
  <div class="navbar">
        <a href="/documents/scripts/archerx/public/home.php"><img class="imagem" src="/documents/scripts/archerx/public/img/logo branca.png" alt width="135" height="30"></a>
            <?php if(isset($_COOKIE['admin'])){ 
            echo(
              '<div class="dropdown">
              <button class="dropbtn">GERÊNCIA
                <i class="fa fa-caret-down"></i>
              </button>
              <div class="dropdown-content">
                <a href="/documents/scripts/archerx/public/gerencia/admin.php">USUÁRIOS</a>
                <a href="/documents/scripts/archerx/public/gerencia/wyntech/login.html">ÁREA WYNTECH</a>
                <a href="/documents/scripts/archerx/public/gerencia/ponto.php">FOLHA PONTO</a>
              </div>
              </div>'
            );
            }
            ?>
            <?php if(isset($_COOKIE['admin'])){ 
            echo(
              '<div class="dropdown">
              <button class="dropbtn">ATUALIZAR
                <i class="fa fa-caret-down"></i>
              </button>
              <div class="dropdown-content-atualizar">
                <a href="/documents/scripts/archerx/public/atualizar/atualizar_gps.php">GPS</a>
                <a href="/documents/scripts/archerx/public/atualizar/atualizar_rede.php">REDE</a>
              </div>
              </div>'
            );
            }
            ?>
            <div class="dropdown">
              <button class="dropbtn">RELATÓRIO
                <i class="fa fa-caret-down"></i>
              </button>
              <div class="dropdown-content-relatorio">
                <a href="/documents/scripts/archerx/public/relatorio/relatorio_gps.php">GPS</a>
                <a href="/documents/scripts/archerx/public/relatorio/relatorio_rede.php">REDE</a>
              </div>
            </div>
            <?php 
              if(isset($_COOKIE['admin'])){ 
                echo('<div class="dropdown">
                    <button class="dropbtn">EQUIPAMENTOS
                      <i class="fa fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content-equipamentos">
                      <a href="/documents/scripts/archerx/public/equipamentos/cadastro.php">CADASTRO GPS</a>
                      <a href="#">HEADSETS</a>
                    </div>
                  </div>'
                );
              }
            ?>
            <div class="dropdown">
              <button class="dropbtn">DASHBOARDS
                <i class="fa fa-caret-down"></i>
              </button>
              <div class="dropdown-content-dashboards">
                <a href="/documents/scripts/archerx/public/dashboards/elev.php">ELEV</a>
                <a href="#">HEADSETS</a>
                <a href="/documents/scripts/archerx/public/dashboards/chamados.php">CHAMADOS</a>
              </div>
            </div>
            <a class="logoff" href="/documents/scripts/archerx/public/logoff.php">LOGOFF</a>
        </div>
      <script>
          $(".drop")
        .mouseover(function() {
        $(".dropdown").show(300);
      });
      $(".drop")
        .mouseleave(function() {
        $(".dropdown").hide(300);     
      });
      </script>
      <div class="teste" >
        <div class="containerdash">
          <h2 class="titulo">OCUPAÇÃO DE RAMAIS ELEV</h2>
            <table>
              <td ><canvas id="myChart"></canvas></td>
                <td>
                  <table class="dados">
                      <?php
                        include('conexao.php');
                        $qRamaisDisponiveis = "SELECT count(*) as disponiveis FROM archerx.integration where status = 0";
                        $result = $conn->query($qRamaisDisponiveis);
                        $ramaisDisponiveis = $result->fetch_assoc();
                        echo "<tr><th>Ramais Disponíveis</th><td>" . $ramaisDisponiveis["disponiveis"] . "</td></tr>";

                        $qRamaisOcupados = "SELECT count(*) as ocupados FROM archerx.integration where status = 1";
                        $result = $conn->query($qRamaisOcupados);
                        $RamaisOcupados = $result->fetch_assoc();
                        echo "<tr><th>Ramais Ocupados</th><td>" . $RamaisOcupados["ocupados"]. "</td></tr>";

                        $RamaisTotal= $ramaisDisponiveis["disponiveis"]+$RamaisOcupados["ocupados"];
                        $PercentualOcupado = $RamaisOcupados["ocupados"]/$RamaisTotal*100;
                        echo "<tr><th>Total de Ramais</th><td>".$RamaisTotal."</td></tr>";

                        echo "<tr><th><p><label for=\"ramais\">Ocupação: ".number_format($PercentualOcupado, 1)."%</label></th><br>";
                        echo "<td><progress id=\"ramais\" value=\"".$RamaisOcupados["ocupados"]."\" max=\"".$RamaisTotal."\"> ".$PercentualOcupado."% </progress></p></td></tr>";

                        $dados = array('OCUPADOS' => $RamaisOcupados['ocupados'],'DISPONÍVEIS' => $ramaisDisponiveis['disponiveis']);

                        $json_data = json_encode($dados);
                      ?>
                </table>
              </td>  
            </table>
          <div>
            <?php
            setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' ); 
            date_default_timezone_set( 'America/Sao_Paulo' );
            echo "<h4 class='tempo'>Última atualização: ".strftime( '%Y-%m-%e %T', strtotime('now'))."</h4>";
            ?>
          </div>
        </div>
      </div>
      <div class="footer" ></div>
        <script>
          var ctx = document.getElementById('myChart').getContext('2d');
          var data = <?php echo $json_data; ?>;

          var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
              labels: Object.keys(data),
              datasets: [{
                data: Object.values(data),
                backgroundColor: [
                  'rgba(255, 99, 132, 0.8)',
                  'rgba(54, 162, 235, 0.8)',
                ]
              }]
            },
            options: {
              responsive: false,
              maintainAspectRatio: false
            }
          });
        </script>
  </body>
</html>