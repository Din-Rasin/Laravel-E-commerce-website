<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        // Validate provider
        if (!in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login')
                ->with('error', 'Invalid social login provider.');
        }

        // Log the provider and redirect URL for debugging
        \Log::info('Social login redirect', [
            'provider' => $provider,
            'redirect' => config("services.{$provider}.redirect"),
            'client_id' => config("services.{$provider}.client_id"),
            // Don't log the secret in production
            'client_secret_set' => !empty(config("services.{$provider}.client_secret")),
        ]);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        // Validate provider
        if (!in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login')
                ->with('error', 'Invalid social login provider.');
        }

        try {
            // Log the provider and redirect URL for debugging
            \Log::info('Social login callback', [
                'provider' => $provider,
                'redirect' => config("services.{$provider}.redirect"),
                'client_id' => config("services.{$provider}.client_id"),
                // Don't log the secret in production
                'client_secret_set' => !empty(config("services.{$provider}.client_secret")),
            ]);

            // Get user data from provider
            $socialUser = Socialite::driver($provider)->user();

            // Find existing user or create new one
            $user = User::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            if (!$user) {
                // Check if user with same email exists
                $user = User::where('email', $socialUser->getEmail())->first();

                if (!$user) {
                    // Create new user
                    $user = User::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'password' => null, // No password for social login
                        'role' => 'user',
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                } else {
                    // Update existing user with provider data
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                }
            }

            // Login the user
            Auth::login($user, true);

            // Redirect based on user role
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/user/dashboard');
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('Social login state error', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('login')
                ->with('error', 'Authentication session expired. Please try again.');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            \Log::error('Social login client error', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);

            $errorMessage = 'Authentication failed with the provider. Please check your credentials.';

            // Check for specific error messages in the response
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
                $errorMessage = 'Invalid OAuth client credentials. Please contact the administrator.';
            }

            return redirect()->route('login')->with('error', $errorMessage);
        } catch (\Exception $e) {
            \Log::error('Social login error', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('login')
                ->with('error', 'An error occurred during social login. Please try again later.');
        }
    }
}
