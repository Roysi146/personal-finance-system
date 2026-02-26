<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TransactionModel;

class TransactionController extends BaseController
{
    protected $helpers = ['form'];
    
    // Show form to create a new transaction
    public function create()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/login');

        $data = [
            'title' => 'Add Transaction - Personal Finance System',
            'name' => session()->get('name')
        ];

        return view('transaction/create', $data);
    }

    //Process form submission to store a new transaction into the database
    public function store()
    {        if (!session()->get('isLoggedIn')) return redirect()->to('/login');

        $rules = [
            'type' => 'required|in_list[income,expense]',
            'amount' => 'required|numeric|greater_than[0]',
            'date' => 'required|valid_date',
            'description' => 'required'
        ];

        if ($this->validate($rules)) {
            $transactionModel = new TransactionModel();

            $data = [
                'user_id' => session()->get('id'), // Take user_id from session
                'type' => $this->request->getVar('type'),
                'amount' => $this->request->getVar('amount'),
                'date' => $this->request->getVar('date'),
                'description' => $this->request->getVar('description')
            ];

            $transactionModel->save($data);

            session()->setFlashdata('success', 'Transaction added successfully');
            return redirect()->to('/dashboard');
        } else {
            $data['validation'] = $this->validator;
            $data['title'] = 'Add Transaction - Personal Finance System';
            return view('transaction/create', $data);
        }
    }

    public function delete($id)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/login');

        $transactionModel = new TransactionModel();

        // Find the transaction by ID
        $transaction = $transactionModel->find($id);

        // Ensure the transaction belongs to the logged-in user
        if ($transaction && $transaction['user_id'] == session()->get('id')) {
            $transactionModel->delete($id);
            session()->setFlashdata('success', 'Transaction deleted successfully');
        } else {
            session()->setFlashdata('error', 'Transaction not found or unauthorized');
        }

        return redirect()->to('/dashboard');
    }

    // Show form to edit an existing transaction
    public function edit($id)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/login');

        $transactionModel = new TransactionModel();
        $transaction = $transactionModel->find($id);

        // Check if transaction exists and belongs to the logged-in user
        if (!$transaction || $transaction['user_id'] != session()->get('id')) {
            session()->setFlashdata('error', 'Akses ditolak.');
            return redirect()->to('/dashboard');
        }

        $data = [
            'title'       => 'Edit Transaksi - Personal Finance',
            'transaction' => $transaction
        ];

        return view('transaction/edit', $data);
    }

    // Process form submission to update an existing transaction in the database
    public function update($id)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to('/login');

        $rules = [
            'type'        => 'required|in_list[income,expense]',
            'amount'      => 'required|numeric|greater_than[0]',
            'date'        => 'required|valid_date',
            'description' => 'required'
        ];

        if ($this->validate($rules)) {
            $transactionModel = new TransactionModel();
            
            // Check if transaction exists and belongs to the logged-in user
            $transaction = $transactionModel->find($id);
            if (!$transaction || $transaction['user_id'] != session()->get('id')) {
                return redirect()->to('/dashboard');
            }

            $data = [
                'type'        => $this->request->getVar('type'),
                'amount'      => $this->request->getVar('amount'),
                'date'        => $this->request->getVar('date'),
                'description' => $this->request->getVar('description')
            ];
            
            $transactionModel->update($id, $data);
            
            session()->setFlashdata('success', 'Transaksi berhasil diperbarui!');
            return redirect()->to('/dashboard');
        } else {
            // If validation fails, return to the edit form with validation errors
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
    }
}
