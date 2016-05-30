<?php
namespace MikeFrancis\BearHug;

use Abraham\TwitterOAuth\TwitterOAuth;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('tweet')
             ->setDescription('Fire off a tweet');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (Carbon::now()->day === 1) {
            return 0;
        }

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
            $output->writeln('<error>' . $tweet->errors[0]->message . '</error>');

            return 1;
        }

        return 0;
    }
}
