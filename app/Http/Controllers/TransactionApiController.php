<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\WithdrawRequest;
use Carbon\Carbon;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\User;
use App\Models\Transaction;

class TransactionApiController extends Controller
{
    public function deposit(DepositRequest $request)
    {
        $user = $request->user();
        $account = $user->account;
        $oldBalance =  $account->balance;

        if(empty($account)) {
            return response()->json(['error' => 'Deactive Account.'], 400);
        }

        $transaction = Transaction::deposit($account->id, Transaction::DEPOSIT, $request->amount, $user->id);

        if(!empty($transaction)) {
            
            $account->balance = $transaction->amount + $oldBalance;
            $account->save();
            $transaction->transication_status = Transaction::TRANACTION_DONE;
            $transaction->save();
        }
        return response()->json(['message' => 'Deposit successful!']);
    }

    public function withdraw(WithdrawRequest $request)
    {
        $user = $request->user();
        $account = $user->account;

        $oldBalance = $user->account->balance;
        $amount = $request->amount;

        if ($oldBalance < $amount) {
            return response()->json(['error' => 'Insufficient balance'], 201);
        }

        try {
            $transaction = Transaction::withdraw($account->id, Transaction::WITHDRAWL, $amount, $user->id);
            if(!empty($transaction)) {
                $account->balance =  $oldBalance - $amount;
                $account->save();
                $transaction->transication_status = Transaction::TRANACTION_DONE;
                $transaction->save();
            }
            return response()->json(['message' => 'Withdrawal successful!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Withdrawal unsuccessful!'], 400);

            \Log::error("error", ["error"=>$e->getMessage(), "trace"=>$e->getTraceAsString()]);
        }

        

        
    }

    public function transfer(TransferRequest $request)
    {
        $user = $request->user();
        $account = $user->account;

        $oldBalance = $account->balance;
        $amount = $request->amount;

        if ($oldBalance < $amount) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        $recipientEmail = $request->email;
        $recipient = User::checkEmailExist( $recipientEmail);

        if (!$recipient) {
            return response()->json(['error' => 'Recipient not found'], 404);
        }

        $recipientAccountId = $recipient->account->id;

        $transaction = Transaction::transfer($account->id, Transaction::TRANSFER, $amount, $recipientAccountId, $user->id);
        if(!empty($transaction)) {
                $account->balance =  $oldBalance - $amount;
                $account->save();

                $recipientOldBalace = $recipient->account->balance;
                $recipient->account->balance =  $recipientOldBalace + $amount;
                $recipient->account->save();

                $transaction->transication_status = Transaction::TRANACTION_DONE;
                $transaction->save();
            }

        return response()->json(['message' => 'Transfer successful!']);
    }

    public function statement(Request $request)
    {
        $user = $request->user();

        $transactions = Transaction::getAllTransactions();

        return TransactionResource::collection($transactions);
    }
}
