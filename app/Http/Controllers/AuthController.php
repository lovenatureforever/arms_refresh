<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function logout(Request $request): RedirectResponse
    {
        // Capture the referer URL before logout to get the tenant domain
        $referer = $request->headers->get('referer');
        $redirectUrl = '/';

        if ($referer) {
            $parsedUrl = parse_url($referer);
            $scheme = $parsedUrl['scheme'] ?? 'http';
            $host = $parsedUrl['host'] ?? 'localhost';
            $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
            $redirectUrl = $scheme . '://' . $host . $port . '/';
        }

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->to($redirectUrl);
    }
}
