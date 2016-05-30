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
    $connection = new TwitterOAuth(
        getenv('TWITTER_KEY'),
        getenv('TWITTER_SECRET'),
        getenv('TWITTER_ACCESS_TOKEN'),
        getenv('TWITTER_ACCESS_TOKEN_SECRET')
    );

    $tweet = $connection->post('statuses/update', [
        //    'status' => 'I can has 1GB of data @thetunnelbear pls?'
        'status' => 'Testing...'
    ]);

    $log->info('Tweet published: ', (array) $tweet);
}

exit;
