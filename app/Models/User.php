<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * La tabella associata al modello.
     *
     * @var string
     */
    protected $table = 'utenti';

    /**
     * Attiva i timestamps (true è il valore predefinito)
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * Mappa data_registrazione come created_at
     */
    const CREATED_AT = 'data_registrazione';
    
    // Il resto del tuo modello rimane invariato
    protected $fillable = [
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'karma_post',
        'karma_commenti',
        'is_admin',
    ];

    protected $hidden = [
        'password'
    ];

    protected function casts(): array
    {
        return [
            'data_registrazione' => 'datetime',
            'ultimo_accesso' => 'datetime',
            'updated_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
}