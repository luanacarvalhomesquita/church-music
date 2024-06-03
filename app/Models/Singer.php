<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Singer extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'singers';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'user_id',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /** 
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany
     */
    public function musics()
    {
        return $this->belongsToMany(
            Music::class,
            'singer_music',
            'singer_id',
            'music_id',
        );
    }

    /**
     * @return BelongsToMany
     */
    public function repertoires()
    {
        return $this->belongsToMany(
            Repertoire::class,
            'singer_repertoire'
        );
    }
}
