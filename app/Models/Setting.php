<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    const CACHE_KEY = 'app_settings';

    protected $primaryKey = 'name';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name', 'value', 'group',
    ];
}
