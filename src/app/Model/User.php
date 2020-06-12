<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticable implements JWTSubject 
{
    use Notifiable;

    protected $table = "user";

    protected $fillable = [
        'name', 'email', 
    ];

    public function subscription()
    {
        return $this->hasMany('App\model\subscription', 'subscripton_id');
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
}
