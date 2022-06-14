<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

// docker exec -it hash_app php bin/console generate:hash {string} --requests={number}
class GenerateHashCommand extends Command
{
    const BASE_URL = 'http://localhost:8000/api/hashes/generate/';

    protected static $defaultName = 'generate:hash';
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('string', InputArgument::REQUIRED, 'The string entry of a base hash.');
        $this->addOption('requests', null, InputOption::VALUE_OPTIONAL, 'How many requests?', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $string = $input->getArgument('string');
        $requests = $input->getOption('requests');
        $data = $this->getHashes($string, $requests);

        $output->writeln([
            '',
            '               HASH              |     KEY     |     ATTEMPTS',
            '====================================================================',
        ]);

        foreach ($data as $hash) {
            $output->writeln([
                $hash['hash'] . ' |   ' . $hash['key'] . '  |       ' . $hash['attempts'],
                '===================================================================='
            ]);
        }

        $output->writeln([
            ''
        ]);

        return Command::SUCCESS;
    }

    private function getHashes(string $string, int $requests): array
    {
        $url = self::BASE_URL . "{$string}/";

        if ($requests === 1) {
            $response = $this->client->request('GET', $url);
            return json_decode($response->getContent(), true);
        }

        $data = [];
        $batch = new \DateTime('now',  new \DateTimeZone('America/Sao_Paulo'));
        $response = $this->client->request('GET', $url, [
            'query' => [
                'batch' => $batch->format('Y-m-d H:i:s'),
            ],
        ]);

        $data[] = json_decode($response->getContent(), true);

        for ($i = 1; $i < $requests; $i++) {
            $string = $data[$i - 1]['hash'];
            $url = self::BASE_URL . "{$string}/";

            $response = $this->client->request('GET', $url, [
                'query' => [
                    'batch' => $batch->format('Y-m-d H:i:s'),
                    'block' => $i,
                ]
            ]);

            $data[] = json_decode($response->getContent(), true);
        }

        return $data;
    }
}