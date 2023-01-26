<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $id;
    protected $currency_code;
    protected $name;
    protected $exchange_rate;
    use HasFactory;
}
