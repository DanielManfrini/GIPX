<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RELATORIO GPS</title>

  <!-- Biblioteca jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!-- Biblioteca DataTables -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="//cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
  <script src="//cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
  <script src="//cdn.datatables.net/rowgroup/1.1.4/js/dataTables.rowGroup.min.js"></script>
  <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />

  <!-- Estilos -->
  <link rel="stylesheet" type="text/css" href="/archerx/css/header-style.css">
  <link rel="stylesheet" type="text/css" href="/archerx/css/relatorio/relatorio-style.css">
  <link rel="icon" href="/archerx/public/img/icon.ico">

  <!-- Sweet Alert -->
  <link type="text/css" href="../../bibli/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

  <!-- Sweet Alerts 2 -->
  <script src="../../bibli/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="../../bibli/sweetalert/dist/sweetalert.min.js"></script>
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
  <div class="container">
    <div class="container_interno">
      <table id="tabela_gps">
        <thead>
          <tr class="header_tabela">
            <td><span style="font-weight:bold;">Baia</span></td>
            <td><span style="font-weight:bold;">Setor</span></td>
            <td><span style="font-weight:bold;">Ramal Dg</span></td>
            <td><span style="font-weight:bold;">Hostname</span></td>
            <td><span style="font-weight:bold;">Serial</span></td>
            <td><span style="font-weight:bold;">Monitor</span></td>
            <td><span style="font-weight:bold;">QDU</span></td>
            <td><span style="font-weight:bold;">Técnico</span></td>
            <td class="fim_tabela"><span style="font-weight:bold;">Data</span></td>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <div id="tabela_detalhes"></div>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      tabelaGPS = $('#tabela_gps').DataTable({
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
        },
        columns: [{
            data: 'baia'
          },
          {
            data: 'setor'
          },
          {
            data: 'ramal'
          },
          {
            data: 'hostname'
          },
          {
            data: 'serial'
          },
          {
            data: 'serie'
          },
          {
            data: 'qdu_serial'
          },
          {
            data: 'nome',
            width: '350px'
          },
          {
            data: 'data',
            width: '150px'
          },
        ],
        ajax: {
          url: '../funcoes/relatorio/obter_dados_gps.php',
          dataSrc: ''
        },
        buttons: [{
          extend: 'csv', // Adiciona o botão de exportação CSV
          text: 'Exportar CSV', // Texto do botão
          customize: function(csv) {
            // Formata o CSV em UTF-8
            csv = "\uFEFF" + csv;

            // Altera o separador para ";"
            csv = csv.replace(/,/g, ";");

            // Remove as aspas das strings
            csv = csv.replace(/"/g, "");

            return csv;
          }
        }],
        dom: 'lBfrtip', // Especifica a posição dos botões
        rowId: 'baia'
      });
    });

    $('#tabela_gps tbody').on('click', 'tr', function() {
      var dadosLinha = tabelaGPS.row(this).data(); // Obtenha os dados da linha selecionada
      var baiaLinha = dadosLinha['baia']; // Obtenha o valor da coluna 'baia'

      // Verifique se os detalhes já foram carregados
      if (tabelaGPS.row(this).child.isShown()) {
        // Os detalhes já estão visíveis, então os esconde
        tabelaGPS.row(this).child.hide();
        $(this).removeClass('details');
      } else {
        // Os detalhes não estão visíveis, então os carrega e os exibe
        tabelaGPS.row(this).child(obterDetalhesHistorico(baiaLinha)).show();
        $(this).addClass('details');
      }
    });

    // Função para obter os detalhes do histórico do GPS
    async function obterDetalhesHistorico(baiaLinha) {
      // Aqui você deve fazer uma requisição AJAX para obter os dados do histórico
      // com base no ID da linha selecionada. Por exemplo:

      console.log(baiaLinha)

      // Fazer uma requisição AJAX para obter os dados do histórico
      await $.ajax({
        url: '../funcoes/relatorio/obter_historico_gps.php',
        method: 'GET',
        data: {
          id: baiaLinha
        },
        success: function(response) {
          console.log(response)
          // Dados do histórico obtidos com sucesso
          ///var historico = response.dados; // Supondo que os dados do histórico são retornados no formato { dados: [...] }
          //console.log(historico)
          // Aqui você pode formatar os dados do histórico para exibição na tabela de detalhes.
          // Por exemplo, você pode criar uma tabela HTML ou usar DataTables para exibir o histórico.
          var linhasHTML = '';
          response.forEach(function(item) {
            console.log(item)
            linhasHTML += '<tr>' +
              '<td>' + item.baia + '</td>' +
              '<td>' + item.setor + '</td>' +
              '<td>' + item.ramal_dg + '</td>' +
              '<td>' + item.hostname + '</td>' +
              '<td>' + item.serial + '</td>' +
              '<td>' + item.serie + '</td>' +
              '<td>' + item.qdu_serial + '</td>' +
              '<td>' + item.nome + '</td>' +
              '<td>' + item.data + '</td>' +
              '</tr>';
          });
          // Criar a tabela de detalhes completa
          var tabelaDetalhesHTML = '<table  class="linha_historico" >' +
            '<thead>' +
            '<tr class="header_tabela">' +
            '<td colspan=9 class="fim_tabela" ><span style="font-weight:bold;">Histórico</span></td>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            linhasHTML +
            '</tbody>' +
            '</table>';

          // Atualizar a tabela de detalhes com o HTML criado
          //return tabelaDetalhesHTML
          console.log(tabelaDetalhesHTML)
          var row = tabelaGPS.row('#' + baiaLinha);
          console.log(row)
          row.child(tabelaDetalhesHTML).show()
        },
        error: function() {
          // Tratar erros na requisição AJAX
          console.error('Erro ao obter o histórico do GPS');
        }
      });
    }
  </script>
  <div class="footer">
    <h6>Criado e mantido por Daniel Lopes Manfrini: 2023</h6>
  </div>
</body>

</html>