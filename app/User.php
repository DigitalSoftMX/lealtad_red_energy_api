<?php

namespace App;

use App\Models\Client;
use App\Models\Role;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/* La clase implementa un Interface de JWTSubject */

class User extends Authenticatable
{
    use Notifiable;
    // Metodo para validar un rol permitido
    public function verifyRole($role)
    {
        foreach ($this->roles as $rol) {
            if ($rol->id == $role) {
                return true;
            }
        }
        return false;
    }
    // Relacion a muchos para el rol del usuario
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    // Relacion usuario cliente
    public function client()
    {
        return $this->hasOne(Client::class);
    }
    // funcion que pregunta si el rol esta autorizado para la web
    public function authorizeRoles($roles)
    {
        if ($this->hasAnyRole($roles)) {
            return true;
        }
        abort(401, 'This action is unauthorized');
    }
    // Relacion entre los clientes principales y el admin de ventas
    public function references()
    {
        return $this->belongsToMany(Client::class, 'sale_clients', 'sale_id');
    }
    /* funcion para buscar el rol del usuario */
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    /* funcion para saber si el nombre del rol existe */
    public function hasRole($role)
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_surname',
        'second_surname',
        'email',
        'sex',
        'phone',
        'birthday',
        'job',//Puesto de trabajo
        'active',
        'external_id',//Login google
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'password',
        'remember_token', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* Metodos override de JWTSubject */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role' => $this->roles[0]->name];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
