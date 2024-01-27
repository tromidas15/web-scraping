<?php

namespace App\Entity;

use App\Repository\ContactsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactsRepository::class)]
class Contacts
{
    #[ORM\ManyToOne(targetEntity: MembersOfEuropeanParliament::class, inversedBy: 'contacts')]
    #[ORM\JoinColumn(name: "member_id", referencedColumnName: "id", nullable: false)]
    private ?MembersOfEuropeanParliament $memberOfEuropeanParliament = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $type = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    private ?int $memberId = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getMemberOfEuropeanParliament(): ?MembersOfEuropeanParliament
    {
        return $this->memberOfEuropeanParliament;
    }

    public function setMemberOfEuropeanParliament(?MembersOfEuropeanParliament $memberOfEuropeanParliament): self
    {
        $this->memberOfEuropeanParliament = $memberOfEuropeanParliament;

        return $this;
    }
}
