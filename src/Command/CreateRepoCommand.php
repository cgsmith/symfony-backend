<?php

namespace App\Command;

use App\Clients\GithubClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-repo',
    description: 'Creates a new repository',
)]
class CreateRepoCommand extends Command
{
    public function __construct(
        private GithubClient $client
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('repo', InputArgument::REQUIRED, 'Name of the repository to create')
            ->setHelp('Specify the repository name and it will be created under your access token');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $repo = $input->getArgument('repo');

        if ($repo) {
            $io->note(sprintf('Trying to create repo: %s', $repo));
        }

        $response = $this->client->createRepo($repo);

        if (201 == $response->getStatusCode()) {
            $io->success('You have a new repo! Go create something amazing!');
            $io->success($response->getContent());

            return Command::SUCCESS;
        } elseif (401 == $response->getStatusCode()) {
            $io->error('GitHub returned a 401. Is your personal access token set in the .env file?');

            return Command::FAILURE;
        } else {
            $io->error('Something went wrong. Hope below helps.');
            $io->error($response->getContent());

            return Command::FAILURE;
        }
    }
}
