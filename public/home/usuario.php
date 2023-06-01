<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title>USUÁRIO</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Estilos -->
    <link rel="stylesheet" type="text/css" href="../../css/header-style.css">
    <link rel="stylesheet" href="../../css/usuario-style.css">
    <link rel="icon" href="../img/icon.ico">

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            <form>
                <p class="informacoes">
                    <input type="hidden" id="id" value="<?php echo $_COOKIE['id'] ?>">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" class="nome_usuario" state="readonly">
                    <label for="login_plansul">Login plansul:</label>
                    <input type="text" id="login_plansul" state="readonly" >
                    <label for="login_caixa">Login caixa:</label>
                    <input type="text" id="login_caixa" state="readonly" >
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha">
                </p>
                <p class="troca_senha">
                    <label for="nova_senha">Nova senha:</label>
                    <input type="password" id="nova_senha">
                    <label for="confirmar_senha">Confirmar senha:</label>
                    <input type="password" id="confirmar_senha">
                </p>
                <button class="atualizar" id="atualizar">Atualizar</button>
            </form>
        </div>
    </div>
    <div class="footer">
        <h6>Criado e mantido por Daniel Lopes Manfrini: 2023</h6>
    </div>
</body>

<script>
    $(document).ready(function () {
        $('#nome').val('<?php echo $_COOKIE['nome'] ?>')
        $('#login_plansul').val('<?php echo $_COOKIE['login_plansul'] ?>')
        $('#login_caixa').val('<?php echo $_COOKIE['login_caixa'] ?>')
    }),

        $('#senha').on('keyup', verifica_senha);
    $('#nova_senha').on('keyup', verifica_senha);
    $('#confirmar_senha').on('keyup', verifica_senha);
    $('#senha').on('keydown', verifica_senha);
    $('#nova_senha').on('keydown', verifica_senha);
    $('#confirmar_senha').on('keydown', verifica_senha);

    function verifica_senha() {

        // coletamos os objetos nescessários.
        var senha_antiga = $('#senha')
        var nova_senha = $('#nova_senha');
        var confirmar_senha = $('#confirmar_senha');
        var atualizar = $('#atualizar');

        console.log('nova: ' + nova_senha.val() + ' confirmacao: ' + confirmar_senha.val())

        // Se a senha nova e a confirmação não forem iguais os campos vão ser vermelhos.
        if (nova_senha.val() !== confirmar_senha.val()) {
            console.log('Senha antiga e confirmação de senha não digitadas');

            // Remove as bordas vermelhas se houver
            confirmar_senha.removeClass('correct-border');
            nova_senha.removeClass('correct-border');

            // Adicionar bordas vermelhas
            confirmar_senha.addClass('error-border');
            nova_senha.addClass('error-border');

            // Se ainda não tiver digitado a senha antiga vai ser vermelho.
            if (senha_antiga.val() == '') {
                senha_antiga.addClass('error-border');
            }

            // Desabilita o botão.
            atualizar.addClass('error-buton');
            $('#atualizar').prop('disabled', true);

            // Se a senha nova e a confirmação forem iguais e não vazios os campos vão mudar para verde.
        } else if (nova_senha.val() == confirmar_senha.val() && nova_senha.val() !== '' && confirmar_senha.val() !== '') {
            console.log('Senha novas iguais');

            // Novas senhãs são iguais, bordas verdes
            confirmar_senha.addClass('correct-border');
            nova_senha.addClass('correct-border');

            // Somente se a senha antiga for digitada habilita novamente o botão.
            if (senha_antiga.val() !== '') {
                console.log('Senha antiga digitada')

                // remove a borda vermelha, porém não adiciona a verde pois não há como saber se está correta até o momento do insert
                senha_antiga.removeClass('error-border');

                atualizar.removeClass('error-buton');
                $('#atualizar').prop('disabled', false);
            }

            // Se as senhas novas forem apagadas.
        } else if (nova_senha.val() == '' && confirmar_senha.val() == '') {
            console.log('Campos de senha limpos')

            // Removemos qualquer classe
            confirmar_senha.removeClass('correct-border');
            nova_senha.removeClass('correct-border');
            confirmar_senha.removeClass('error-border');
            senha_antiga.removeClass('error-border');
            senha_antiga.removeClass('error-border');
            atualizar.removeClass('error-buton');
            $('#atualizar').prop('disabled', false);

        }
    };

    // Função ajax para atualizar os dados.
    $('#atualizar').on('click', function (event) {
        event.preventDefault();

        id = $('#id').val()
        senha_antiga = $('#senha').val()
        nova_senha = $('#nova_senha').val()

        $.ajax({
            url: '../funcoes/home/atualiza_dados_funcionario.php',
            method: 'POST',
            data: {
                id: id,
                senha_antiga: senha_antiga,
                nova_senha: nova_senha,
            },
            success: async function (response) {
                if (response['erro'] == false) {
                    await swal({
                        title: "Sucesso!",
                        text: "Dados atualizados!",
                        icon: "success",
                        button: false,
                        timer: 1500
                    });
                } else {
                    await swal({
                        title: "Erro!",
                        text: response['msg'],
                        icon: "error",
                        button: false,
                        timer: 1500
                    });
                }

                $('#senha').val('')
                $('#nova_senha').val('')
                $('#confirmar_senha').val('')

            },
            error: async function (response) {
                // Tratar erros na requisição AJAX
                console.log(response)
                await swal({
                    title: "Erro!",
                    text: "Contate o administrador!",
                    icon: "error",
                    button: false,
                    timer: 1500
                });
                $('#senha').val('')
                $('#nova_senha').val('')
                $('#confirmar_senha').val('')
            }
        });
    })
</script>

</html>