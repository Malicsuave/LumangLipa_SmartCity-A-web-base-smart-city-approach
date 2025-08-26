<?php

namespace App\Providers;

use App\Actions\Auth\AuthenticateUser;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Http\Controllers\TwoFactorAuthenticationController; // Import the custom controller
use Illuminate\Support\ServiceProvider; // Add this line

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Default Fortify actions
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Custom authentication logic with account locking and activity tracking
        Fortify::authenticateUsing(function (Request $request) {
            return (new AuthenticateUser())->authenticate($request);
        });

        // Custom view for Two-Factor Authentication challenge
        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge'); // Replace with your custom 2FA Blade file
        });

        // Rate limiting for login
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        // Rate limiting for Two-Factor Authentication
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Override the 2FA routes to use a custom controller with route names
        Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
            ->name('two-factor.enable') // Name the route for enabling 2FA
            ->middleware(['auth']);

        Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
            ->name('two-factor.disable') // Name the route for disabling 2FA
            ->middleware(['auth']);

        // Route::post('/user/two-factor-recovery-codes', [TwoFactorAuthenticationController::class, 'recoveryCodes'])
        //     ->name('two-factor.recovery-codes') // Name the route for regenerating recovery codes
        //     ->middleware(['auth']);
    }
}