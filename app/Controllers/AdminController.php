<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class AdminController extends BaseController
{
    public function index()
    {
        // Proteksi 1: Harus login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Proteksi 2: Harus memiliki role 'admin'
        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Akses ditolak. Anda bukan Admin.');
            return redirect()->to('/dashboard');
        }

        $userModel = new UserModel();

        $data = [
            'title' => 'Admin Panel - Personal Finance',
            'users' => $userModel->findAll() // Ambil semua data pengguna
        ];

        return view('admin/index', $data);
    }
}
