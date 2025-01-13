<?php

namespace App\Command;

use App\Client\EncarApiClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class ParseKoreanWebsiteCommand extends Command
{
    private const CARMAP = [
        'Hyundai' => '현대',
        'Genesis' => '제네시스',
        'Kia' => '기아',
        'Chevrolet (GM Daewoo)' => '쉐보레(GM대우)',
        'Renault Korea (Samsung)' => '르노코리아(삼성)',
        'KG Mobility (SsangYong)' => 'KG모빌리티(쌍용)',
        'Other Manufacturers' => '기타 제조사',
    ];
    private $client;

    public function __construct(EncarApiClient $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('parse:korean-website')
            ->addArgument('context', InputArgument::REQUIRED, 'The context for fetching cars')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'Run the command without processing data')
            ->setDescription('Parse Korean website data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $carManufacturers = array_flip(self::CARMAP);

        $context = $input->getArgument('context');
        $dryRun = $input->getOption('dry-run');

        $startTime = microtime(true);

        try {
            $data = $this->client->fetchCars($context, 8, 8);

            $table = new Table($output);
            $table->setHeaders(['Car ID', 'Model', 'Badge', 'Year', 'Price', 'Brand']);

            foreach ($data as $car) {
                $table->addRow([
                    $car['Id'],
                    $car['Model'],
                    $car['Badge'],
                    $car['Year'],
                    $car['Price'],
                    $carManufacturers[$car['Manufacturer']],
                ]);
            }

            $table->render();

            $endTime = microtime(true);
            $executionTime = $endTime - $startTime;

            $output->writeln(sprintf(
                '<comment>Total elements: %d</comment>',
                count($data)
            ));
            $output->writeln(sprintf(
                '<comment>Execution time: %.4f seconds</comment>',
                $executionTime
            ));

        } catch (\Exception $e) {
            $output->writeln('<error>Error occurred: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}