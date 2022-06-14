<?php

use App\Entity\BankAccount;
use App\Entity\Item;
use App\Entity\TodoList;
use App\Entity\User;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class OperationTest extends TestCase
{
    private BankAccount $bankAccount;
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();

        $this->user->setLastName('Doe');
        $this->user->setFirstName('John');
        $this->user->setEmail('john.doe@gmail.com');
        $this->user->setRoles(['ROLE_USER']);
        // pwd : test
        $this->user->setPassword('$2y$13$hfqNoOxwWmYHRovjrN11HecsZQ/5C5vFGcRMmQ/krnaO6u591wSDC');

        $this->bankAccount = new BankAccount();
        $this->bankAccount->setOwner($this->user);
        $this->bankAccount->setBalance(0);
        $this->bankAccount->setRIB('FR1234567890123');

        parent::setUp();
    }

    public function testDepositOperation(): void
    {
        $this->bankAccount->deposit(1100);
        $this->assertEquals(1000, $this->bankAccount->getBalance());
    }

    public function testWithdrawOperation(): void
    {
        $this->bankAccount->withdraw(1100);
        $this->assertEquals(0, $this->bankAccount->getBalance());
    }
}
