<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SingerRepertoire extends Pivot
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'singer_repertoire';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'repertoire_id',
        'singer_id',
        'singer_name',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
