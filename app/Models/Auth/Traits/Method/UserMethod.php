<?php

namespace App\Models\Auth\Traits\Method;

/**
 * Trait UserMethod.
 */
trait UserMethod
{
    /**
     * @return mixed
     */
    public function canChangeEmail()
    {
        return config('access.users.change_email');
    }

    /**
     * @return bool
     */
    public function canChangePassword()
    {
        return ! app('session')->has(config('access.socialite_session_name'));
    }

    /**
     * @param bool $size
     *
     * @throws \Illuminate\Container\EntryNotFoundException
     * @return bool|\Illuminate\Contracts\Routing\UrlGenerator|mixed|string
     */
    public function getPicture($size = false)
    {
        switch ($this->avatar_type) {
            case 'gravatar':
                if (! $size) {
                    $size = config('gravatar.default.size');
                }

                return gravatar()->get($this->email, ['size' => $size]);

            case 'storage':
                return url('storage/'.$this->avatar_location);
        }

        $social_avatar = $this->providers()->where('provider', $this->avatar_type)->first();

        if ($social_avatar && strlen($social_avatar->avatar)) {
            return $social_avatar->avatar;
        }

        return false;
    }

    /**
     * @param $provider
     *
     * @return bool
     */
    public function hasProvider($provider)
    {
        foreach ($this->providers as $p) {
            if ($p->provider == $provider) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->hasRole(config('access.users.admin_role'));
    }

    /**
     * @return mixed
     */
    public function isJuniorVIP()
    {
        return $this->hasRole(config('access.users.junior_vip_role'));
    }

    /**
     * @return mixed
     */
    public function isSeniorVIP()
    {
        return $this->hasRole(config('access.users.senior_vip_role'));
    }

    /**
     * @return mixed
     */
    public function isExpertVIP()
    {
        return $this->hasRole(config('access.users.expert_vip_role'));
    }

    /**
     * @return mixed
     */
    public function isLegendVIP()
    {
        return $this->hasRole(config('access.users.legend_vip_role'));
    }

    /**
     * @return mixed
     */
    public function isJuniorDonate()
    {
        return $this->hasRole(config('access.users.junior_donate_role'));
    }

    /**
     * @return mixed
     */
    public function isSeniorDonate()
    {
        return $this->hasRole(config('access.users.senior_donate_role'));
    }

    /**
     * @return mixed
     */
    public function isExpertDonate()
    {
        return $this->hasRole(config('access.users.expert_donate_role'));
    }

    /**
     * @return mixed
     */
    public function isLegendDonate()
    {
        return $this->hasRole(config('access.users.legend_donate_role'));
    }

    /**
     * @return mixed
     */
    public function isJuniorUser()
    {
        return $this->hasRole(config('access.users.junior_user_role'));
    }

    /**
     * @return mixed
     */
    public function isSeniorUser()
    {
        return $this->hasRole(config('access.users.senior_user_role'));
    }

    /**
     * @return mixed
     */
    public function isExpertUser()
    {
        return $this->hasRole(config('access.users.expert_user_role'));
    }

    /**
     * @return mixed
     */
    public function isLegendUser()
    {
        return $this->hasRole(config('access.users.legend_user_role'));
    }

    /**
     * @return mixed
     */
    public function isJuniorManager()
    {
        return $this->hasRole(config('access.users.junior_manager_role'));
    }

    /**
     * @return mixed
     */
    public function isSeniorManager()
    {
        return $this->hasRole(config('access.users.senior_manager_role'));
    }

    /**
     * @return mixed
     */
    public function isExpertManager()
    {
        return $this->hasRole(config('access.users.expert_manager_role'));
    }

    /**
     * @return mixed
     */
    public function isLegendManager()
    {
        return $this->hasRole(config('access.users.legend_manager_role'));
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return config('access.users.requires_approval') && ! $this->confirmed;
    }
}
