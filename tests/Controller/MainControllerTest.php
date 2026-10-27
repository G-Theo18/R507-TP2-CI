<?php

namespace App\Tests\Controller;

use App\Controller\MainController;
use App\Entity\Contact;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testListPageIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/liste');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginPageIsDisplayed(): void
    {
        $client = static::createClient();
        $client->request('GET', '/connexion');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginRedirectsWhenAlreadyAuthenticated(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get(EntityManagerInterface::class);

        $user = (new User())
            ->setUsername('test_'.uniqid())
            ->setPassword('not-used')
            ->setRoles(['ROLE_ADMIN']);
        $em->persist($user);
        $em->flush();

        $client->loginUser($user);
        $client->request('GET', '/connexion');

        $this->assertResponseRedirects();
    }

    public function testContactSubmissionIsSaved(): void
    {
        $client = static::createClient();
        $message = 'Message '.uniqid();

        $client->request('GET', '/');
        $client->submitForm('Envoyer', [
            'form[firstName]' => 'John',
            'form[name]' => 'Doe',
            'form[message]' => $message,
        ]);

        $this->assertResponseRedirects('/');

        $em = static::getContainer()->get(EntityManagerInterface::class);
        $contact = $em->getRepository(Contact::class)->findOneBy(['message' => $message]);

        $this->assertNotNull($contact);
        $this->assertSame('new', $contact->getStatus());
    }

    public function testLogoutMethodThrowsException(): void
    {
        $controller = new MainController();

        $this->expectException(\LogicException::class);
        $controller->logout();
    }
}
