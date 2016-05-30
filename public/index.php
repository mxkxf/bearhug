<?php

use MikeFrancis\BearHug\Exception as BearHugException;
use Dotenv\Dotenv;
use Slim\App as Application;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Abraham\TwitterOAuth\TwitterOAuth;
use Slim\Container;

require_once('../vendor/autoload.php');

try {
    $dotenv = new Dotenv(__DIR__ . '/../');
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    // Do nothing
}

$container = new Container([
    'settings' => [
        'displayErrorDetails' => getenv('APP_DEBUG') === 'true' ? true : false
    ]
]);

$container['errorHandler'] = function (Container $container) {
    return function (Request $request, Response $response, Exception $exception) use ($container) {
        if ($exception instanceof BearHugException) {
            return $container['response']->withStatus(400)
                ->withHeader('Content-Type', 'application/json')
                ->withJson(['message' => $exception->getMessage()]);
        }
    };
};

$app = new Application($container);

$app->add(function (Request $request, Response $response, $next) {
    throw new SomethingException();
    $queryParams = $request->getQueryParams();

    if ($queryParams['token'] !== getenv('AUTH_TOKEN')) {
        throw new BearHugException('Unauthorised request');
    }

    return $next($request, $response);
});


$app->get('/', function (Request $request, Response $response) {
    $statuses = [
        "I can has 1GB of data @thetunnelbear pls?",
        "Shameless @thetunnelbear tweet for more bandwidth. Use it - it's great!",
        "Using @thetunnelbear as my VPN, you should check it out too.",
        "My favourite bear, @thetunnelbear!"
    ];

    $connection = new TwitterOAuth(
        getenv('TWITTER_KEY'),
        getenv('TWITTER_SECRET'),
        getenv('TWITTER_ACCESS_TOKEN'),
        getenv('TWITTER_ACCESS_TOKEN_SECRET')
    );

    $tweet = $connection->post('statuses/update', [
        'status' => $statuses[array_rand($statuses)]
    ]);

    if (isset($tweet->errors)) {
        throw new BearHugException('Twitter OAuth Error');
    }

    return $response->withJson(['message' => 'Tweet published']);
});

$app->run();
