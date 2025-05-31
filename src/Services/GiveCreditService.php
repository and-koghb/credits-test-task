<?php

namespace App\Services;

use App\Domain\Client\Client;
use App\Domain\Credit\ApplicationDecision;
use App\Domain\Credit\Credit;
use App\Notifiers\LogNotifier;
use App\Repositories\CreditRepository;

class GiveCreditService
{
    public function __construct(
        private LogNotifier $notifier,
        private iterable $rules,
        private iterable $modifiers,
        private ApplicationService $applicationService,
        private CreditRepository $creditRepository
    ) {}

    public function checkApplication(Client $client, Credit $credit): array
    {
        if ($this->creditRepository->exists($credit)) {
            throw new \RuntimeException('You already got this credit.');
        }

        $cacheKey = $this->getCacheKey($client, $credit);
        if ($decision = $this->applicationService->getDecision($cacheKey)) {
            return $decision->toArray();
        }

        $decision = $this->makeDecision($client, $credit);
        $this->applicationService->saveDecision($cacheKey, $decision);

        return $decision->toArray();
    }

    public function giveCredit(Client $client, Credit $credit): void
    {
        if ($this->creditRepository->exists($credit)) {
            throw new \RuntimeException('You already got this credit.');
        }

        $decision = $this->checkApplication($client, $credit);

        if (empty($decision['approved'])) {
            $message = sprintf(
                'Credit rejected for %s. Reasons: %s',
                $client->getName(),
                implode(', ', $decision['messages'])
            );
            $this->notifier->notify($message);
            throw new \RuntimeException($message);
        }

        $this->creditRepository->save($credit);

        $this->notifier->notify(sprintf(
            'Credit approved for %s. Amount: %s, Rate: %s%%',
            $client->getName(),
            $credit->getAmount(),
            $decision['final_rate']
        ));
    }

    private function makeDecision(Client $client, Credit $credit): ApplicationDecision
    {
        $approved = true;
        $messages = [];

        foreach ($this->rules as $rule) {
            if (!$rule->isApproved($client, $credit)) {
                $approved = false;
                $messages[] = $rule->getMessage();
            }
            $rule->applyConditions($client, $credit);
        }

        foreach ($this->modifiers as $modifier) {
            $modifier->modify($credit, $client);
        }

        return new ApplicationDecision(
            $approved,
            $credit->getRate(),
            $messages
        );
    }

    private function getCacheKey(Client $client, Credit $credit): string
    {
        return md5($client->getPin().serialize($credit));
    }
}
