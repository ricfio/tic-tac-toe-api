# Tic-Tac-Toe API

Tic-Tac-Toe API is a backend service that implements a REST API to Play the Tic-Tac-Toe game.

If you would like implement a client you should use these entrypoints:  

| Name       | Entrypoint (Method and URL)   | Description                   |
|------------|-------------------------------|-------------------------------|
| startGame  | POST  /games                  | Start a New Game              |
| resumeGame | GET   /games/{token}          | Resume a Pending Game         |
| makeMove   | PATCH /games/{token}/makeMove | Make a Player's Move          |
| autoMove   | PATCH /games/{token}/autoMove | Make an Automatic Random Move |

For full API specifications see:

- [Open API Documentation](public/doc/index.html) (from here you can also try the API entrypoints)
- [openapi.yaml](public/doc/openapi.yaml)

**DEPENDENCES:**

The main dependencies of this project are the followings:

| Name           | Description                   | Package                   |
|----------------|-------------------------------|---------------------------|
| Slim Framework | PHP micro-framework           | slim/slim                 |
| PHP CS Fixer   | PHP Coding Standards fixer    | friendsofphp/php-cs-fixer |
| Psalm          | PHP Static Analysis tool      | vimeo/psalm               |
| PHPUnit        | PHP Testing framework         | phpunit/phpunit           |

**NOTES:**

- At this time this project does not include any frontend clients.

## Getting started

Use the following commands to build and up the docker environment, and loggin in the app docker container:  

```bash
docker-compose build --no-cache
docker-compose up -d
docker-compose exec php bash
```

Now, from app docker container, install the dependencies:

```bash
composer install
```

Now, you can request on your browser the app homepage and other resources:

- [Homepage](http://localhost:8000/)
- [Open API Documentation](http://localhost:8000/doc/index.html)
- [API baseurl](http://localhost:8000/api/v1)

## Useful commands

### make

The project includes a Makefile to quickly execute some main tasks.  

`make`

```console
Usage:  make [TARGET]

Targets:
  install            Install packages (composer install)
  backup             Backup codebase (*_YYYYMMDD_HHMM.tar.gz)
  clear              Clear cache and temporary storage (./temp/*)
  check              Check codebase (php-cs-fixer, psalm, phparkitect)
  start              Start server
  stop               Stop server
  test               Test codebase (phpunit)
  update             Update packages (composer update)
```

### git-hook (pre-commit)

You can also use the bash script `./git-hook.sh` to manage the `.git/hooks/pre-commit`.

`./git-hook.sh`

```console
Usage:  git-hooks.sh [COMMAND]

Commands:
  install            Install .git/hooks/precommit
  uninstall          Uninstall .git/hooks/pre-commit
  run                Run ./scripts/git-hooks/pre-commit.sh
```

`./git-hooks.sh run`

```console
./scripts/git-hooks/pre-commit.sh

  Coding Standard
    ✔ PHP CS Fixer

  Static Analysis
    ✔ Psalm

  Test Execution
    ✔ PHPUnit
```

You can change `./scripts/git-hooks/pre-commit.sh` to customize it with your preferences.

`vi ./scripts/git-hooks/pre-commit.sh`

## Legend

In the following table you can found the meaning of the main used terms inside the codebase.

| Name  | Description                                                          |
|-------|----------------------------------------------------------------------|
| board | game board (size 3x3)                                                |
| cell  | cell position [0..8] on the game board                               |
| line  | sequence of three cells in row (horizontal, vertical, diagonal)      |
| mark  | mark in a cell [0 => ' ', 1 => 'X', 2 => 'O'] of the game board      |

## API usage (with examples of entrypoint calls by cURL commands)

While the `startGame` entrypoint returns as response a json with a token only inside:

```json
{
    "token": "909b5840fbf4dab033ed8e0f21d8682c"
}
```

all the other API entrypoint return as response a json with the full game state of the game identified by the token:

```json
{
  "token": "6a85276f62aeeaa5d117264dd9caf6e6", // token that identify the game (such as passed in the url)
  "board": [   // current marks in the cells of the game board, mapped as: 0 => NONE, 1 => 'X', 2 => 'Y'
    2,0,1,
    0,2,0,
    1,1,2
  ],
  "turn": 1,   // player that should make the next move, mapped as: 1 => PLAYER1, 2 => PLAYER2
  "winner": 2, // winner of the game, mapped as: -1 => NONE, 0 => DRAW, 1 => PLAYER1, 2 => PLAYER2
  "status": 1  // game status, mapped as: 0 => pending, 1 => completed
}
```

**NOTE**
The cells in the `board` array are sorted by index and must be displayed on the game board with an approach from TOP-LEFT to BOTTOM-RIGHT, as following rappresented:

```console
0 1 2
3 4 5
6 7 8
```

### Start a New Game (startGame)

You can start a new game calling the `startGame` entrypoint that returns a token identifying that specific game.

```bash
curl --request POST  --header 'Content-Type: application/json' --url http://localhost:8000/api/v1/games
```

```json
{
    "token": "909b5840fbf4dab033ed8e0f21d8682c"
}
```

The above token returned as response will be used after for all the next calls, so in the below example calls you can replace the token showed (`909b5840fbf4dab033ed8e0f21d8682c`) in the request url with the real token returned by this entrypoint `startGame`.

### Resume a Pending Game (resumeGame)

To resume a pending game (previously started) you can call the `resumeGame` entrypoint to retrive the game status.  
This entrypoint require you have to pass the token in the entrypoint url.

```bash
curl --request GET   --header 'Content-Type: application/json' --url http://localhost:8000/api/v1/games/909b5840fbf4dab033ed8e0f21d8682c
```  

### Make a Player's Move (makeMove)

To make a player's move you can call the `makeMove` entrypoint passing in the request body the json with the data of the game move to make.  
This entrypoint require you have to pass the token in the entrypoint url.  

Below an example of the input json:

```json
{
    "turn": 1, // The player that make the move, can assume the values 1 (player1) or 2 (player2).
    "cell": 4, // The cell position in the game board where the player would like add own mark ('X' or 'O').
}
```

```bash
curl --request PATCH --header 'Content-Type: application/json' --url http://localhost:8000/api/v1/games/909b5840fbf4dab033ed8e0f21d8682c/makeMove --data '{"turn": 1,"cell": 4}'  
```  

### Make an Automatic Random Move (autoMove)

To make an automatic random move you can call the `autoMove` entrypoint (no json data need in the request body).  
This entrypoint require you have to pass the token in the entrypoint url.  

```bash
curl --request PATCH --header 'Content-Type: application/json' --url http://localhost:8000/api/v1/games/909b5840fbf4dab033ed8e0f21d8682c/autoMove  
```  

## Appendix

### BACKLOG

- [ ] `PHPUnit/tests`: Improve the test suites and the current tests coverage (to analyze)
- [ ] `GitHub/CI`: Add .github/workflows/ci.yml for continuous integration
- [ ] `settings`: Create a setting file with the main app parameters ($storagePath in ContainerFactory.php, etc.)
- [ ] `API::startGame/game`: The json response could be the same of other methods (replacing current response)
- [ ] `GameRepositoryRedis`: Implement GameRepositoryRedis (implements GameRepositoryInterface)
- [ ] `HTTP/Code`: Improve the HTTP error codes mapping used in the response when an exception is thrown
- [ ] `openapi.yaml`: Improve the openapi.yaml with more detailed structures and examples
- [ ] `api-client-cli`: Implement a basic API Client CLI (Command Line Interface) to demo/test purpose
- [ ] `make/demo`: Implement target demo on Makefile to show a demo of the API
- [ ] `PHP81/Enum`: Use Enums (PHP 8.1) to replace class such as CellMark, GameWinner, GameStatus, and so on
- [ ] `GameEngine::scanLines(Board $board, int $cell)`: Scan only the lines that run through the last marked cell
- [ ] `GameEngine::isDraw(...)`: Detect a draw when there can be no winner
- [ ] `ValidableInterface`: Use @template annotations to replace ValidableIntInterface and ValidableStringInterface
