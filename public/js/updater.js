function apertado(id){

    console.log(id)

    atualizar_tabela_rede(
        document.getElementById('baia'+id).value,
        document.getElementById('pach_panel'+id).value,
        document.getElementById('switch_host'+id).value,
        document.getElementById('switch_ip'+id).value,
        document.getElementById('switch_porta'+id).value
    ) 
}

function apertado_gps(id){

    console.log(id)

    atualizar_tabela_gps(
        document.getElementById('baia'+id).value,
        document.getElementById('setor'+id).value,
        document.getElementById('ramal'+id).value,
        document.getElementById('hostname'+id).value,
        document.getElementById('serial'+id).value,
        document.getElementById('serie'+id).value,
        document.getElementById('qdu_serial'+id).value
    ) 
}

async function atualizar_tabela_rede(baia,pach,host,ip,porta){
    console.log(
        'baia =',baia,
        'pach =',pach,
        'host =',host,
        'ip =',ip,
        'porta =',porta,
    )

    // criar tabela
    var dados_rede = [baia,pach,host,ip,porta];
    console.log(dados_rede);

    // realizar a requisição
    const dados = await fetch('atualiza_js.php?dados_rede='+dados_rede);
    // ler os dados
    const resposta = await dados.json();
    console.log(resposta);

    if(resposta['erro'] == false){
        await swal({
            title: "Erro!",
            text: resposta['msg'],
            icon: "error",
            button: false,
            timer: 1500
          });
    }else{
        await swal({
            title: "Sucesso!",
            text: "Erro ao atualizar baia!",
            icon: "success",
            button: false,
            timer: 1500
          });
    }

    window.location.href = "/documents/scripts/archerx/public/atualizar/atualizar_rede.php";

}

async function atualizar_tabela_gps(baia,setor,ramal,hostname,serial,serie,qdu_serial){
    console.log(
        'baia =',baia,
        'setor =',setor,
        'ramal =',ramal,
        'hostname =',hostname,
        'serial =',serial,
        'serie =',serie,
        'qdu_serial =',qdu_serial,
    )

    // criar tabela
    var dados_gps = [baia,setor,ramal,hostname,serial,serie,qdu_serial];
    console.log(dados_gps);

    // realizar a requisição
    const dados = await fetch('atualiza_js.php?dados_gps='+dados_gps);
    // ler os dados
    const resposta = await dados.json();
    console.log(resposta);

    if (resposta['erro'] == true){
        await swal({
            title: "Erro!",
            text: resposta['msg'],
            icon: "error",
            button: false,
            timer: 1500
          });
    }else{
        await swal({
            title: "Sucesso!",
            text: "Baia atualizada com sucesso.",
            icon: "success",
            button: false,
            timer: 1500
          });
    }

    window.location.href = "/documents/scripts/archerx/public/atualizar/atualizar_gps.php";
}