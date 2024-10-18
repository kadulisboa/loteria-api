# Loteria PHP Project

Este é um projeto PHP que utiliza **Docker**, **PostgreSQL**, **SQLite**, e **PHPUnit**. O projeto gerencia sorteios e bilhetes de uma loteria com diversas rotas de API para manipulação desses dados.

## Requisitos

Certifique-se de ter os seguintes softwares instalados:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Composer](https://getcomposer.org/)

## Instalação

### Passo 1: Clonar o repositório

Clone o repositório para sua máquina local:

```bash
git clone https://github.com/kadulisboa/loteria-test.git
cd loteria-test
```
### Passo 2: Subir os serviços com Docker Compose
Construa e inicie os containers usando o Docker Compose:

```bash
docker-compose up --build
```
Isso iniciará o servidor PHP na porta 8000 e o PostgreSQL na porta 5433.

### Passo 3: Acessar a aplicação
A aplicação estará disponível em:

```bash
http://localhost:8000
```

## Serviços Docker
 - app: Container PHP rodando a aplicação.
 - db: Container PostgreSQL para armazenamento dos dados da loteria.

## Rotas da API
Aqui estão as principais rotas disponíveis na aplicação:

1. Criar Sorteio
   - Endpoint: /sorteio/criar
   - Método: POST
   - Descrição: Cria um novo sorteio.
####
2. Gerar Resultado de Sorteio
   - Endpoint: /sorteio/{id}/resultado
   - Método: POST
   - Parâmetros: {id} - ID do sorteio.
   - Descrição: Gera o resultado de um sorteio específico.
####
3. Obter Resultado de Sorteio
   - Endpoint: /sorteio/{id}/resultado
   - Método: GET
   - Parâmetros: {id} - ID do sorteio.
   - Descrição: Retorna o resultado de um sorteio específico.
####
4. Criar Bilhetes
   - Endpoint: /bilhete/criar
   - Método: POST
   - Corpo (JSON):
      ```json
      {
        "drawId": 1,
        "tripulante_id": 15,
        "quantity": 50,
        "tens": 10
      }
     ```
   - Descrição: Cria bilhetes para um sorteio.
####

5. Executar Migrations
   - Endpoint: /migrate
   - Método: GET
   - Descrição: Executa as migrations para configurar o banco de dados.
####

6. Página Inicial
   - Endpoint: /
   - Método: GET
   - Descrição: Renderiza a página inicial.
####
7. Status do Servidor
   - Endpoint: /server
   - Método: GET
   - Descrição: Retorna um JSON com o status do servidor.

## Testes Unitários
   O projeto utiliza PHPUnit para testes unitários. Para rodar os testes, execute:

```bash
composer test
```

## Postman Json
  O arquivo chamado Requests-Loteria.json é um arquivo para ser importando no Postman.