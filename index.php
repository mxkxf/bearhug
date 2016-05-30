<?php

require_once('vendor/autoload.php');

use Dotenv\Dotenv;
use Abraham\TwitterOAuth\TwitterOAuth;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));

try {
    $dotenv = new Dotenv(__DIR__);
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    // Do nothing
}

if ($_GET['token'] === getenv('AUTH_TOKEN')) {
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

    $log->info('Tweet published: ', (array) $tweet);
}

header('HTTP/1.1 200 OK');
exit;
