<?php

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
            ->addArgument('context', InputArgument::REQUIRED, 'The context for fetching cars')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'Run the command without processing data')
            ->setDescription('Parse Korean website data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $context = $input->getArgument('context');
        $dryRun = $input->getOption('dry-run');

        $startTime = microtime(true);

        try {
            $data = $this->handler->handle($context, $dryRun);

            $table = new Table($output);
            $table->setHeaders(['Car ID', 'Model', 'Badge', 'Year', 'Price', 'Brand']);

            foreach ($data as $car) {
                $table->addRow([
                    $car['Id'],
                    $car['Model'],
                    $car['Badge'],
                    $car['Year'],
                    $car['Price'],
                    $car['Brand'],
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