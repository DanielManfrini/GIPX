<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ATUALIZAR REDE</title>

  <!-- Estilos -->
  <link rel="stylesheet" type="text/css" href="/archerx/css/header-style.css">
  <link rel="stylesheet" type="text/css" href="/archerx/css/atualizar/atualizar_rede-style.css">
  <link rel="icon" href="/archerx/public/img/icon.ico">

  <!-- Biblioteca jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <!-- Biblioteca DataTables -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

  <!-- Sweet Alert -->
  <link type="text/css" href="/archerx/bibli/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

  <!-- Sweet Alerts 2 -->
  <script src="/archerx/bibli/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="/archerx/bibli/sweetalert/dist/sweetalert.min.js"></script>

  <!-- Search input -->
  <script src="/archerx/bibli/searchinput/search.input.js"></script>
  <!-- Ultima tentativa de realizar buscas em inputs -->
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
  <div class=container>
    <div class="container_interno">
      <table id="tabela_rede">
        <thead>
          <tr class="header_tabela">
            <th>BAIA</th>
            <th>PATCH</th>
            <th>HOST</th>
            <th>IP</th>
            <th>PORTA</th>
            <th>AÇÃO</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
  <script>
    $(document).ready(function () {
      tabelarede = $('#tabela_rede').DataTable({
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
        },
        columns: [
          {
            data: 'baia',
          },
          {
            data: 'pach_panel',
            render: function (data, type, row, meta) {
              return '<input type="text" class="input-pach_panel" value="' + data + '" style="width: 100px;">';
            }
          },
          {
            data: 'switch_host',
            render: function (data, type, row, meta) {
              return '<input type="text" class="input-switch_host" value="' + data + '" style="width: 100px;">';
            }
          },
          {
            data: 'switch_ip',
            render: function (data, type, row, meta) {
              return '<input type="text" class="input-switch_ip" value="' + data + '" style="width: 100px;">';
            }
          },
          {
            data: 'switch_porta',
            render: function (data, type, row, meta) {
              return '<input type="text" class="input-switch_porta" value="' + data + '" style="width: 100px;">';
            }
          },
          {
            targets: -1, // Última coluna
            orderable: false, // Não permite ordenação
            className: 'dt-body-center', // Centraliza o conteúdo da coluna
            render: function (data, type, row, meta) {
              return '<button class="btn-update" data-id="' + row.id + '">Atualizar</button>';
            }
          }
        ],
        ajax: {
          url: '/archerx/public/funcoes/relatorio/obter_dados_rede.php',
          dataSrc: ''
        },
        rowId: 'baia',
        initComplete: function () {
          this.api().search('', true, false).draw(); // Executar a pesquisa inicial

          // Configurar o plug-in "Search Input"
          $('.dataTables_filter input').searchInput();
        }
      });

      // Evento de clique no botão de atualização
      $('#tabela_rede tbody').on('click', '.btn-update', function () {
        var id = $(this).data('id');
        // as variaveis abaixo são apenas para recolher a baia pois ela não está em um input, apenas na célula
        var tr = $(this).closest('tr');
        var row = tabelarede.row(tr);
        var data = row.data();
        var baia = data.baia;
        console.log(baia)
        // Coletar o resto das variáveis
        var pach_panel = $(this).closest('tr').find('.input-pach_panel').val();
        var switch_host = $(this).closest('tr').find('.input-switch_host').val();
        var switch_ip = $(this).closest('tr').find('.input-switch_ip').val();
        var switch_porta = $(this).closest('tr').find('.input-switch_porta').val();
        // Realizar a ação de atualização para o ID correspondente
        // ...
        $.ajax({
          url: '/archerx/public/funcoes/atualizar/atualiza_js.php',
          method: 'POST',
          data: {
            tipo: 'rede',
            baia: baia,
            pach_panel: pach_panel,
            switch_host: switch_host,
            switch_ip: switch_ip,
            switch_porta: switch_porta,
          },
          success: async function (response) {
            console.log(response)
            await swal({
              title: "Sucesso!",
              text: "Baia atualizada!",
              icon: "success",
              button: false,
              timer: 1500
            });
            tabelarede.ajax.reload(null, false);
          },
          error: async function (response) {
            // Tratar erros na requisição AJAX
            console.log(response)
            await swal({
              title: "Erro!",
              text: response['msg'],
              icon: "error",
              button: false,
              timer: 1500
            });
            tabelarede.ajax.reload(null, false);
          }
        });
      });
    });
  </script>

  <div class="footer">
    <h6>Criado e mantido por Daniel Lopes Manfrini: 2023</h6>
  </div>
</body>

</html>