async function buscar_dados(){

    console.log('iniciando')
    // realizar a requisição
    const dados = await fetch('http://172.10.20.60/archerx/public/busca_dash_heads.php');
    // ler os dados
    const resposta = await dados.json();

    console.log(resposta)

    return resposta

}

function carregar_pagina(){
    buscar_dados()

    .then((resposta) => {

        $.ajax({
            url: "headsets.php",
            type: "POST",
            data: {valor: resposta},
            success: function(resposta) {
                console.log(resposta);
            }
        });
        
    })
    .catch((error) => {
        console.error(error);
    });
}