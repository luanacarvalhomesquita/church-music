<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Repertoire extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'repertoires';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'title',
        'date',
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
     * @return BelongsToMany
     */
    public function musics()
    {
        return $this->belongsToMany(Music::class, 'music_repertoire');
    }

    /**
     * @return BelongsToMany
     */
    public function singers()
    {
        return $this->belongsToMany(Singer::class, 'singer_repertoire');
    }
}
