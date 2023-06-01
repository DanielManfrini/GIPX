<div class="navbar">
  <a href="../home/home.php"><img class="imagem" src="../img/logo branca.png" alt width="135" height="30"></a>
  <button class="menu-icon">&#9776;</button>
  <ul class="menu">
    <?php if (isset($_COOKIE['ti']) or isset($_COOKIE['coordenador']) or isset($_COOKIE['gerente'])) {
      echo ('<div class="dropdown">
              <button class="dropbtn">GERÊNCIA
                <i class="fa fa-caret-down"></i>
              </button>
              <div class="dropdown-content">
              '. (isset($_COOKIE['ti']) ? '<a href="../wyntech/login.html">ÁREA WYNTECH</a>
                <a href="/portal/public/chamados/listar">CHAMADOS</a>
                <a href="../gerencia/funcionarios.php">FUNCIONÁRIOS</a>':'').'
                <a href="../gerencia/admin.php">USUÁRIOS</a>
              </div>
              </div>'
      );
    }
    ?>
    <?php if (isset($_COOKIE['ti'])) {
      echo ('<div class="dropdown">
              <button class="dropbtn">ATUALIZAR
                <i class="fa fa-caret-down"></i>
              </button>
              <div class="dropdown-content-atualizar">
                <a href="../atualizar/atualizar_gps.php">Tabela GPS</a>
                <a href="../atualizar/atualizar_ip.php">Tabela IP PLANSUL</a>
                <a href="../atualizar/atualizar_rede.php">Tabela REDE CAIXA</a>
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
        <?php
        if (isset($_COOKIE['ti']) or isset($_COOKIE['rh']) or isset($_COOKIE['supervisor']) or isset($_COOKIE['coordenador']) or isset($_COOKIE['gerente'])) {
          echo ('
                  <a href="../relatorio/catracas.php">CATRACAS</a>
                  <a href="../relatorio/ponto.php">FOLHA PONTO</a>
                ');
        }
        ;
        if (isset($_COOKIE['ti']) or isset($_COOKIE['coordenador']) or isset($_COOKIE['gerente'])) {
          echo ('
                  <a href="../relatorio/relatorio_gps.php">GPS</a>
                  <a href="../relatorio/relatorio_rede.php">REDE</a>
                ');
        }
        ?>
      </div>
    </div>
    <?php
    if (isset($_COOKIE['ti'])) {
      echo ('
              <div class="dropdown">
                <button class="dropbtn">EQUIPAMENTOS
                  <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content-equipamentos">
                  <a href="../equipamentos/cadastro.php">CADASTRO GPS</a>
                  <a href="../equipamentos/headsets.php">HEADSETS</a>
                </div>
              </div>'
      );
    }
    if (isset($_COOKIE['ti']) or isset($_COOKIE['gerente'])) {
      echo ('
            <div class="dropdown">
              <button class="dropbtn">DASHBOARDS
                <i class="fa fa-caret-down"></i>
              </button>
              <div class="dropdown-content-dashboards">
                <!--<a href="../dashboards/elev.php">ELEV</a>-->
                <a href="../dashboards/headsets.php">HEADSETS</a>
                <a href="../dashboards/chamados.php">CHAMADOS</a>
              </div>
            </div>'
      );
    }
    ?>
    <a class="logoff" href="../logoff.php">LOGOFF</a>
  </ul>
  <a class="nome" href="../home/usuario.php">
    <?php
    if (isset($_COOKIE['ti'])) {
      $cargo = "TI";
    } elseif (isset($_COOKIE['rh'])) {
      $cargo = "RH";
    } elseif (isset($_COOKIE['supervisor'])) {
      $cargo = "SUPERVISÃO";
    } elseif (isset($_COOKIE['coordenador'])) {
      $cargo = "COORDENAÇÃO";
    } elseif (isset($_COOKIE['gerente'])) {
      $cargo = "GERÊNCIA";
    }
    echo $cargo . ": " . $_COOKIE['nome']
      ?>
  </a>
</div>

<script>
  var menuIcon = document.querySelector('.menu-icon');
  var menu = document.querySelector('.menu');
  var nav = document.querySelector('.navbar');

  menuIcon.addEventListener('click', function () {
    if (menu.style.display === 'block') {
      menu.style.display = 'none';
    } else {
      menu.style.display = 'block';
    }
  });
</script>