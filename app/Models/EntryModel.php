<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class EntryModel extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entries';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
        'data' => 'json',
        'published' => 'bool',
    ];

    public function origin()
    {
        return $this->belongsTo(self::class);
    }
}
