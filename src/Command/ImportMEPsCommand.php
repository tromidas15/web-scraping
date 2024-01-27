<?php
declare(strict_types=1);

namespace App\Command;

use App\Message\ProcessMepsXmlMessage;
use App\Services\FailedToFetchDataException;
use App\Services\HttpRequestsService;
use App\Services\ParliamentMemberService;
use App\Services\WebScraper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'ImportMEPsCommand',
    description: 'Imports MEPs data from the European Parliament website',
)]
class ImportMEPsCommand extends Command
{
    protected static $defaultName = 'app:import-meps';

    public function __construct(
        private readonly HttpRequestsService $httpRequestsService,
        private readonly MessageBusInterface $messageBus,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $xml = $this->httpRequestsService->getData();
            // Dispatch the message with the entire XML
            $this->messageBus->dispatch(new ProcessMepsXmlMessage($xml));
        } catch (FailedToFetchDataException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        } catch (\Exception $e) {
            $io->error('An error occurred: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $io->success('XML data dispatched for processing.');
        return Command::SUCCESS;
    }
}
