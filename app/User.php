<?php

namespace App;
use App\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public  function articles() {
        return $this->hasMany('App\Article');
    }

    public function roles() {
        return $this->belongsToMany('App\Role','role_user');
    }
    public  function canDo($permission, $require = false) {
        if (is_array($permission)) {
            foreach ($permission as $permItem) {
                $result = $this->canDo($permItem);
                if ($result && !$require) {
                    return true;
                } elseif (!$result && $require) {
                    return false;
                }
                return $require;
            }

        } else {
            foreach ($this->roles as $role) {
                foreach ($role->permissions as $perm) {
                    if (str_is($perm->name, $permission)) {
                        return true;
                    }
                }
            }
        }
    }
    public  function hasRole($name, $require = false) {
        if (is_array($name)) {
            foreach ($name as $roleItem) {
                $result = $this->hasRole($roleItem);
                if ($result && !$require) {
                    return true;
                } elseif (!$result && $require) {
                    return false;
                }
                return $require;
            }

        } else {
            foreach ($this->roles as $role) {

                    if ($role->name == $name) {
                        return true;
                    }

            }
        }
    }
}
