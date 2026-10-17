<?php

namespace App\Services;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MfaChallenge
{
    const LIFETIME_MINUTES = 5;
    const MAX_ATTEMPTS = 5;

    public function issue(User $user, $scope, Request $request)
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $request->session()->put($this->sessionKey($scope), [
            'user_id' => (int) $user->id,
            'code_hash' => Hash::make($code),
            'expires_at' => time() + (self::LIFETIME_MINUTES * 60),
            'attempts' => 0,
        ]);

        return $code;
    }

    public function pendingUserId($scope, Request $request)
    {
        $challenge = $request->session()->get($this->sessionKey($scope));

        if (! is_array($challenge) || ! isset($challenge['user_id'], $challenge['expires_at'])) {
            return null;
        }

        if ((int) $challenge['expires_at'] <= time()) {
            $this->clear($scope, $request);

            return null;
        }

        return (int) $challenge['user_id'];
    }

    public function verify($scope, $userId, $code, Request $request)
    {
        if ($this->pendingUserId($scope, $request) !== (int) $userId) {
            return false;
        }

        $key = $this->sessionKey($scope);
        $challenge = $request->session()->get($key);

        if (! isset($challenge['code_hash']) || ! is_string($challenge['code_hash'])) {
            $this->clear($scope, $request);

            return false;
        }

        $attempts = isset($challenge['attempts']) ? (int) $challenge['attempts'] : 0;
        if ($attempts >= self::MAX_ATTEMPTS) {
            $this->clear($scope, $request);

            return false;
        }

        $matches = is_string($code)
            && preg_match('/^\d{6}$/D', $code)
            && Hash::check($code, $challenge['code_hash']);

        if (! $matches) {
            $challenge['attempts'] = $attempts + 1;

            if ($challenge['attempts'] >= self::MAX_ATTEMPTS) {
                $this->clear($scope, $request);
            } else {
                $request->session()->put($key, $challenge);
            }

            return false;
        }

        $this->clear($scope, $request);

        return true;
    }

    public function clear($scope, Request $request)
    {
        $request->session()->forget($this->sessionKey($scope));
    }

    private function sessionKey($scope)
    {
        return 'mfa_challenge.' . $scope;
    }
}
