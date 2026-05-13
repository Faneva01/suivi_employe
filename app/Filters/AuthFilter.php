<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if ($arguments) {
            $requiredRole = $arguments[0];
            $userRole = session()->get('role');

            if ($userRole !== $requiredRole) {
                // If it's an admin trying to access RH/Employe, maybe allow?
                // But for the TP, let's stick to strict roles or hierarchy.
                // Usually Admin > RH > Employe.
                
                if ($requiredRole === 'employe') {
                    // Everyone can access employe space? No, usually specific.
                }

                if ($userRole !== 'admin' && $userRole !== $requiredRole) {
                     return redirect()->to('/login')->with('error', 'Accès non autorisé.');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
