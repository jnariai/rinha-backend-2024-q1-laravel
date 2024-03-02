<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExtractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $transactions = collect($this->transactions)->map(fn($transaction) => [
            'valor' => $transaction->value,
            'tipo' => $transaction->type,
            'descricao' => $transaction->description,
            'realizada_em' => $transaction->created_at
        ]);
        return [
            'saldo' => [
                'total' => $this->balance,
                'data_extrato' => Carbon::now(),
                'limite' => $this->limit,
            ],
            'ultimas_transacoes' => $transactions
        ];
    }
}

//"valor": 10,
//      "tipo": "c",
//      "descricao": "descricao",
//      "realizada_em": "2024-01-17T02:34:38.543030Z"
