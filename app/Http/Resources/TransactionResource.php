<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Transaction;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_date' => $this->created_at->toDateTimeString(),
            'amount' => $this->amount,
            'type' => ($this->amount > 0) ? 'Credit' : 'Debit',
            'details' => $this->type === 'Transfer' ? 'Transfer to ' . $this->whenLoaded('account', $this->account->user->email) : $this->type,
            'balance'  => $this->calculateBalance(),
            
        ];
    }

    /**
     * Calculate the balance based on the type of transaction.
     *
     * @return float
     */
    protected function calculateBalance(): float
    {
         // Start with the initial balance from the user's account
        $balance = 0;

        // Start with the initial balance from the user's account
    

    // Retrieve all transactions related to the user's account
    $transactions = Transaction::where('user_id', $this->user->id)
        ->orderBy('created_at', 'asc')
        ->get();

    // Iterate through the transactions and update the balance
    foreach ($transactions as $transaction) {
        if ($transaction->type === 'Deposit' || $transaction->type === 'Transfer') {
            $balance += $transaction->amount;
        } elseif ($transaction->type === 'Withdrawal') {
            $balance -= $transaction->amount;
        }
    }

        return $balance;
    }
}
