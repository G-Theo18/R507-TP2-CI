<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTest extends TestCase
{
    public function testSettersAndGetters(): void
    {
        $user = (new User())
            ->setUsername('admin')
            ->setPassword('hashed');

        $this->assertNull($user->getId());
        $this->assertSame('admin', $user->getUsername());
        $this->assertSame('admin', $user->getUserIdentifier());
        $this->assertSame('hashed', $user->getPassword());
    }

    public function testRolesAlwaysContainRoleUser(): void
    {
        $user = new User();

        $this->assertSame(['ROLE_USER'], $user->getRoles());
    }

    public function testRolesAreMergedWithoutDuplicates(): void
    {
        $user = (new User())->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $roles = $user->getRoles();

        $this->assertCount(2, $roles);
        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles);
    }

    public function testPlainPassword(): void
    {
        $user = (new User())->setPlainPassword('secret');

        $this->assertSame('secret', $user->getPlainPassword());
    }

    public function testInitPasswordWithoutPlainPasswordDoesNothing(): void
    {
        $hasher = $this->createStub(UserPasswordHasherInterface::class);

        $user = (new User())->initPassword($hasher);

        $this->assertNull($user->getPassword());
    }

    public function testInitPasswordHashesPlainPassword(): void
    {
        $hasher = $this->createStub(UserPasswordHasherInterface::class);
        $hasher->method('hashPassword')->willReturn('hashed_secret');

        $user = (new User())
            ->setPlainPassword('secret')
            ->initPassword($hasher);

        $this->assertSame('hashed_secret', $user->getPassword());
    }
}
