<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Transaction;

class TransactionResource extends JsonResource
{
    /**
     * @var float The running balance
     */
    protected static $runningBalance = 0;

    /**
     * @var float The previous amount
     */
    protected static $prevAmt = 0;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        // Calculate new balance
        $balance = $this->calculateBalance($this->amount, $this->getType());

        // Update previous amount for the next transaction
        $this->setPrevAmt($balance);

        return [
            'id' => $this->id,
            'transaction_date' => $this->created_at->toDateTimeString(),
            'amount' => $this->amount,
            'type' => $this->getType(),
            'details' => $this->getDetails(),
            'balance' => $balance,
        ];
    }

    /**
     * Get the type of transaction.
     *
     * @return string
     */
    protected function getType(): string
    {
        return ($this->type === 'Deposit') ? 'Credit' : 'Debit';
    }

    /**
     * Get the details of the transaction.
     *
     * @return string
     */
    protected function getDetails(): string
    {
        if ($this->type === 'Transfer') {
            // Check if the account is loaded to avoid MissingValue error
            if ($this->relationLoaded('account')) {
                return 'Transfer to ' . $this->account->user->email;
            }
            // If account is not loaded, return a default value or handle it accordingly
            return 'Transfer';
        }
        
        return 'Deposit';
    }

    /**
     * Calculate the balance based on the type of transaction.
     *
     * @return float
     */
    protected function calculateBalance($amount, $type): float
    {
        switch ($type) {
            case 'Credit':
                static::$runningBalance += $amount;
                break;
            case 'Debit':
                static::$runningBalance -= $amount;
                break;
        }
        
        return static::$runningBalance;
    }

    /**
     * Get the previous amount.
     *
     * @return float
     */
    protected function getPrevAmt(): float
    {
        return static::$prevAmt;
    }

    /**
     * Set the previous amount.
     *
     * @param float $balance
     * @return void
     */
    protected function setPrevAmt(float $balance): void
    {
        static::$prevAmt = $balance;
    }
}
