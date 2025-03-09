<?php

namespace App\Modules\Register\Controllers;

use App\Core\Controller;

class RegisterController extends Controller
{
    public function index(): string
    {
        return $this->viewWithLayout('index', 'layout', [
            'title' => 'Inscription - Gitopedia',
        ]);
    }

    public function register(): string
    {
        $email = $this->request->post('email');
        $password_hash = password_hash($this->request->post('password'), PASSWORD_DEFAULT);

        return '';
    }

    protected function IsValidCredentials($email, $password_hash): bool
    {
        $credentials = array($email, $password_hash);

        foreach ($credentials as $credential) 
        {
            if (!isset($credential) || empty($credential))
                return false;

            $credential_len = strlen($credential);
            if ($credential_len < 6 || $credential_len > 64)
                return false;
        }

        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        if (!preg_match($pattern, $email))
            return false;

        return true;
    }
}