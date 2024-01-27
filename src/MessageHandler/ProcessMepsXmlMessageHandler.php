<?php

namespace App\MessageHandler;

use App\Entity\Contacts;
use App\Entity\MembersOfEuropeanParliament;
use App\Message\ProcessMepsXmlMessage;
use App\Services\ParliamentMemberService;
use App\Services\WebScraper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ProcessMepsXmlMessageHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WebScraper $webScraper,
        private readonly ParliamentMemberService $parliamentMemberService
    ) {
    }

    public function __invoke(ProcessMepsXmlMessage $message)
    {
        $xml = $message->getXml();
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

                    $qb->delete('App\Entity\Contacts', 'c')
                        ->where($qb->expr()->notIn('c.id', ':ids'))
                        ->setParameter('ids', $contactIdsToDelete);

                    $query = $qb->getQuery();
                    $query->execute();
                }
            }

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            //to add logs
            $this->entityManager->getConnection()->rollBack();
        }
    }
}
