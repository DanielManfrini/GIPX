<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <title>RELATORIO REDE</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
  <link rel="stylesheet" href="/archerx/css/relatorio/relatorio-style.css">
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
      <table id="tabela_rede">
        <thead>
          <tr class="header_tabela">
            <th>BAIA</th>
            <th>PATCH</th>
            <th>HOST</th>
            <th>IP</th>
            <th>PORTA</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <script>
      $(document).ready(function() {
        tabelarede = $('#tabela_rede').DataTable({
          "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
          },
          columns: [{
              data: 'baia'
            },
            {
              data: 'pach_panel'
            },
            {
              data: 'switch_host'
            },
            {
              data: 'switch_ip'
            },
            {
              data: 'switch_porta'
            },
          ],
          ajax: {
            url: '../funcoes/relatorio/obter_dados_rede.php',
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
    </script>
    <div class="footer">
      <h6>Criado e mantido por Daniel Lopes Manfrini: 2023</h6>
    </div>
</body>

</html>