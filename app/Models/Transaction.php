<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['to_account_id', 'from_account_id', 'type', 'amount', 'user_id', 'transication_status'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    const DEPOSIT       = 1;
    const WITHDRAWL     = 2;
    const TRANSFER      = 3;

    const TRANACTION_TYPE = [
        self::DEPOSIT   => "Deposit",
        self::WITHDRAWL => "Withdrawl",
        self::TRANSFER  => "Transfer",
    ];

    const TRANACTION_DONE = 1; 
    const TRANACTION_UNDONE = 0; 

    public function account()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function deposit($accountId, $type, $amount, $userId)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'amount' => $amount,
        ]);
    }

    public static function withdraw($accountId, $type, $amount, $userId) {

        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'amount' => -$amount,
            'from_account_id' => $accountId
        ]);
    }

    public static function transfer($accountId, $type, $amount, $recipientAccountId, $userId)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'amount' => -$amount,
            'from_account_id' => $accountId,
            'to_account_id' => $recipientAccountId
        ]);
    }

    public static function getAllTransactions($accountId=null)
    {
        return self::where(function($query) use ($accountId)
        {
            if($accountId) {
                $query->where('id', $accountId);
            }
        })->latest()->paginate(10);
    }

}
