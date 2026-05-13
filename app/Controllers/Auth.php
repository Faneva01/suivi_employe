<?php

namespace App\Controllers;

use App\Models\EmployeModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectUser(session()->get('role'));
        }
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new EmployeModel();
        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['actif'] == 0) {
                return redirect()->back()->with('error', 'Votre compte est désactivé.');
            }

            $this->setUserSession($user);
            return $this->redirectUser($user['role']);
        }

        return redirect()->back()->with('error', 'Email ou mot de passe incorrect.');
    }

    private function setUserSession($user)
    {
        $data = [
            'id'          => $user['id'],
            'nom'         => $user['nom'],
            'prenom'      => $user['prenom'],
            'email'       => $user['email'],
            'role'        => $user['role'],
            'isLoggedIn'  => true,
        ];

        session()->set($data);
        return true;
    }

    private function redirectUser($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->to('/admin');
            case 'rh':
                return redirect()->to('/rh');
            default:
                return redirect()->to('/employe');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
