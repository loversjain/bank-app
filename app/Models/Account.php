<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'balance'];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    const DEACTIVE = 0;
    const ACTIVE = 1;
    const BALANCE_ZERO = 0;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, );
    }

    public static function createAccount($userId)
    {
        $resonse = static::create([
                    'user_id' => $userId
                    ]);
        return $resonse;
    }

    public static function getOneActiveAccount($userId)
    {
        $resonse = static::where('user_id', $userId)->whereStatus(self::ACTIVE)->first();
        return $resonse;
    }
    public static function getAllAccount()
    {
        $resonse = static::whereStatus(self::ACTIVE)->get();
        return $resonse;
    }
}
