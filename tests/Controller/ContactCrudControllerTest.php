<?php

namespace App\Tests\Controller;

use App\Controller\ContactCrudController;
use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use PHPUnit\Framework\TestCase;

class ContactCrudControllerTest extends TestCase
{
    public function testEntityFqcn(): void
    {
        $this->assertSame(Contact::class, ContactCrudController::getEntityFqcn());
    }

    public function testConfigureActionsReturnsActions(): void
    {
        $controller = new ContactCrudController();

        $actions = $controller->configureActions(Actions::new());

        $this->assertInstanceOf(Actions::class, $actions);
    }
}
