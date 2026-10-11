<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PrivacyPolicyController extends Controller
{
    /**
     * Display the foundation's official Privacy Policy page.
     */
    public function __invoke(): View
    {
        return view('privacy-policy');
    }
}
