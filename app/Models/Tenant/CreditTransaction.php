<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CreditTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'reference_type',
        'reference_id',
        'performed_by',
    ];

    // Transaction types
    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';

    // Reference types
    const REF_COSEC_ORDER = 'cosec_order';
    const REF_ADMIN_ADJUSTMENT = 'admin_adjustment';
    const REF_TOP_UP = 'top_up';
    const REF_REFUND = 'refund';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Add credits to a user's account
     */
    public static function addCredits($userId, $amount, $description, $referenceType = null, $referenceId = null, $performedBy = null)
    {
        $user = User::findOrFail($userId);
        $balanceBefore = $user->credit ?? 0;
        $balanceAfter = $balanceBefore + $amount;

        // Update user balance
        $user->credit = $balanceAfter;
        $user->save();

        // Log transaction
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_CREDIT,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'performed_by' => $performedBy,
        ]);
    }

    /**
     * Deduct credits from a user's account
     */
    public static function deductCredits($userId, $amount, $description, $referenceType = null, $referenceId = null, $performedBy = null)
    {
        $user = User::findOrFail($userId);
        $balanceBefore = $user->credit ?? 0;
        $balanceAfter = $balanceBefore - $amount;

        // Update user balance
        $user->credit = $balanceAfter;
        $user->save();

        // Log transaction
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_DEBIT,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'performed_by' => $performedBy,
        ]);
    }

    /**
     * Get formatted type label
     */
    public function getTypeLabelAttribute()
    {
        return $this->type === self::TYPE_CREDIT ? 'Credit' : 'Debit';
    }

    /**
     * Get formatted amount with sign
     */
    public function getFormattedAmountAttribute()
    {
        $sign = $this->type === self::TYPE_CREDIT ? '+' : '-';
        return $sign . ' RM ' . number_format($this->amount, 0);
    }
}
