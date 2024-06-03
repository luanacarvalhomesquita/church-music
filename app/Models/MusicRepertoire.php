<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MusicRepertoire extends Pivot
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'music_repertoire';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'repertoire_id',
        'music_id',
        'music_name',
        'tone',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
