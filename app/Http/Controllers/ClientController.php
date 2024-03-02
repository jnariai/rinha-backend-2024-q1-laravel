<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\ExtractResource;
use App\Services\ClientServices;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    public function __construct(protected ClientServices $clientServices)
    {

    }

    public function storeTransaction(TransactionRequest $transactionRequest, int $clientId): JsonResponse
    {
        $client = $this->clientServices->getClientById($clientId);
        $transaction = [
            'value' => $transactionRequest->valor,
            'type' => $transactionRequest->tipo,
            'description' => $transactionRequest->descricao];
        $newBalance = $this->clientServices->makeTransaction($transaction, $client);

        return response()->json($newBalance);
    }

    public function getExtract(int $clientId): JsonResponse
    {
        $client = $this->clientServices->getClientById($clientId);

        return response()->json(new ExtractResource($client));
    }
}
