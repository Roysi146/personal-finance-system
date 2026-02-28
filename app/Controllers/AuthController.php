<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class AuthController extends BaseController
{
    // Load form helper untuk validasi form
    protected $helpers = ['form'];
    
    // Method untuk menampilkan form login
    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    // Method untuk memproses login
    public function loginAuth()
    {
        $session = session();
        $userModel = new UserModel();

        // Ambil data email dan password dari form login
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // cari user berdasarkan email
        $data = $userModel->where('email', $email)->first();

        if ($data) {
            $pass = $data['password'];
            $verify_pass = password_verify($password, $pass);

            if ($verify_pass) {
                // Set session data untuk user yang berhasil login
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

    // Method untuk menampilkan form registrasi
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/register');
    }

    // Method untuk menyimpan data registrasi user baru
    public function store()
    {
        // Validasi input data registrasi
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
                'role' => 'user' // Set default role sebagai 'user' untuk semua registrasi baru
            ];
            $userModel->save($data);
            $userId = $userModel->getInsertID(); // Mendapatkan ID user yang baru dibuat

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

    // Method untuk logout user
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }

    // Method untuk menampilkan form forgot password
    public function forgot()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/forgot');
    }

    // Method untuk mengirim link reset password
    public function sendResetLink()
    {
        $email = $this->request->getPost('email');
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // Security measure: Jangan beri tahu pengguna apakah email terdaftar atau tidak (untuk mencegah enumerasi akun)
        // Namun, untuk tujuan pengembangan, kita beritahu saja agar mudah dites
        if (!$user) {
            session()->setFlashdata('error', 'Email are not registered in our system.');
            return redirect()->back();
        }

        // Generate reset token 64 karakter
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token berlaku selama 1 jam

        // Simpan token dan waktu kedaluwarsa ke database
        $userModel->update($user['id'], [
            'reset_token' => $token,
            'reset_expires_at' => $expires
        ]);

        // Link reset
        $resetLink = base_url('/reset/' . $token);

        // Proses Kirim Email
        $emailService = \config\services::email();
        $emailService->setTo($email);
        $emailService->setSubject('Reset Password - Finance Manager');

        // Isi pesan Email (HTML)
        $message = "
            <h2>Reset Your Password</h2>
            <p>Hello <b>{$user['name']}</b>,</p>
            <p>We received a request to reset your password. Click the link below to set a new password:</p>
            <p><a href='{$resetLink}' style='display:inline-block;padding:10px 20px;background-color:#007bff;color:#fff;text-decoration:none;border-radius:5px;'>Reset Password</a></p>
            <p>If you did not request a password reset, please ignore this email.</p>
            <p>This link will expire in 1 hour.</p>
        ";

        $emailService->setMessage($message);

        if ($emailService->send()) {
            session()->setFlashdata('success', 'A password reset link has been sent to your inbox or your spam folder.');
            return redirect()->to('/login');
        } else {
            // Log error email jika gagal mengirim dari google
            $data = $emailService->printDebugger(['headers']);
            session()->setFlashdata('error', 'Failed to send reset link. Please try again later.');
            // Log error detail untuk debugging
            // print_r($data);
            return redirect()->back();
        }
    }

    public function reset($token = null)
    {
        $userModel = new UserModel();

        // Cari user berdasarkan reset token
        $user = $userModel->where('reset_token', $token)->first();

        // Validasi token: pastikan token valid dan belum expired
        if (!$user || strtotime($user['reset_expires_at']) < time()) {
            session()->setFlashdata('error', 'Invalid or expired reset token.');
            return redirect()->to('/forgot');
        }

        $data = [
            'token' => $token
        ];

        return view('auth/reset', $data);
    }

    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)->first();

        // Validasi keamanan ulang (berjaga-jaga jika user membiarkan tab terbuka terlalu lama)
        if (!$user || strtotime($user['reset_expires_at']) < time()) {
            session()->setFlashdata('error', 'Invalid or expired reset token.');
            return redirect()->to('/forgot');
        }

        // Validasi password baru (minimal 6 karakter)
        if (strlen($password) < 6) {
            session()->setFlashdata('error', 'Password must be at least 6 characters long.');
            return redirect()->back();
        }

        $userModel->update($user['id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_expires_at' => null
        ]);
        session()->setFlashdata('success', 'Password has been reset successfully.');
        return redirect()->to('/login');
    }
}
