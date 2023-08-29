<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Wave\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'verification_code',
        'verified',
        'trial_ends_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];

    /**
     * Get the domains for the user.
     */
    public function domains(){
        return $this->hasMany(Domain::class);
    }

    /**
     * Get the scans for the user.
     */
    public function scans()
    {
        return $this->hasMany(Scan::class);
    }

    public function useTokens($count = 1){
        $this->available_tokens = $this->available_tokens - $count;
        $this->used_tokens = $this->used_tokens + $count;
        $this->save();
    }

    public function addTokens($count = 1){
        $this->total_tokens = $this->total_tokens + $count;
        $this->available_tokens = $this->available_tokens + $count;
        $this->save();
    }


}
