<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Transaction;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ClientServices
{

    public function getClientById(int $clientId): Client
    {
        $client = Client::find($clientId);

        if (empty($client)) {
            throw new NotFoundHttpException('Cliente nâo encontrado');
        }

        return $client;
    }

    public function makeTransaction(array $transaction, Client $client): array
    {
        $transactionType = $transaction['type'];

        $transactionType === 'd' ? $this->processDebitTransaction($transaction['value'], $client) : $this->processCreditTransaction($transaction['value'], $client);

        $this->recordTransaction($transaction, $client);

        return ['limite' => $client->limit, 'saldo' => $client->balance];
    }

    public function processDebitTransaction(float $value, Client $client): void
    {
        if (!$this->hasLimit($value, $client)) {
            throw new UnprocessableEntityHttpException('Limite de transação ultrapassado');
        }
        $client->balance -= $value;
        $client->save();
    }

    public function hasLimit(float $value, Client $client): bool
    {
        return ($client->limit + $client->balance - $value) >= 0;
    }

    public function processCreditTransaction(float $value, Client $client): void
    {
        $client->balance += $value;
        $client->save();
    }

    public function recordTransaction(array $transaction, Client $client): void
    {
        Transaction::create([
            'client_id' => $client->id,
            'value' => $transaction['value'],
            'type' => $transaction['type'],
            'description' => $transaction['description']
        ]);
    }

}
