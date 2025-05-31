<?php

namespace App\Controller;

use App\DTO\CreateClientRequest;
use App\Domain\Client\Client;
use App\Repositories\ClientRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientController extends AbstractController
{
    #[Route('/api/clients', name: 'create_client', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ClientRepository $repository,
        LoggerInterface $logger
    ): JsonResponse {
        try {
            /** @var CreateClientRequest $requestDto */
            $requestDto = $serializer->deserialize(
                $request->getContent(),
                CreateClientRequest::class,
                'json'
            );
        } catch (\Exception $e) {
            $logger->error('Deserialization error: '.$e->getMessage());
            return $this->json(
                ['error' => 'Invalid request data' . $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        $errors = $validator->validate($requestDto);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $client = new Client(
            $requestDto->name,
            $requestDto->age,
            $requestDto->region,
            $requestDto->income,
            $requestDto->score,
            $requestDto->pin,
            $requestDto->email,
            $requestDto->phone
        );

        $repository->save($client);

        return $this->json([
            'status' => 'success',
            'message' => 'Client created successfully',
            'pin' => $client->getPin()
        ], Response::HTTP_CREATED);
    }
}