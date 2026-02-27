<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $helpers = ['form'];
    
    public function login()
    {
        // If user already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function loginAuth()
    {
        $session = session();
        $userModel = new UserModel();

        // Get email and password from request
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // cari user berdasarkan email
        $data = $userModel->where('email', $email)->first();

        if ($data) {
            $pass = $data['password'];
            $verify_pass = password_verify($password, $pass);

            if ($verify_pass) {
                // set session data if password is correct
                $ses_data = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'role' => $data['role'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
                session()->setFlashdata('success', 'Login successful! Welcome back, ' . $data['name'] . '.');
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('msg', 'Wrong Password');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Email not found');
            return redirect()->to('/login');
        }
    }

    public function register()
    {
        return view('auth/register');
    }

    public function store()
    {
        // Validation rules for registration form
        $rules = [
            'name' => 'required|alpha_space|min_length[3]|max_length[50]',
            'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]|max_length[200]',
        ];

        if ($this->validate($rules)) {
            $userModel = new UserModel();
            $data = [
                'name' => $this->request->getVar('name'),
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'role' => 'user' // default role for new users
            ];
            $userModel->save($data);
            $userId = $userModel->getInsertID(); // Get the ID of the newly created user

            // Set session data for the new user
            $ses_data = [
                'id' => $userId,
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'isLoggedIn' => TRUE
            ];
            session()->set($ses_data);

            // Set flashdata success message and redirect to dashboard
            session()->setFlashdata('success', 'Account created successfully! Welcome.');
            return redirect()->to('/dashboard');
        } else {
            $data['validation'] = $this->validator;
            return view('auth/register', $data);  
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
