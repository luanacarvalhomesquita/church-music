<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetsPassword extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'password_resets';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'pin',
        'expired_date',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
