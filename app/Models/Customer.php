<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends \Eloquent
{
    use HasFactory;

    protected $fillable =
    [
        'name',
        'type',
        'email',
        'address',
        'city',
        'state',
        'postal_code'
    ];

    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
