<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Profile extends Model
{
    use HasFactory;

    /**
     * Set the table primary key name (column)
     * @var string
     */
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'gender', 'birthday',
        'address', 'city', 'country_code', 'locale', 'timezone',
    ];

    /**
     * Inverse One-to-One Relationship: Profile belongs to One User
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
