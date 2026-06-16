<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DownPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'receipt_number',
        'amount',
        'payment_method',
        'reference_number',
        'payment_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique receipt number
     */
    public static function generateReceiptNumber(): string
    {
        $prefix = date('Ymd');
        $lastPayment = self::where('receipt_number', 'like', "DP-{$prefix}-%")
            ->latest('id')
            ->first();

        if ($lastPayment) {
            $lastSeq = (int) substr($lastPayment->receipt_number, -4);
            $newSeq = str_pad($lastSeq + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSeq = '0001';
        }

        return "DP-{$prefix}-{$newSeq}";
    }
}
