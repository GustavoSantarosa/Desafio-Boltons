

<h3 align="center">Desafio Boltons</h3>


## 🧐 Sobre <a name = "about"></a>

Desafio de boltons realizado utilizando api da arquivei.

## 🏁 Para iniciar o projeto

Clone o projeto
```
gh repo clone GustavoSantarosa/Desafio-Boltons
```

Neste exemplo, o .env foi retirado do git ignore, então não se preocupe.
em seguida, baixe as dependencias utilizadas no projeto
```
composer install
```

Com o docker start os containers
```
docker-compose up -d
```

acesse o sh do container da api novamente
```
docker exec -it desafio-api sh
```
e rode as migrations do Laravel
```
cd app
php artisan migrate
```
e por ultimo precisamos levantar o worker que vai processar a fila no laravel
```
php artisan queue:work
```
Em seguidas temos alguns comandos uteis para facilitar a vida do dev
- para printar o env do container
```
docker exec -it desafio-api printenv
```
-  Para deletar todos os containers
```
docker rm -f $(docker ps -a -q)
```
- para restartar a fila
```
php artisan queue:restart
```

Acesse o seu localhost e pronto, o projeto ja é para estar funcionando.

## 🔧 rodando os testes <a name = "tests"></a>
Para testar a aplicação utilizando tdd voce pode utilizar o comando do unit, nele foram criado alguns testes padroes, e bem simples apenas para demonstrar no desafio.

acesse o sh do container da api
```
docker exec -it desafio-api sh
```
em seguida utilize o comando do phpunit
```
app/vendor/bin/phpunit
```

Testes presentes:

- checa se o retorno da api é codigo 200.
- checa se a estrutura do json da api esta de acordo com o esperado.
- checa se o conteudo do retorno do endpoint esta de acordo com o esperado.
- pega o valor da nota fiscal retornado na api, e subtrai por 1348.00 e checa se o resultado da 0

## 🎈 Utilizando
Exemplo de cabeçalho para efetuar as requisições:
```
curl -X GET \
  localhost/api/v1/nf \
  -H 'Content-Type: application/json' \
  -H 'x-api-id: 1234' \
  -H 'x-api-key: 5678'
```

No desafio, foram criados 2 endpoints, o primeiro é um endpoint que busca por todas os xmls na arquivei e armazena em seu banco de dados.
```
localhost/api/v1/nf
```
- ele faz a coleta de 50 em 50 xmls da arquivei e joga em uma fila dentro do banco de dados até que todos estejam na fila.
- em seguida um worker processa a fila utilizando um job, que checa se a nota ja existe no banco de dados, caso não, ele inseri.
```
localhost/api/v1/nf/chave/{chave}
```
- neste endpoint, ele vai checar se a chave ja não existe no banco de dados do projeto, caso não exista ele consulta na api da arquivei

- em seguida ele armazena no banco do projeto para consultas futuras, e devolve as informações requisitadas no desafio para o usuario.

```
localhost/api/v1/nf/chave/{chave}?nocache=1
```
- foi criado o parametro nocache para quando o usuario queira forçar a busca da nota na api da arquivei

```
localhost/api/v1/nf/chave/50171130290824000104550010000224381005443300?nocache=1
```

## ⛏️ Utilizado

- [mysql](https://www.mysql.com/) - banco de dados
- [php](https://www.php.net/) - linguagem
- [laravel](https://laravel.com/) - framework

## ✍️ Autor

- [@Luis Gustavo Santarosa Pinto](https://github.com/GustavoSantarosa) - Idea & Initial work

