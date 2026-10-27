<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    public function testNewContactIsEmpty(): void
    {
        $contact = new Contact();

        $this->assertNull($contact->getId());
        $this->assertNull($contact->getFirstName());
        $this->assertNull($contact->getName());
        $this->assertNull($contact->getMessage());
        $this->assertNull($contact->getCreatedAt());
        $this->assertNull($contact->getStatus());
    }

    public function testSettersAndGetters(): void
    {
        $date = new \DateTimeImmutable('2026-01-15 10:00:00');

        $contact = (new Contact())
            ->setFirstName('John')
            ->setName('Doe')
            ->setMessage('Test message')
            ->setCreatedAt($date)
            ->setStatus('new');

        $this->assertSame('John', $contact->getFirstName());
        $this->assertSame('Doe', $contact->getName());
        $this->assertSame('Test message', $contact->getMessage());
        $this->assertSame($date, $contact->getCreatedAt());
        $this->assertSame('new', $contact->getStatus());
    }
}
