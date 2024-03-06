<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ClientServices
{

    public function getClient(int $clientId): Client
    {
        $client = Client::find($clientId);

        if (empty($client)) {
            throw new NotFoundHttpException('Cliente nâo encontrado');
        }

        return $client;
    }

    public function makeTransaction(array $transaction, int $clientId): array
    {
        try {
            DB::beginTransaction();
            $queryClient = Client::whereId($clientId);

            if (!$queryClient->exists()) {
                throw new NotFoundHttpException('Cliente nâo encontrado');
            }
            $client = $queryClient->lockForUpdate()->first();
            $transactionType = $transaction['type'];

            if ($transactionType === 'd') {
                if (!$this->hasLimit($transaction['value'], $client)) {
                    throw new UnprocessableEntityHttpException('Limite de transação ultrapassado');
                }
                $client->balance -= $transaction['value'];
            } else {
                $client->balance += $transaction['value'];
            }
            $client->save();
            Transaction::create([
                'client_id' => $client->id,
                'value' => $transaction['value'],
                'type' => $transaction['type'],
                'description' => $transaction['description']
            ]);

            DB::commit();
            return ['limite' => $client->limit, 'saldo' => $client->balance];
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage(), $exception->getStatusCode());
        }
    }

    public function hasLimit(float $value, Client $client): bool
    {
        return ($client->limit + $client->balance - $value) >= 0;
    }

}
