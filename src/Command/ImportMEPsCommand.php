<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\Contacts;
use App\Entity\MembersOfEuropeanParliament;
use App\Services\FailedToFetchDataException;
use App\Services\HttpRequestsService;
use App\Services\ParliamentMemberService;
use App\Services\WebScraper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ImportMEPsCommand',
    description: 'Imports MEPs data from the European Parliament website',
)]
class ImportMEPsCommand extends Command
{
    protected static $defaultName = 'app:import-meps';

    public function __construct(
        private readonly EntityManagerInterface  $entityManager,
        private readonly WebScraper              $webScraper,
        private readonly ParliamentMemberService $parliamentMemberService,
        private readonly HttpRequestsService     $httpRequestsService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $xml = $this->httpRequestsService->getData();
        } catch (FailedToFetchDataException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $this->entityManager->getConnection()->beginTransaction();
        try {
            foreach ($xml->mep as $mepData) {
                $refId = (int)$mepData->id;
                $mep = $this->entityManager->getRepository(MembersOfEuropeanParliament::class)->findOneBy(['ref_id' => $refId]);

                if (!$mep) {
                    $mep = new MembersOfEuropeanParliament();
                    $this->entityManager->persist($mep);
                }
                $contactDetails = $this->webScraper->scrape((string)$mepData->fullName, $refId);
                $this->parliamentMemberService->appendData($mep, $mepData);
                $contactIds = [];
                foreach ($contactDetails->getContactDetails() as $contactDetail) {
                    $contact = $this->entityManager->getRepository(Contacts::class)->findOneBy(
                        [
                            'type' => $contactDetail->type,
                            'value' => $contactDetail->value,
                        ]
                    );

                    if ($contact) {
                        $contactIds[] = $mep->getId();
                        continue;
                    }
                    $contact = new Contacts();
                    $contact->setType($contactDetail->type);
                    $contact->setValue($contactDetail->value);
                    $mep->addContact($contact);

                    $this->entityManager->persist($contact);
                }

                if (!empty($contactIdsToDelete)) {
                    $qb = $this->entityManager->createQueryBuilder();

                    // Create a delete query for the Contacts entity
                    $qb->delete('App\Entity\Contacts', 'c')
                        ->where($qb->expr()->notIn('c.id', ':ids'))
                        ->setParameter('ids', $contactIdsToDelete);

                    // Execute the delete query
                    $query = $qb->getQuery();
                    $query->execute();
                }
            }

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            $io->error('Database operation error: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $io->success('MEPs imported or updated successfully.');

        return Command::SUCCESS;
    }
}
