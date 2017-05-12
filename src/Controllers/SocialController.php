<?php

namespace Laralum\Social\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Laralum\Social\Models\Settings;
use Laralum\Social\Models\Social;
use Laralum\Users\Models\User;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    /**
     * Stores the social settings.
     *
     * @var \Laralum\Social\Models\Social
     */
    private $settings;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->settings = Settings::first();
    }

    /**
     * Redirect the user to the provider authentication page.
     *
     * @return Response
     */
    public function provider($provider)
    {
        $this->checkDisabled();

        $this->checkProvider($provider);

        $this->setConfig($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @return Response
     */
    public function callback(Request $request, $provider)
    {
        $this->checkDisabled();

        $this->checkProvider($provider);

        $this->setConfig($provider);

        $user = User::findOrFail(Auth::id());
        $redirectAfter = $user->hasPermission('laralum::access') || $user->superAdmin() ? route('laralum::social.integrations') : url('/');

        $user = Socialite::driver($provider)->user();

        if (Auth::check()) {
            if (!Social::where(['user_id' => Auth::id(), 'provider' => $provider])->first()) {
                $this->registerSocial($provider, $user);

                return redirect($redirectAfter)->with('success', __('laralum_social::general.provider_linked', ['provider' => $provider]));
            }

            return redirect($redirectAfter)->with('error', __('laralum_social::general.provider_already_linked', ['provider' => $provider]));
        }

        if ($user->getEmail() && $user->getName()) {
            $dbuser = User::where('email', $user->getEmail())->first();

            if ($dbuser && Social::where(['user_id' => $dbuser->id, 'provider' => $provider])->first()) {
                // User found and social already set - Force login

                Auth::login($dbuser);

                return redirect($redirectAfter)->with('success', __('laralum_social::general.logged_in', ['provider' => $Provider]));
            } elseif ($dbuser) {
                // User found but no social set - Setup the social & Force Login
                $this->registerSocial($provider, $user, $dbuser);

                Auth::login($dbuser);

                return redirect($redirectAfter)->with('success', __('laralum_social::general.logged_in', ['provider' => $Provider]));
            } elseif (!$dbuser && $this->settings->allow_register) {
                // No user found and registers enabled - Register user, social & login
                /*
                $dbuser = new User;
                $dbuser->name = $user->getName();
                $dbuser->email = $user->getEmail();
                $dbuser->save();

                $this->registerSocial($provider, $user, $dbuser);

                Auth::login($dbuser);
                */

                abort(403, 'Feature not yet done');

                return redirect($redirectAfter)->with('success', __('laralum_social::general.logged_in', ['provider' => $Provider]));
            }

            abort(403, 'Registrations are not allowed');
        }

        abort(403, 'The provider did not return a valid email or name to register the user');
    }

    public function integrations()
    {
        $providers = Social::availableProviders();

        return view('laralum_social::integrations', ['providers' => $providers]);
    }

    /**
     * Unlink the social account.
     *
     * @param string $provider
     *
     * @return Response
     */
    public function unlink($provider)
    {
        $this->checkDisabled();

        $user = User::findOrFail(Auth::id());
        $redirectAfter = $user->hasPermission('laralum::access') || $user->superAdmin() ? route('laralum::social.integrations') : url('/');

        $link = Social::where(['user_id' => Auth::id(), 'provider' => $provider])->first();

        if (!$link) {
            return redirect($redirectAfter)->with('success', __('laralum_social::general.link_not_found', ['provider' => $provider]));
        }

        $link->delete();

        return redirect($redirectAfter)->with('success', __('laralum_social::general.unlinked', ['provider' => $provider]));
    }

    /**
     * Update the social settings.
     *
     * @return Response
     */
    public function settings(Request $request)
    {
        $this->authorize('update', Settings::class);

        $this->settings->update([
            'enabled'                 => $request->enabled ? true : false,
            'allow_register'          => $request->allow_register ? false : false, // Not enabled yet
            'facebook_client_id'      => $request->facebook_client_id ? encrypt($request->facebook_client_id) : null,
            'facebook_client_secret'  => $request->facebook_client_secret ? encrypt($request->facebook_client_secret) : null,
            'twitter_client_id'       => $request->twitter_client_id ? encrypt($request->twitter_client_id) : null,
            'twitter_client_secret'   => $request->twitter_client_secret ? encrypt($request->twitter_client_secret) : null,
            'linkedin_client_id'      => $request->linkedin_client_id ? encrypt($request->linkedin_client_id) : null,
            'linkedin_client_secret'  => $request->linkedin_client_secret ? encrypt($request->linkedin_client_secret) : null,
            'google_client_id'        => $request->google_client_id ? encrypt($request->google_client_id) : null,
            'google_client_secret'    => $request->google_client_secret ? encrypt($request->google_client_secret) : null,
            'github_client_id'        => $request->github_client_id ? encrypt($request->github_client_id) : null,
            'github_client_secret'    => $request->github_client_secret ? encrypt($request->github_client_secret) : null,
            'bitbucket_client_id'     => $request->bitbucket_client_id ? encrypt($request->bitbucket_client_id) : null,
            'bitbucket_client_secret' => $request->bitbucket_client_secret ? encrypt($request->bitbucket_client_secret) : null,
        ]);

        $this->settings->touch();

        return redirect()->route('laralum::settings.index', ['p' => 'Social'])->with('success', __('laralum_social::general.updated_settings'));
    }

    /**
     * Check if the social feature is disabled.
     */
    private function checkDisabled()
    {
        if (!$this->settings->enabled) {
            abort(404, __('laralum_social::general.social_disabled'));
        }
    }

    /**
     * Check if the provider is enabled or not.
     *
     * @param string $provider
     */
    private function checkProvider($provider)
    {
        $ci = $provider.'_client_id';
        $cs = $provider.'_client_secret';

        if (!$this->settings->$ci || !$this->settings->$cs) {
            abort(404, __('laralum_social::general.provider_not_found', ['provider' => $provider]));
        }
    }

    /**
     * Set the configuration to run socialite with a defined provider.
     *
     * @param string $provider
     */
    private function setConfig($provider)
    {
        $ci = $provider.'_client_id';
        $cs = $provider.'_client_secret';

        config(['services.'.$provider => [
            'client_id'     => decrypt($this->settings->$ci),
            'client_secret' => decrypt($this->settings->$cs),
            'redirect'      => route('laralum_public::social.callback', ['provider' => $provider]),
        ]]);
    }

    /**
     * Registers the social account.
     *
     * @param string                     $provider
     * @param mixed                      $user
     * @param \Laralum\Users\Models\User $dbuser
     *
     * @return bool
     */
    private function registerSocial($provider, $user, $dbuser = null)
    {
        return Social::create([
            'user_id'      => $dbuser ? $dbuser->id : Auth::user()->id,
            'provider'     => $provider,
            'token'        => $user->token,
            'secret_token' => isset($user->tokenSecret) ? $user->tokenSecret : null,
        ]);
    }
}
