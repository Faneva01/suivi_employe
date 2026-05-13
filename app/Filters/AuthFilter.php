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

            // Admin can access any page (hierarchy: admin > rh > employe)
            if ($userRole === 'admin') {
                return; // Allow access
            }

            // RH can access RH and employe routes
            if ($userRole === 'rh' && ($requiredRole === 'rh' || $requiredRole === 'employe')) {
                return; // Allow access
            }

            // Strict check for exact role match
            if ($userRole !== $requiredRole) {
                return redirect()->to('/login')->with('error', 'Accès non autorisé.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
