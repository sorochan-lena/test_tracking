<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'ip', 'ua', 'ref', 'param1', 'param2', 'error', 'bad_domain'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function badDomain()
    {
        return $this->hasOne('App\BadDomain', 'id', 'bad_domain');
    }
}
