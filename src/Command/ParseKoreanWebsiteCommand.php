<?php

declare(strict_types=1);

namespace App\Command;

use App\Services\KoreanParseHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class ParseKoreanWebsiteCommand extends Command
{
    public function __construct(private readonly KoreanParseHandler $handler)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('parse:korean-website')
            ->addArgument('brand', InputArgument::REQUIRED, 'The car brand')
            ->addArgument('car', InputArgument::OPTIONAL, 'The car model')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'Run the command without processing data')
            ->setDescription('Parse Korean website data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dryRun = $input->getOption('dry-run');

        //TODO rename
        $context = [
            'brand' => $input->getArgument('brand'),
            'car' => $input->getArgument('car'),
        ];

        $startTime = microtime(true);

        try {
            $data = $this->handler->handle($context, $dryRun);

            $table = new Table($output);
            $table->setHeaders(['Car ID', 'Model', 'Badge', 'Year', 'Price', 'Brand']);

            foreach ($data as $carData) {
                $table->addRow([
                    $carData['Id'],
                    $carData['Model'],
                    $carData['Badge'],
                    $carData['Year'],
                    $carData['Price'],
                    $carData['Brand'],
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