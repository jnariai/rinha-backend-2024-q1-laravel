<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Services\ClientServices;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    public function __construct(protected ClientServices $clientServices)
    {

    }

    public function storeTransaction(TransactionRequest $transactionRequest, int $clientId): JsonResponse
    {
        $transaction = [
            'value' => $transactionRequest->valor,
            'type' => $transactionRequest->tipo,
            'description' => $transactionRequest->descricao];
        $newBalance = $this->clientServices->makeTransaction($transaction, $clientId);

        return response()->json($newBalance);
    }

    public function getExtract(int $clientId): JsonResponse
    {
        $client = $this->clientServices->getClient($clientId);
        $lastTransactions = Transaction::where('client_id', $clientId)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $transactions = $lastTransactions->map(fn($transaction) => [
            'valor' => $transaction->value,
            'tipo' => $transaction->type,
            'descricao' => $transaction->description,
            'realizada_em' => $transaction->created_at
        ]);
        return response()->json([
            'saldo' => [
                'total' => $client->balance,
                'data_extrato' => Carbon::now(),
                'limite' => $client->limit,
            ],
            'ultimas_transacoes' => $transactions
        ]);

    }
}
