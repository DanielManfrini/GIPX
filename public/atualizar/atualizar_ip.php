<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ATUALIZAR TABELA IP</title>

  <!-- Estilos -->
  <link rel="stylesheet" type="text/css" href="/archerx/css/header-style.css">
  <link rel="stylesheet" type="text/css" href="/archerx/css/atualizar/atualizar_ip-style.css">
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
      <table id="tabela_ip">
        <thead>
          <tr class="header_tabela">
            <th>ip</th>
            <th>NOME</th>
            <th>DESCRIÇÃO</th>
            <th>ATIVO</th>
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
      tabelaip = $('#tabela_ip').DataTable({
        language: {
          url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
        },
        columnDefs: [
          { type: "num", "targets": [0] }
        ],
        order: [[0, "asc"]],
        columns: [
          {
            data: 'ip'
          },
          {
            data: 'nome',
            render: function (data, type, row, meta) {
              return '<input type="text" class="input-nome" value="' + data + '" style="width: 300px;">';
            }
          },
          {
            data: 'descricao',
            render: function (data, type, row, meta) {
              return '<input type="text" class="input-descricao" value="' + data + '" style="width: 400px;">';
            }
          },
          {
            data: 'status',
            render: function (data, type, row, meta) {
              return '<select name="status" class="select-status" style="width: 50px" ><option selected"' + data + '">' + data + '</option>; <option value="1">SIM</option>; <option value="0">NÃO</option></select>';
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
          url: '/archerx/public/funcoes/relatorio/obter_dados_tabela_ip.php',
          dataSrc: ''
        },
        rowId: 'id',
      });

      // Evento de clique no botão de atualização
      $('#tabela_ip tbody').on('click', '.btn-update', function () {
        // as variaveis abaixo são apenas para recolher a baia pois ela não está em um input, apenas na célula
        var tr = $(this).closest('tr');
        var row = tabelaip.row(tr);
        var data = row.data();
        var ip = data.ip;
        // Coletar o resto das variáveis
        var nome = $(this).closest('tr').find('.input-nome').val();
        var descricao = $(this).closest('tr').find('.input-descricao').val();
        var status = $(this).closest('tr').find('.select-status').val();
        
        console.log("Dados à enviar:",ip,nome,descricao,status)


        $.ajax({
          url: '/archerx/public/funcoes/atualizar/atualiza_js.php',
          method: 'POST',
          data: {
            tipo: 'ip',
            ip: ip,
            nome: nome,
            descricao: descricao,
            status: status,
          },
          success: async function (response) {
            console.log(response)
            await swal({
              title: "Sucesso!",
              text: "ip atualizado!",
              icon: "success",
              button: false,
              timer: 1500
            });
            tabelaip.ajax.reload(null, false);
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
            tabelaip.ajax.reload(null, false);
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