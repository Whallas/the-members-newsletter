# Newsletter - The Members

Projeto de API de Newsletter como teste para vaga de desenvolvedor no time The Members. Inspirado em sua [documentação](https://github.com/The-members/teste-backend).

## Pré-requisitos

- PHP >= 8.2
- Composer
- Docker

## Instalação

1. Clone o repositório.
2. Execute o comando `composer install` para instalar as dependências do Laravel.
3. Execute o comando `cp .env.example .env` para criar o arquivo de configuração do ambiente.
4. Execute o comando `php artisan key:generate`.
Configure as variáveis de ambiente no arquivo `.env` de acordo com o seu ambiente.
5. Execute o comando `./vendor/bin/sail up -d` para iniciar os containers do Docker.
6. Depois de iniciar os containers, execute o comando `./vendor/bin/sail artisan migrate --seed` para criar as tabelas no banco de dados.
7. Gere os arquivos de documentação da API usando o comando `./vendor/bin/sail artisan scribe:generate`.

## Uso
Case deseje utilizar uma fila, execute o comando `./vendor/bin/sail artisan queue:work` para processar os e-mails em segundo plano.

Acesse a documentação da API em `http://localhost:8080/docs`. Ou troque a porta se necessário através da variável `APP_PORT`.
