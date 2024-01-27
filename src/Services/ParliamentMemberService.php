<?php

namespace App\Services;

use App\Entity\MembersOfEuropeanParliament;
use Doctrine\ORM\EntityManagerInterface;

class ParliamentMemberService
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getAll(): array
    {
        $members = $this->entityManager->getRepository(MembersOfEuropeanParliament::class)->findAll();

        foreach ($members as $person) {
            $fullName = explode(' ', $person->getFullName());
            $lastName = array_pop($fullName);
            $data[] = [
                'id' => $person->getId(),
                'firstName' => implode(' ', $fullName),
                'lastName' => $lastName,
                'country' => $person->getCountry(),
                'politicalGroup' => $person->getPoliticalGroup(),
            ];
        }

        return $data;
    }

    public function getById(int $id): array
    {
        $person = $this->entityManager->getRepository(MembersOfEuropeanParliament::class)->find($id);
        if(!$person) {
            return [];
        }
        $fullName = explode(' ', $person->getFullName());
        $lastName = array_pop($fullName);

        $data = [
            'id' => $person->getId(),
            'firstName' => implode(' ', $fullName),
            'lastName' => $lastName,
            'country' => $person->getCountry(),
            'politicalGroup' => $person->getPoliticalGroup(),
            'contacts' => $this->parseContacts($person->getContacts()->toArray()),
        ];

        return $data;
    }

    private function parseContacts(array $contacts): array
    {
        $data = [];
        foreach ($contacts as $contact) {
            $data[] = [
                'type' => $contact->getType(),
                'value' => $contact->getValue(),
            ];
        }

        return $data;
    }
    public function appendData(MembersOfEuropeanParliament $mep, \SimpleXMLElement $mepData): void
    {
        $mep->setFullName((string)$mepData->fullName);
        $mep->setCountry((string)$mepData->country);
        $mep->setPoliticalGroup((string)$mepData->politicalGroup);
        $mep->setRefId((int)$mepData->id);
        $mep->setNationalPoliticalGroup((string)$mepData->nationalPoliticalGroup);
    }
}