function apertado_admin_atualizar(id){

    console.log(id)

    atualizar_usuario(
        id,
        document.getElementById('nome'+id).value,
        document.getElementById('login'+id).value,
        document.getElementById('senha'+id).value,
        document.getElementById('nivel'+id).value,
    ) 
}

async function admin_excluir(id){
    console.log(
        'id =',id,
    )

    // criar tabela
    var dados_exclusao = [id];
    console.log(dados_exclusao);

    // realizar a requisição
    const dados = await fetch('../funcoes/gerencia/atualiza_admin.php?dados_exclusao='+dados_exclusao);
    // ler os dados
    const resposta = await dados.json();
    console.log(resposta);

    if(resposta['erro'] == false){
        alert("Excluido com sucesso!");
    }else{
        alert("Erro ao excluir!");
    }

    window.location.href = "../gerencia/admin.php";
    
}

async function atualizar_usuario(id,nome,login,senha,nivel){
    console.log(
        'id =',id,
        'nome =',nome,
        'login =',login,
        'senha =',senha,
        'nivel =',nivel,
    )

    // criar tabela
    var dados_atualizar = [id,nome,login,senha,nivel];
    console.log(dados_atualizar);

    // realizar a requisição
    const dados = await fetch('../funcoes/gerencia/atualiza_admin.php?dados_atualizar='+dados_atualizar);
    // ler os dados
    const resposta = await dados.json();
    console.log(resposta);

    if(resposta['erro'] == false){
        alert("Atualizado com sucesso!");
    }else{
        alert("Erro ao atualizar!");
    }

    window.location.href = "../gerencia/admin.php";

}

async function admin_cadastrar(){

    nome = document.getElementById('nome').value;
    login = document.getElementById('login').value;
    senha = document.getElementById('senha').value;
    nivel = document.getElementById('nivel').value;

    var dados_cadastro = [nome,login,senha,nivel];
    console.log(dados_cadastro);

    // realizar a requisição
    const dados = await fetch('../funcoes/gerencia/atualiza_admin.php?dados_cadastro='+dados_cadastro);
    // ler os dados
    const resposta = await dados.json();
    console.log(resposta);

    if(resposta['erro'] == false){
        alert("Cadastrado com sucesso!");
    }else{
        alert("Erro ao cadastrar!");
    }

    window.location.href = "../gerencia/admin.php";
}