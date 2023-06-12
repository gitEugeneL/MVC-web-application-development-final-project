# Healthcare-CRM-final-project
**4th semester.**

Final project on the subject of **MVC-web-application-development**.

<i>Docker, PostgreSQL, Php-8, Symfony-6, RestAPI, JWT-authentication, Vue.js client</i>


## How to run this server

1.Start containers and run composition for all services defined in the docker-compose.yml

    docker-compose up -d

2.Connect to the docker server.

    docker-compose exec app bash

3.Start the server

    symfony server:start


## How to run vue web client (folder client)

1.Project Setup

    npm install

2.Compile and Hot-Reload for Development

    npm run dev


## Database design
![Database design](https://raw.githubusercontent.com/gitEugeneL/Healthcare-CRM-final-project/main/presentation/dbDesign.png?token=GHSAT0AAAAAACAH5ZKHTCIJ7FWNDDFQQ4QWZEHKMIQ)


## Links

* [Docker](https://developer.fedoraproject.org/tools/docker/about.html) how to install Docker
* [Docker Compose](https://developer.fedoraproject.org/tools/docker/compose.html) how to install docker-compose
* [Symfony](https://symfony.com/) symfony framework
