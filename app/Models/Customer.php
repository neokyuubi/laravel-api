<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends \Eloquent
{
    use HasFactory;

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
