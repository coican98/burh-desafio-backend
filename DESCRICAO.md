# Desafio Backend BURH 
 
## Introdução 
Nesse desafio serão analisadas suas competências no desenvolvimento de uma API de vagas de emprego. O desafio é referente à vaga Desenvolvedor(a) Backend PHP, publicada no [Burh](https://burh.com.br/vagas/3270129695). Abaixo você encontrará todas as informações necessárias para criar e submeter seu desafio.  

Boa sorte! 🙂 
 
## Instruções 
Para realizar o desafio é importante que você cumpra os itens abaixo: 
* Possuir um Github; 
* Realizar o [Fork](https://docs.github.com/pt/get-started/quickstart/fork-a-repo) deste projeto e subir os commits em seu Github; 
* Estar inscrito na [vaga](https://burh.com.br/vagas/3270129695). 
 
## Instruções 
Para começar a desenvolver, o primeiro passo é criar um fork deste projeto, logo após, recomendamos que você de uma boa olhada nas principais funções e requisitos do desafio antes de começar a programar. Ao subir os commits do seu projeto busque ser o mais descritivo possível, sem subir muitas funcionalidades de uma vez. O desafio busca analisar suas competências em desenvolvimento de APIs, portanto não é necessário e nem será analisado nenhuma tela. Busque terminar primeiro os itens essenciais do desafio e só então, caso queira, você pode implementar funcionalidades adicionais ao seu projeto. 
 
A API deve ser criada utilizando PHP com o Framework Laravel. O banco ficará a sua escolha, portanto que seja um banco SQL (MySql, MariaDB, PostgreSQL, Sqlite, etc). 
 
## O Projeto 
Você será responsável pela criação de uma API Restful de cadastro de vagas e candidatura de usuários, em que uma empresa pode criar uma vaga e um usuário pode se candidatar nas vagas criadas.  
A API deve ser o mais simples possível, contendo somente as funcionalidades que você considere essenciais para a integração completa do seu projeto e atenda aos nossos requisitos.  
Rotas, estrutura do banco e estrutura do código também estarão ao seu critério, portanto que supram os requisitos. 
  
A API deverá conter as seguintes entidades: 

 
* Empresa; 
* Usuário; 
* Vaga. 
 
A entidade empresa deverá conter os campos nome, descrição, CNPJ e plano. 
A entidade vaga deverá conter os campos título, descrição, tipo de vaga, salário e horário. 
A entidade usuário deverá conter os campos nome, e-mail, CPF e idade. 
 
Requisitos: 

 
* Empresas podem abrir vagas. 
* Usuários podem se candidatar a vagas. 
* Não pode haver mais de um usuário com o mesmo e-mail ou CPF cadastrado. 
* Não pode haver mais de uma empresa com o mesmo CNPJ cadastrado. 
* As empresas poderão ter 2 tipos de plano: "Free" ou "Premium". Empresas com o plano Free poderão abrir até 5 vagas, enquanto empresas com o plano Premium podem abrir até 10 vagas. 
* Poderão existir vagas do tipo PJ, CLT e estágio.  
* Vagas do tipo CLT e estágio tem o cadastro do salário e horário obrigatórios.  
* Vagas do tipo CLT devem possuir o salário mínimo de R$1212,00 enquanto vagas de estágio e PJ não possuem um valor mínimo.  
* Vagas do tipo estágio devem ter o horário máximo de 6 horas. 
* Deverá haver uma rota de busca de usuários, podendo filtrar por nome, E-mail e CPF. Além disso a rota deverá retornar todas as vagas em que aqueles usuários estão inscritos, trazendo todos os dados dessas vagas. 
 
Você é livre para nomear os campos da forma que preferir e adicionar quaisquer campos extras ou tabelas para criar relações entre as entidades. não é necessário qualquer sistema de autenticação. 
 
## O que avaliaremos em seu projeto 
* Cumprimento dos requisitos do desafio. 
* Estrutura e coerência do código. 
* Arquitetura do banco. 
* Código limpo e organizado. 
* Padrões de código (PSRs, Design patterns, SOLID). 
* Tratamento de erros. 
 
## O que será um diferencial para seu projeto. 
* Uso de docker. 
* Testes de integração. 
* Design Patterns. 
* Documentação (ReadME). 

* Uso de cache 
 
## O que NÃO é essencial em seu projeto 
* Frontend. 
* Autenticação. 
