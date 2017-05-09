<?php

namespace Laralum\Social\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laralum\Social\Models\Settings;

class SocialController extends Controller
{
    private $settings;

    public function __construct()
    {
        $this->settings = Settings::first();

        if (!$this->settings->enabled) {
            abort(404, __('laralum_social::general.social_disabled'))
        }
    }
    /**
     * Redirect the user to the provider authentication page.
     *
     * @return Response
     */
    public function provider($provider)
    {
        $ci = $provider . "_client_id";
        $cs = $provider . "_client_secret";

        if (!$this->settings->$ci || !$this->settings->$cs) {
            abort(404, __('laralum_social::general.provider_not_found', ['provider' => $provider]));
        }

        config(['services.' . $provider => [
            'client_id' => $this->settings->$ci,
            'client_secret' => $this->settings->$cs,
            'redirect' => route('laralum_public::social.callback', ['provider' => $provider]),
        ]])

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @return Response
     */
    public function(Request $request, $provider)
    {
        $user = Socialite::driver('github')->user();

        dd($user);
    }
}
