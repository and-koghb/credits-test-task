<?php

namespace App\Controller;

use App\DTO\ApplyForCreditRequest;
use App\Repositories\CreditRepository;
use App\Services\GiveCreditService;
use App\Domain\Credit\Credit;
use App\Repositories\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreditController extends AbstractController
{
    #[Route('/api/credits/apply', name: 'check_credit', methods: ['POST'])]
    public function apply(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ClientRepository $clientRepository,
        GiveCreditService $giveCreditService
    ): JsonResponse
    {
        $request = $serializer->deserialize($request->getContent(), ApplyForCreditRequest::class, 'json');

        $errors = $validator->validate($request);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $client = $clientRepository->findByPin($request->pin);
        if (!$client) {
            return $this->json(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }

        $credit = new Credit(
            $request->name,
            $request->amount,
            $request->rate,
            new \DateTime($request->startDate),
            new \DateTime($request->endDate),
            $client->getPin()
        );

        $result = $giveCreditService->checkApplication($client, $credit);

        return $this->json($result);
    }

    #[Route('/api/credits/give', name: 'approve_credit', methods: ['POST'])]
    public function give(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ClientRepository $clientRepository,
        GiveCreditService $giveCreditService,
        CreditRepository $creditRepository
    ): JsonResponse {
        $request = $serializer->deserialize($request->getContent(), ApplyForCreditRequest::class, 'json');

        $errors = $validator->validate($request);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $client = $clientRepository->findByPin($request->pin);
        if (!$client) {
            return $this->json(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        }

        $credit = new Credit(
            $request->name,
            $request->amount,
            $request->rate,
            new \DateTime($request->startDate),
            new \DateTime($request->endDate),
            $client->getPin()
        );

        try {
            $giveCreditService->giveCredit($client, $credit);
            return $this->json([
                'status' => 'approved',
                'message' => 'Credit given5.',
                'final_rate' => $credit->getRate() . '%'
            ]);
        } catch (\RuntimeException $e) {
            return $this->json([
                'status' => 'rejected',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
