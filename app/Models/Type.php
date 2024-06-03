<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Type extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'types';

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
     * @return BelongsToMany
     */
    public function musics()
    {
        return $this->hasMany(Music::class);
    }
}
