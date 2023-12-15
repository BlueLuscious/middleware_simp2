<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $table = 'debts';
    protected $fillable = [
        'code',
        'status',
        'api_client',
        'ccf_code',
        'ccf_client_id',
        'ccf_client_data',
        'client_origin_id',
        'subdebts',
    ];
}