<?php

namespace App\Entity;

use App\Repository\BankAccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankAccountRepository::class)]
class BankAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'float')]
    private $balance;

    #[ORM\Column(type: 'string', length: 15)]
    private $RIB;

    #[ORM\OneToOne(inversedBy: 'bankAccount', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getRIB(): ?string
    {
        return $this->RIB;
    }

    public function setRIB(string $RIB): self
    {
        $this->RIB = $RIB;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function balanceValid(float $amount = 0): bool
    {
        if ($this->balance + $amount >= 0  && $this->balance + $amount <= 1000) {
            return true;
        } else {
            return false;
        }
    }

    public function deposit(float $amount): self
    {
        if ($this->balanceValid($amount)) {
            $this->balance += $amount;
        } else {
            $this->balance += 1000 - $this->balance;
        }

        return $this;
    }

    public function withdraw(float $amount): self
    {
        if ($this->balanceValid($amount)) {
            $this->balance -= $amount;
        } else {
            $this->balance = 0;
        }

        return $this;
    }

    public function sendEmail(\DateTime $date): bool
    {
        $dateStart = ($date)->setTime(22, 0);
        $dateEnd = ($date)->setTime(6, 0);
        if ($date >= $dateStart && $date <= $dateEnd) {
            return true;
        } else {
            return false;
        }
    }
}
