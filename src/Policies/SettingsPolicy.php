<?php

namespace Laralum\Social\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Laralum\Users\Models\User;

class SettingsPolicy
{
    use HandlesAuthorization;

    /**
     * Filters the authoritzation.
     *
     * @param mixed $user
     * @param mixed $ability
     */
    public function before($user, $ability)
    {
        if (User::findOrFail($user->id)->superAdmin()) {
            return true;
        }
    }

    /**
     * The mandatory permissions for the module.
     *
     * @var array
     */
    protected $permissions = [
        [
            'name' => 'Update Social Settings',
            'slug' => 'laralum::social.settings',
            'desc' => 'Allows updating the social settings',
        ],
    ];

    /**
     * Determine if the current user can update the general settings.
     *
     * @param mixed $user
     *
     * @return bool
     */
    public function update($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::social.settings');
    }
}
