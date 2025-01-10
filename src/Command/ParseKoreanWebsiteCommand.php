<?php

namespace App\Command;

use App\Client\EncarApiClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseKoreanWebsiteCommand extends Command
{
    private const CARMAP = [
        'Hyundai' => '현대',
        'Genesis' => '제네시스',
        'Kia' => '기아',
        'Chevrolet (GM Daewoo)' => '쉐보레(GM대우)',
        'Renault Korea (Samsung)' => '르노코리아(삼성)',
        'KG Mobility (SsangYong)' => 'KG모빌리티(쌍용)',
        'Other Manufacturers' => '기타 제조사', ];
    private $client;

    public function __construct(EncarApiClient $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('parse:korean-website')
            ->addArgument('context', InputArgument::REQUIRED)
            ->setDescription('Parse Korean website data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $carManufacturers = array_flip(self::CARMAP);

        $context = $input->getArgument('context');

        try {

            $data = $this->client->fetchCars($context);

            if (isset($data['SearchResults'])) {
                foreach ($data['SearchResults'] as $car) {
                    $output->writeln(sprintf(
                        'Car ID: %s, Model: %s, Badge: %s, Year: %s, Price: %s, Brand: %s',
                        $car['Id'],
                        $car['Model'],
                        $car['Badge'],
                        $car['Year'],
                        $car['Price'],
                        $carManufacturers[$car['Manufacturer']],
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