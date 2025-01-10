<?php

namespace App\Command;

use App\Client\EncarApiClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseKoreanWebsiteCommand extends Command
{
    private $client;

    public function __construct(EncarApiClient $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('parse:korean-website')
            ->setDescription('Parse Korean website data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $data = $this->client->fetchCars();

            if (isset($data['SearchResults'])) {
                foreach ($data['SearchResults'] as $car) {
                    $output->writeln(sprintf(
                        'Car ID: %s, Model: %s, Badge: %s, Year: %s, Price: %s',
                        $car['Id'],
                        $car['Model'],
                        $car['Badge'],
                        $car['Year'],
                        $car['Price'],
                    ));
                }
            } else {
                $output->writeln('No car data found.');
            }
        } catch (\Exception $e) {
            $output->writeln('Error occurred: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}