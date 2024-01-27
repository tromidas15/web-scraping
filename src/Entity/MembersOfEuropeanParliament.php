<?php

namespace App\Entity;

use App\Repository\MembersOfEuropeanParliamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembersOfEuropeanParliamentRepository::class)]
class MembersOfEuropeanParliament
{
    #[ORM\OneToMany(mappedBy: 'memberOfEuropeanParliament', targetEntity: Contacts::class)]
    private Collection $contacts;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $full_name = null;

    //longest european country name is 53 characters UK 55 for fail safe
    #[ORM\Column(length: 55)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $political_group = null;

    #[ORM\Column]
    private ?int $ref_id = null;

    #[ORM\Column(length: 255)]
    private ?string $national_political_group = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): static
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getPoliticalGroup(): ?string
    {
        return $this->political_group;
    }

    public function setPoliticalGroup(string $political_group): static
    {
        $this->political_group = $political_group;

        return $this;
    }

    public function getRefId(): ?int
    {
        return $this->ref_id;
    }

    public function setRefId(int $ref_id): static
    {
        $this->ref_id = $ref_id;

        return $this;
    }

    public function getNationalPoliticalGroup(): ?string
    {
        return $this->national_political_group;
    }

    public function setNationalPoliticalGroup(string $national_political_group): static
    {
        $this->national_political_group = $national_political_group;

        return $this;
    }

    public function addContact(Contacts $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setMemberOfEuropeanParliament($this);
        }

        return $this;
    }

    public function removeContact(Contacts $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getMemberOfEuropeanParliament() === $this) {
                $contact->setMemberOfEuropeanParliament(null);
            }
        }

        return $this;
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }
}
