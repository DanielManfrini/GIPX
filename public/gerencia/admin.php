<!DOCTYPE html>
<html lang="pt-BR">

<head>

  <title>ACESSOS</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Estilos -->
  <link rel="stylesheet" type="text/css" href="/archerx/css/header-style.css">
  <link rel="stylesheet" href="/archerx/css/gerencia/admin-style.css">
  <link rel="icon" href="../img/icon.ico">

  <!-- Biblioteca jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <!-- Biblioteca jQuery UI-->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- Biblioteca DataTables -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="../../bibli/dataTables/Buttons-2.3.6/js/dataTables.buttons.min.js"></script>

  <!-- CSS DataTables -->
  <link rel="stylesheet" href="../../bibli/dataTables/Buttons-2.3.6/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />

  <!-- Sweet Alert -->
  <link type="text/css" href="/archerx/bibli/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

  <!-- Sweet Alerts 2 -->
  <script src="/archerx/bibli/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="/archerx/bibli/sweetalert/dist/sweetalert.min.js"></script>

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
      <table id="tabela_usuarios">
        <thead>
          <tr class="header_tabela">
            <th>Nome</th>
            <th>Matrícula Plansul</th>
            <th>Matrícula Caixa</th>
            <th>Senha</th>
            <th>Nível</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Abaixo ficará os modais -->
  <div id="modal_cadastro" title="Formulário de Cadastro" class="modal_cadastro">
    <form>
      <label for="nome_cadastro">Nome:</label>
      <input type="text" id="nome_cadastro" name="nome_cadastro">
        <label for="login_plansul_cadastro">Matrícula Plansul:</label>
        <input type="text" id="login_plansul_cadastro" name="login_plansul_cadastro">
        <label for="login_caixa_cadastro">Matrícula Caixa:</label>
        <input type="text" id="login_caixa_cadastro" name="login_caixa_cadastro">
        <label for="senha_cadastro">Senha de acesso:</label>
        <input type="text" id="senha_cadastro" name="senha_cadastro">
      <label for="nivel">Nível de acesso:</label>
      <select class="nivel_cadastro" id="nivel_cadastro" name="nivel_cadastro">
        <option value="1">TI</option>
        <option value="2">RH</option>
        <option value="3">SUPERVISOR</option>
        <option value="4">COORDENADOR</option>
        <option value="5">GERÊNCIA</option>
      </select>
    </form>
  </div>

  <!-- Rodapé -->
  <div class="footer">
    <h6>Criado e mantido por Daniel Lopes Manfrini: 2023</h6>
  </div>
</body>

<!-- Scripts -->
<script> // Criação da tabela 
  $(document).ready(function () {
    tabela_usuarios = $('#tabela_usuarios').DataTable({
      language: {
        url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
      },
      columns: [
        {
          data: 'nome',
          render: function (data, type, row, meta) {
            return '<input type="text" class="input-nome" value="' + data + '" style="width: 350px;">'
          }
        },
        {
          data: 'login_plansul',
          render: function (data, type, row, meta) {
            return '<input type="text" class="input-login_plansul" value="' + data + '" style="width: 110px;">'
          }
        },
        {
          data: 'login_caixa',
          render: function (data, type, row, meta) {
            return '<input type="text" class="input-login_caixa" value="' + data + '" style="width: 110px;">'
          }
        },
        {
          data: 'senha',
          render: function (data, type, row, meta) {
            return '<input type="password" class="input-senha" style="width: 150px;">'
          }
        },
        {
          data: 'nivel_acesso',
          render: function (data, type, row, meta) {
            return '<select class="select-nivel"><?php if (isset($_COOKIE['ti'])) {echo '<option value="1">TI</option>';} ?><option value="2">RH</option><option value="3">SUPERVISOR</option><option value="4">COORDENADOR</option><option value="5">GERÊNCIA</option></select>'
          }
        },
        {// Adicionamos dois valores vazios e renderizamos como botões para pegar o click
          data: 'idusuarios',
          orderable: false,
          className: 'dt-body-center',
          render: function (data, type, row, meta) {
            return '<button class="btn-atualizar" value="' + data + '">Atualizar</button>';
          }
        },
        {
          data: 'idusuarios',
          orderable: false,
          className: 'dt-body-center',
          render: function (data, type, row, meta) {
            return '<button class="btn-excluir" value="' + data + '">Excluir</button>';
          }
        }
      ],

      buttons: [ // teste de botão do datatables (ficaria mais bonito se funcionasse)
        {
          text: 'Cadastrar',
          className: 'btn-cadastro',
          action: function () {
            $("#modal_cadastro").dialog("open");
          }
        }
      ],

      rowId: 'idusuarios',

      ajax: { // Envia um get para preencer a tabela
        url: '/archerx/public/funcoes/gerencia_usuarios.php',
        data: function (d) {
          d.tipo = 'select';
        },
        dataSrc: ''
      },

      createdRow: function (row, data, dataIndex) { // Para cada linha criada vamos verificar o valor da combobox
        var select = $(row).find('.select-nivel');
        select.val(data.nivel_acesso);
      },


      dom: 'lBfrtip', // Especifica a posição dos botões

    });

    // Configuração do modal!
    $("#modal_cadastro").dialog({
      autoOpen: false, // Não abrir automaticamente ao carregar a página
      modal: true, // Modal com sobreposição
      width: 400, // Definir a largura do modal para 400 pixels
      buttons: {
        "Cadastrar": function () {
          var nome_cadastro = $('#nome_cadastro').val()
          var login_plansul_cadastro = $('#login_plansul_cadastro').val()
          var login_caixa_cadastro = $('#login_caixa_cadastro').val()
          var senha_cadastro = $('#senha_cadastro').val()
          var nivel_cadastro = $('#nivel_cadastro').val()

          $.ajax({
            url: '/archerx/public/funcoes/gerencia_usuarios.php', // Função para criar.
            method: 'POST',
            data: {
              tipo: 'cadastrar',
              nome: nome_cadastro,
              login_plansul: login_plansul_cadastro,
              login_caixa: login_caixa_cadastro,
              senha: senha_cadastro,
              nivel: nivel_cadastro
            },
            success: async function (response) {
              if (response['erro'] == false) {
                await swal({
                  title: "Sucesso!",
                  text: "Usuário cadastrado!",
                  icon: "success",
                  button: false,
                  timer: 1500
                });
                tabela_usuarios.ajax.reload(null, false);
              } else {
                await swal({
                  title: "Erro!",
                  text: response['msg'],
                  icon: "error",
                  button: false,
                  timer: 1500
                });
                tabela_usuarios.ajax.reload(null, false);
              }

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
              tabela_usuarios.ajax.reload(null, false);
            }
          })
          // Lógica para salvar os dados do formulário
          $(this).dialog("close"); // Fechar o modal após salvar
        },
        "Cancelar": function () {
          $(this).dialog("close"); // Fechar o modal sem salvar
        }
      }
    });
    // Abrir o modal ao clicar em um botão
    $("#btn-abrir-modal").click(function () {
      $("#modal_cadastro").dialog("open");
    });
  })

  // Chamada para executar a função
  $('#tabela_usuarios tbody').on('click', '.btn-atualizar', function () {
    executa_botoes('atualizar', 'atualizado', this)
  })
  $('#tabela_usuarios tbody').on('click', '.btn-excluir', function () {
    executa_botoes('excluir', 'excuído', this)
  })

  function executa_botoes(tipo, acao, botao) {

    var tipo = tipo;
    var id = $(botao).val();
    var nome_botao = $('.input-nome').val();
    var login_plansul_botao = $('.input-login_plansul').val();
    var login_caixa_botao = $('.input-login_caixa').val();
    var senha_botao = $('.input-senha').val();
    var nivel_botao = $('.select-nivel').val();

    console.log('Dados a enviar: ', id, tipo, nome_botao, login_plansul_botao, login_caixa_botao, senha_botao, nivel_botao);

    $.ajax({
      url: '/archerx/public/funcoes/gerencia_usuarios.php', // Função para criar.
      method: 'POST',
      data: {
        id: id,
        tipo: tipo,
        nome: nome_botao,
        login_plansul: login_plansul_botao,
        login_caixa: login_caixa_botao,
        senha: senha_botao,
        nivel: nivel_botao
      },
      success: async function (response) {
        if (response['erro'] == false) {
          await swal({
            title: "Sucesso!",
            text: "Usuário " + acao + "!",
            icon: "success",
            button: false,
            timer: 1500
          });
          tabela_usuarios.ajax.reload(null, false);
        } else {
          await swal({
            title: "Erro!",
            text: response['msg'],
            icon: "error",
            button: false,
            timer: 1500
          });
          tabela_usuarios.ajax.reload(null, false);
        }

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
        tabela_usuarios.ajax.reload(null, false);
      }
    })
  }
</script>

</html>