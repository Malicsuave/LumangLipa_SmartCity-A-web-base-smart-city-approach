<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use App\Models\UserActivity;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => [
                'required',
                'string',
                new StrongPassword(),
                'confirmed',
                'different:current_password' // Ensure new password is different from current
            ],
        ], [
            'current_password.current_password' => __('The provided password does not match your current password.'),
            'password.different' => 'The new password must be different from your current password.',
        ])->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
            'password_changed_at' => now(),
            'force_password_change' => false, // Clear forced password change flag
        ])->save();
        
        // Log the password change
        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'password_changed',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => [
                'change_method' => 'user_initiated',
                'forced_change' => $input['forced_change'] ?? false,
            ],
        ]);
    }
}
