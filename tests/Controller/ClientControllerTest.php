<?php

namespace App\Tests\Controller;

use App\Repositories\ClientRepository;
use App\DTO\CreateClientRequest;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientControllerTest extends WebTestCase
{
    private ClientRepository $clientRepository;
    private ValidatorInterface $validator;
    private KernelBrowser $clientApp;

    protected function setUp(): void
    {
        $this->clientApp = static::createClient();
        $container = $this->clientApp->getContainer();

        $this->clientRepository = $container->get(\App\Tests\Fakes\TestClientRepository::class);
        $this->validator = $container->get(ValidatorInterface::class);
    }

    public function testCreateClientSuccess()
    {
        $client = $this->createTestClient();

        $response = $this->makeRequest($client);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('pin', $responseData);
    }

    public function testCreateClientValidationErrors()
    {
        $invalidClient = new CreateClientRequest();
        $invalidClient->name = '';
        $invalidClient->age = 17;
        $invalidClient->region = 'XX';
        $invalidClient->income = -100;
        $invalidClient->score = 1001;
        $invalidClient->pin = 'invalid!';
        $invalidClient->email = 'not-an-email';
        $invalidClient->phone = '123456789';

        $errors = $this->validator->validate($invalidClient);
        $this->assertGreaterThan(0, count($errors));
    }

    public function testCreateClientWithDuplicatePin()
    {
        $client = $this->createTestClient();

        $clientDomainObject = new \App\Domain\Client\Client(
            $client->name,
            $client->age,
            $client->region,
            $client->income,
            $client->score,
            $client->pin,
            $client->email,
            $client->phone
        );

        $this->clientRepository->save($clientDomainObject);

        $response = $this->makeRequest($client);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertStringContainsString(
            'There already is a user with the provided PIN.',
            $responseData['detail'] ?? ''
        );
    }

    private function createTestClient(): CreateClientRequest
    {
        $client = new CreateClientRequest();
        $client->name = "Jan Novak";
        $client->age = 30;
        $client->region = "PR";
        $client->income = 1500;
        $client->score = 600;
        $client->pin = "123-45-6789";
        $client->email = "jan.novak@example.com";
        $client->phone = "+420123456789";

        return $client;
    }

    private function makeRequest(CreateClientRequest $client): Response
    {
        $data = [
            'name' => $client->name,
            'age' => $client->age,
            'region' => $client->region,
            'income' => $client->income,
            'score' => $client->score,
            'pin' => $client->pin,
            'email' => $client->email,
            'phone' => $client->phone
        ];

        $this->clientApp->request('POST', '/api/clients', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($data));

        return $this->clientApp->getResponse();
    }
}
