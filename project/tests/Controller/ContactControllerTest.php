<?php

namespace App\Test\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ContactRepository $repository;
    private string $path = '/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Contact::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Contact index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'contact[firstName]' => 'firstName',
            'contact[lastName]' => 'lastName',
            'contact[phoneNumber]' => '123456',
            'contact[email]' => 'test@test.test',
            'contact[note]' => 'Testing',
        ]);

        self::assertResponseRedirects('/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $fixture = new Contact();
        $fixture->setFirstName('My FirstName');
        $fixture->setLastName('My LastName');
        $fixture->setPhoneNumber('123456789');
        $fixture->setEmail('My@test.email');
        $fixture->setNote('My note');
        $fixture->setSlug('my-first-name-my-last-name');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getSlug()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Contact');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $fixture = new Contact();
        $fixture->setFirstName('My');
        $fixture->setLastName('Title');
        $fixture->setPhoneNumber('123456');
        $fixture->setEmail('title@my.test');
        $fixture->setNote('My Title note');
        $fixture->setSlug('my-title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getSlug()));

        $this->client->submitForm('Update', [
            'contact[firstName]' => 'MyNew',
            'contact[lastName]' => 'TitleNew',
            'contact[phoneNumber]' => '987654321',
            'contact[email]' => 'new-test@title.mail',
            'contact[note]' => 'My Title note',
        ]);

        self::assertResponseRedirects('/');

        $fixture = $this->repository->findAll();

        self::assertSame('MyNew', $fixture[0]->getFirstName());
        self::assertSame('TitleNew', $fixture[0]->getLastName());
        self::assertSame('987654321', $fixture[0]->getPhoneNumber());
        self::assertSame('new-test@title.mail', $fixture[0]->getEmail());
        self::assertSame('My Title note', $fixture[0]->getNote());
    }

    public function testRemove(): void
    {

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Contact();
        $fixture->setFirstName('My');
        $fixture->setLastName('Title');
        $fixture->setPhoneNumber('123456');
        $fixture->setEmail('title@my.test');
        $fixture->setNote('My Title note');
        $fixture->setSlug('my-title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getSlug()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/');
    }
}
