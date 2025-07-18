<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Enums\InvoiceStatusEnum;
use Botble\Payment\Models\Payment;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Invoice extends BaseModel
{
    protected $table = 'ec_invoices';

    protected $fillable = [
        'code',
        'reference_id',
        'reference_type',
        'customer_name',
        'company_name',
        'company_logo',
        'customer_email',
        'customer_phone',
        'customer_address',
        'customer_tax_id',
        'sub_total',
        'tax_amount',
        'shipping_amount',
        'shipping_tax_amount',
        'payment_fee',
        'discount_amount',
        'amount',
        'payment_id',
        'status',
        'paid_at',
        'shipping_method',
        'shipping_option',
        'coupon_code',
        'discount_description',
        'description',
    ];

    protected $casts = [
        'sub_total' => 'float',
        'tax_amount' => 'float',
        'shipping_amount' => 'float',
        'shipping_tax_amount' => 'float',
        'payment_fee' => 'float',
        'discount_amount' => 'float',
        'amount' => 'float',
        'status' => InvoiceStatusEnum::class,
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice): void {
            $invoice->code = static::generateUniqueCode();
        });

        static::deleted(function (Invoice $invoice): void {
            $invoice->items()->delete();
        });
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class)->withDefault();
    }

    public static function generateUniqueCode(): string
    {
        $prefix = get_ecommerce_setting('invoice_code_prefix', 'INV-');
        $nextInsertId = BaseModel::determineIfUsingUuidsForId() ? static::query()->count() + 1 : static::query()->max('id') + 1;

        do {
            $code = sprintf('%s%d', $prefix, $nextInsertId);
            $nextInsertId++;
        } while (static::query()->where('code', $code)->exists());

        return $code;
    }

    protected function taxClassesName(): Attribute
    {
        return Attribute::get(function () {
            $taxes = [];

            foreach ($this->items as $invoiceItem) {
                if (! $invoiceItem->tax_amount || empty($invoiceItem->options['taxClasses'])) {
                    continue;
                }

                foreach ($invoiceItem->options['taxClasses'] as $taxName => $taxRate) {
                    $taxes[] = $taxName . ' - ' . $taxRate . '%';
                }
            }

            return implode(', ', array_unique($taxes));
        });
    }
}
