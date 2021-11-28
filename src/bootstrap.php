<?php

declare(strict_types=1);

use App\ContainerFactory;
use App\Infrastructure\Port\Controller\GameController;
use Slim\Factory\AppFactory;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Slim\Routing\RouteCollectorProxy;

require __DIR__.'/../vendor/autoload.php';

// Instantiate Container
$container = ContainerFactory::create();

// Instantiate App
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);
//$app->addErrorMiddleware(false, true, true);

// Add routes

/**
 * @psalm-suppress UnusedClosureParam
 */
$app->get('/', function (Request $request, Response $response) {
    $html = <<<'HTML'
<html>
    <head>
        <title>Tic-Tac-Toe</title>
    </head>
    <body>
        <h1>Tic-Tac-Toe</h1>
        <ul>
            <li><h2><a href="/doc/index.html"><h3>OpenAPI Documentation</h3></a></h2></li>
        </ul>
    </body>
</html>
HTML;
    $response->getBody()->write($html);

    return $response;
});

$app->group('/api/v1', function (RouteCollectorProxy $api): void {
    $api->post('/games', [GameController::class, 'startGame']);
    $api->get('/games/{token}', [GameController::class, 'resumeGame']);
    $api->patch('/games/{token}/makeMove', [GameController::class, 'makeMove']);
    $api->patch('/games/{token}/autoMove', [GameController::class, 'autoMove']);
});

$app->run();
