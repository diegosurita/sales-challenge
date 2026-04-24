<?php

namespace Module\Auth\Interface\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Auth\Core\UseCases\AuthenticateUserUseCase;

class AuthenticationController extends Controller
{
    public function login(
        Request $request,
        AuthenticateUserUseCase $authenticateUserUseCase,
    ): RedirectResponse {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $isAuthenticated = $authenticateUserUseCase->execute(
            $validated['email'],
            $validated['password'],
        );

        if (!$isAuthenticated) {
            return back()->withErrors([
                'failed' => __('auth.failed'),
            ])->onlyInput('failed');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
