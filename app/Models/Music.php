<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Music extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'music';

    /**
     * @var array<string>
     */
    public const TONES = [
        'Ab',
        'A#',
        'A#m',
        'A',
        'Am',
        'Bb',
        'B#',
        'B#m',
        'B',
        'Bm',
        'Cb',
        'C#',
        'C#m',
        'C',
        'Cm',
        'Db',
        'D#',
        'D#m',
        'D',
        'Dm',
        'Eb',
        'E#',
        'E#m',
        'E',
        'Em',
        'Gb',
        'G#',
        'G#m',
        'G',
        'Gm',
    ];

    protected $fillable = [
        'id',
        'name',
        'description',
        'main_version',
        'played',
        'type_id',
        'user_id',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'user_id',
    ];

    /**
     * @return BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

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
    public function singer()
    {
        return $this->belongsToMany(Singer::class);
    }

    /**
     * @return BelongsToMany
     */
    public function repertoires()
    {
        return $this->belongsToMany(
            Repertoire::class,
            'music_repertoire'
        );
    }
}
