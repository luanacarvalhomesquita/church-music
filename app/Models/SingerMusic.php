<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SingerMusic extends Pivot
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'singer_music';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'singer_id',
        'music_id',
        'tone',
        'user_id',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
