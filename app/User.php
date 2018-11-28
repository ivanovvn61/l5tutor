<?php

namespace Corp;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function articles()
    {
        return $this->hasMany('Corp\Article');
    }

    public function roles()
    {
        return $this->belongsToMany('Corp\Role', 'role_user');
    }

    //  'string'  array('View_Admin','ADD_ARTICLES')
    //
    public function canDo($permission, $require = FALSE)
    {
        if (is_array($permission)) {
            foreach ($permission as $item) {
                $item = $this->canDo($item);
                if ($item && !$require) {
                    return TRUE;
                } else if (!$item && $require) {
                    return FALSE;
                }
            }
            return $require;
        } else {
            foreach ($this->roles as $role) {
                foreach ($role->perms as $perm) {
                    if (str_is($permission, $perm->name)) {
                        return TRUE;
                    }
                }
            }
        }
    }

    // string  ['role1', 'role2']
    public function hasRole($nameRole, $require = false)
    {
        if (is_array($nameRole)) {
            foreach ($nameRole as $item) {
                $hasRole = $this->hasRole($item);
                if ($hasRole && !$require) {
                    return true;
                } elseif (!$hasRole && $require) {
                    return false;
                }
            }
            return $require;
        } else {
            foreach ($this->roles as $role) {
                if ($role->name == $nameRole) {
                    return true;
                }
            }
        }
        return false;
    }
}