GIPX - Gerênciador Interno Plansul Xaxim

Versão - 3.8

Data: 19/02/2022 à 01/06/2023

Desenvolvedores:
    - Backend: Mauricio Ferrari
    - Frontend: Cristian Lopes
	- Backend e Frontend: Daniel Lopes Manfrini 

Descrição: interface de gerencia da API Archer de autoprovisionamento de ramais 3CX;

Changelog

V.1.0 = por: Mauricio Ferrari, Cristian Lopes.
	
	Inicial.

V.1.1 = Por: Daniel Lopes Manfrini.

	Adição das colunas de setor e monitor.
	Alteração do arquivo de atualização "Updater.php":
		Restruturação completa do arquivo, com avisos de dados duplicados e dados inexistentes,
		o arquivo é executado inteiro, diferente de antes, que parava no primeiro erro,
		agora passa por todas as condições antes de finalizar e relata apenas o dado que está incorreto ao operador,
		evitando a reinserção de um dado que não foi processado. 
	População das tabelas com os dados do GPS.

V.1.2 = Por: Daniel Lopes Manfrini.

	Criado a página de cadastro,
	Criado o arquivo "inserter.php":
		Junto com a página de cadastro realiza no banco de dados o INSERT de novas informações,
		tais como máquinas e equipamentos.

V.1.3 = Por: Daniel Lopes Manfrini.

	Restruturação da página Dashboard elev.
	Inicio da migração da página de gerencia de funcionarios:
		atualmente é um programa em phyton, a migração deve se a facilidade de acesso.

V.1.4 = Por: Daniel Lopes Manfrini.

	Alteração completa do HEADER e adição de um FOOTER.
	Alteração completa do login.

V.1.5 = Por: Daniel Lopes Manfrini.

	Finalizado página de funcionarios.
		A página foi criada usando funções em ".js" para a busca das informações.
	Ficaram duas pendências:
		1ª: criar asa funções de UPDATE e INSERT.
		2º: conseguir estabelecer conexão com o SQLserver no jorginho.
			Os bancos das catracas são em SQl server.

V.2.0 = Por: Daniel Lopes Manfrini.

	Alteração completa dos arquivos de CSS.
	Alterado cores base.
	Alterado tela de fundo.
	Alterado cores de margens e fontes.

V.2.1 = Por: Daniel Lopes Manfrini.

	Criado novo menu dropdown GERÊNCIA para o HEADER. 

V.2.2 = Por: Daniel Lopes Manfrini.

	Criado a página de RELATÓRIO DE REDE dentro do menu dropdown da GERÊNCIA.
	Criado novo menu dropdown dentro de CADASTRO e alterado a página cadastro para a opção GPS.
	Criado nova página GPS e adicionado dentro do menu dropdown CADASTRO.
	Utilizado o arquivo "Updater.php" para realizar o UPDATE das informações.

V.2.3 = Por: Daniel Lopes Manfrini.

	Criado um link para o site WYNTECH dentro do menu dropdown GERÊNCIA.

V.2.4 = Por: Daniel Lopes Manfrini.

	Reformulado HEADER.
	Adição de sidebars em páginas de relatório.
	criação da página HOME.
	Alterado o modo de pesquisa nas páginas de cadastro.
		agora a busca pode ser feita sem a nescessidade de indicar um tipo.
	Reformulado o CSS de algumas páginas.

V.2.5 = Por: Daniel Lopes Manfrini.

	Criado página FOLHA PONTO
		a página é hospedada no 172.10.20.60 por conta do driver.

V.3.0 = Por: Daniel Lopes Manfrini Data: 19/05/2023.

	Sucesso na comunicação com MSSQL
		Esta ação abriu novas oportunidades de integração,
		eliminando totalment o uso do servidor windows 172.10.20.60

V.3.1 = Por: Daniel Lopes Manfrini.

	Reestruturação completa do sitema utilizando DATATABLES e SWEET ALERTS.

		O uso destas duas bibliotecas facilitaram em muito a tratativa de dados e retorno ao usuário.

V.3.2 = Por: Daniel Lopes Manfrini.

	Mudança completa do uso de .js para AJAX nas funções asyncronas.

V.3.3 = Por: Daniel Lopes Manfrini.

	Alteração das cores padrões do sistema para as cores da empresa.

V.3.4 = Por: Daniel Lopes Manfrini.

	Criação das novas páginas utilizando MSSQL

		Pagina de relatório de catracas.
			Tem como objetivo retornar todas as vezes que o funicionario em questão utilizou as catracas no dia.
			
			Falta: Adicionar função de buscar por horário.
		
		Página de gerência de ponto.
			Assim como a página de catracas tem como objetivo retornar os pontos do funcionario no período selecionado.
				Para a criação desta página foi utilizado a criação de uma tabela,
				replicando os dados providenciados em uma view criado por Felipe Zappeli (Plansul planejamento e consultoria).

V.3.4.1 = Por: Daniel Lopes Manfrini.

	Inicio da criação da página de headsets.

		Iterrompido, sem prévia de finalizar.

V.3.5 = Por: Daniel Lopes Manfrini Data: 26/05/2023.

	Criação da página de usuário e reformulação do header.

		Como o sistema vai ser aberto para os STAFS e foram criados vários acessos,
		Realizei a  criação de novos níveis de acesso.

		Para isso tive de criar uma nova tabela no banco de dados do usuario, 
		aproveitando também para alterar toda a estrutura da página de login e do timeout das páginas.

		O header foi reformulado para que apareça o nome e o nível de acesso do usuário:  NÍVEL:USUÁRIO.

		Criei a página para que o usuário possa realizar a troca de senha, 
		é acessada ao clicar sobre a região do header onde contem o nome do usuário.

V.3.6 = Por: Daniel Lopes Manfrini.

	Mudança do header, onde a página de pontos passa do dropdown gerência para relatório.

V.3.7 = Por: Daniel Lopes Manfrini Data: 27/05/2023.

	Criação da página Tabela IP PLANSUL.

		Tem como objetivo informar ao técnico todos os ips fixos utilizados e livres.

V.3.8 = Por: Daniel Lopes Manfrini Data: 31/05/2023.

	Criação da API entre o GIPX e o  GLPI.

		A api tem como objetivo realizar a abertura de chamados diretamente da tabela de pontos,
		sem que o supervisor precise abrir o glpi assim tendo que usar dois sistemas.

			Os testes foram um sucesso.

				Aguardando aprovação de uso!
