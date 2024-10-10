<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLogs extends Model
{
    use HasFactory;

    protected  $table = 'request_logs';

    protected $fillable = [
        'request_method',
        'request_url',
        'user_agent',
        'ip_address',
        'request_body',
        'request_time',
        
        'response_body',
        'response_status_code'
    ];
}
