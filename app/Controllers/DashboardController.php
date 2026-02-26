<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TransactionModel;

class DashboardController extends BaseController
{
    public function index()
    {
        // Check if user is logged in, if not redirect to login page
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $transactionModel = new TransactionModel();
        $userId = session()->get('id');

        // Fetch transactions for the logged-in user, ordered by date and id
        $transactions = $transactionModel->where('user_id', $userId)->orderBy('date', 'DESC')->orderBy('id', 'DESC')->findAll();

        // Calculate total income and total expense
        $totalIncome = 0;
        $totalExpense = 0;

        foreach ($transactions as $t) {
            if ($t['type'] === 'income') {
                $totalIncome += $t['amount'];
            } else {
                $totalExpense += $t['amount'];
            }
        }

        $balance = $totalIncome - $totalExpense;

        // Pass user data to the dashboard view
        $data = [
            'title' => 'Dashboard - Personal Finance System',
            'name' => session()->get('name'),
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance
        ];

        return view('dashboard/index', $data);
    }
}
