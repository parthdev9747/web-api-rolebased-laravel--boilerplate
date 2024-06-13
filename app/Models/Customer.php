<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country');
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency');
    }
}
