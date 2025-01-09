<?php

namespace App\Http\Requests\ImageRequest;

use App\Http\Requests\ImageRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\PersonalAccessToken;

class IndexImageRequest extends ImageRequest
{
    protected function passesAuthorization()
    {
        if (!parent::passesAuthorization()) {
            return false;
        }

        return !!$this->attemptAuthorizationUsingToken($this->query('token', null));
    }

    private function attemptAuthorizationUsingToken(?string $token): Authenticatable|bool
    {
        if(auth()->check()) {
            return true;
        }

        if ($token === null) {
            return false;
        }

        [$userId, $token] = explode('|', $token);

        $databaseToken = hash('sha256', $token);
        $personalAccessToken = PersonalAccessToken::query()
            ->where('token', $databaseToken)
            ->first();

        if (!$personalAccessToken) {
            abort(401);
        }

        return auth()->loginUsingId($userId);
    }
}
