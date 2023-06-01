<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <title>FUNCIONÁRIOS</title>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Estilos -->
  <link rel="stylesheet" type="text/css" href="/archerx/css/header-style.css" />
  <link rel="stylesheet" type="text/css" href="/archerx/css/gerencia/funcionarios-style.css" />
  <link rel="icon" href="../img/icon.ico">

  <!-- Biblioteca jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <!-- Biblioteca jQuery UI-->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- Biblioteca DataTables -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="/archerx/bibli/dataTables/Buttons-2.3.6/js/dataTables.buttons.min.js"></script>

  <!-- CSS DataTables -->
  <link rel="stylesheet" href="/archerx/bibli/dataTables/Buttons-2.3.6/css/buttons.dataTables.min.css">
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
      <div class="container_funcionario">
        <div class="funcionario" id="funcionario">
          <h3 class="titulo">FUNCIONÁRIO</h3>
          <p class="matricula">
            <label for="matricula">Matricula:</label>
            <input type="text" name="matricula" id="matricula" />
            <button id="buscar">Buscar</button>
          </p>
          <p class="nome">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" />
          </p>
          <p class="setor">
            <label for="setor">Setor:</label>
            <select name="setor" id="setor">;
              <option value=""></option>
              <option value="TESTE">TESTE</option>;
              <option value="SUPORTE DE TI">SUPORTE DE TI</option>;
            </select>
          </p>
          <P class="cartao_pis">
            <label for="cartao">Cartao:</label>
            <input type="text" name="cartao" id="cartao" />
            <label for="pis">Pis:</label>
            <input type="text" id="pis">
          </P>
          <p class="situacao">
            <label for="situacao">Situação:</label>
            <input type="text" name="situacao" id="situacao" />
            <label for="demissao">demissão:</label>
            <input type="text" name="demissao" id="demissao" />
          </p>
          <P>
          <div class="cadastro">
            <button id="cadastrar">Cadastrar</button>
            <button id="atualizar">Atualizar</button>
            <button id="baixa">Baixa</button>
            <button id="excluir">Excluir</button>
          </div>
          </P>
        </div>
        <div class="container_opcoes">
          <h3 class="titulo_opcoes">OPÇÕES</h3>
          <button id="provisorio">Provisório</button>
          <button id="bloqueio">Bloqueio</button>
          <button id="headsets">Headsets</button>
        </div>
      </div>

      <div class="container_cartoes" id="container_cartoes">
        <form class="cartoes">
          <h3 class="titulo_cartoes">CARTÕES</h3>
          <p class="cartao_provisorio" >
            <label for="cartao_provisorio">Cartão:</label>
            <input type="text" name="cartao_provisorio" id="cartao_provisorio" />
            <button id="associar">Associar</button>
            <button id="dessassociar">Dessassociar</button>
          </p>
          <p class="data_provisorio" >
            <label for="data_inicial_cartao">Inicio:</label>
            <input type="date" name="data_inicial_cartao" id="data_inicial_cartao" />
            <label for="data_final">Fim:</label>
            <input type="date" name="data_final_cartao" id="data_final_cartao" />
          </p>
        </form>
      </div>

      <div class="container_bloqueio" id="container_bloqueio">
        <form class="bloqueio">
          <h3 class="titulo_bloqueio">ENTRADA NA EMPRESA</h3>
          <p class="situacao">
            <label for="situacao_bloqueio">Situação:</label>
            <input type="text" readonly='' id="situacao_bloqueio" />
            <button id="bloquear">Bloquear</button>
            <button id="desbloquear">Desbloquear</button>
          </p>
          <p class="data">
            <label for="data_inicial_bloqueio">Inicio:</label>
            <input type="date" name="data_inicial" id="data_inicial_bloqueio" />
            <label for="data_final_bloqueio">Fim:</label>
            <input type="date" name="data_final" id="data_final_bloqueio" />
          </p>
        </form>
      </div>

      <!-- Modal headsets -->
      <div class="modal_head" id="modal_head" title="Gerência de Headsets!">
        <div class="container_heads" id="container_heads">
          <form class="heads">
            <h3 class="titulo_head_atual">HEADSET</h3>
            <p class="head_atual">
              <label for="headset_atual">Atual:</label>
              <input type="text" id="headset_aual" />
            </p>
            <p class="head_atual">
              <label for="situacao_head">Situação:</label>
              <input type="text" id="situacao_head" />
            </p>
            <h3 class="titulo_movimentacoes">MOVIMENTAÇÕES</h3>
            <p class="head_novo">
              <label for="opcao">Motivo:</label>
              <select id="opcao">
                <option value=""></option>
                <option value="troca">TROCA</option>
                <option value="entrega">ENTREGA</option>
                <option value="integracao">INTEGRAÇÃO</option>
                <option value="emprestimo">EMPRÉSTIMO</option>
              </select>
            <p class="head_novo">
              <label for="headset_novo">Headset:</label>
              <input type="text" id="headset_novo" />
            </p>
            <p class="head_novo">
              <label class="label_chamado" for="campo_chamado">Chamado:</label>
              <input type="text" id="campo_chamado" />
            </p>
            <p class="head_novo">
              <label for="desconto">Desconto:</label>
              <select id="desconto">
                <option value=""></option>
                <option value="danos">DANOS</option>
                <option value="perca">PERCA</option>
              </select>
            </p class="head_novo">
          </form>
          <div class="container_opcoes_heads">
            <form class="opcoes_heads">
              <h3 class="titulo_opcoes_movimentacoes">OPÇÕES</h3>
              <h5>MOVIMENTAÇÕES</h5>
              <p class="acao_botoes">
                <button id="atualizar">Atualizar</button>
                <button id="baixa_head">Baixa</button>
              </p>
              <h5>EMPRÉSTIMOS</h5>
              <p class="acao_botoes">
                <button id="baixa_emprestimo">Baixa</button>
              </p>
              <h5>DESCONTO</h5>
              <p class="acao_botoes">
                <button id="desconto">Não Devolvido</button>
                <button id="devolvido">Devolvido</button>
              </p>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="footer">
    <h6>Criado e mantido por Daniel Lopes Manfrini: 2023</h6>
  </div>
  <script>
    $(document).ready(function () {
      // Configuração do modal!
      $("#modal_head").dialog({
        autoOpen: false, // Não abrir automaticamente ao carregar a página
        modal: true, // Modal com sobreposição
        width: 400, // Definir a largura do modal para 400 pixels
      });
      // Abrir o modal ao clicar em um botão
      $("#headsets").click(function () {
        $("#modal_head").dialog("open");
      });
    });

    $('#buscar').on('click', buscar_dados);
    $('#matricula').on('keyup',buscar_dados);

    function buscar_dados(){

      var matricula = $('#matricula').val()

      $.ajax({
        url: '/archerx/public/funcoes/gerencia_funcionarios.php',
        method: 'GET',
        data: {
          tipo: 'Select_funcionario',
          matricula: matricula
        },
        success: async function (response) {
          console.log(response)

          $('#nome').val(response.nome)
          $('#setor').val(response.departamento)
          $('#cartao').val(response.cartao)
          $('#pis').val(response.pis)
          $('#situacao').val(response.situacao_funcionario)
          $('#demissao').val(response.data_demissao)
          $('#cartao_provisorio').val(response.provisorio)
          $('#data_inicial_cartao').val(response.inicio_provisorio)
          $('#data_final_cartao').val(response.fim_provisorio)
          $('#situacao_bloqueio').val(response.bloqueio)
          $('#data_inicial_bloqueio').val(response.inicio_bloqueio)
          $('#data_final_bloqueio').val(response.fim_bloqueio)

        }
      });
    };

    $('#provisorio').on('click',function(){
      $('.container_cartoes').css('display', 'grid')
      $('.container_bloqueio').css('display', 'none')
    })
    $('#bloqueio').on('click',function(){
      $('.container_cartoes').css('display', 'none')
      $('.container_bloqueio').css('display', 'grid')
    })
  </script>
</body>

</html>