<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Profile;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/');
        }

        // Get all profile IDs for the current user
        $profileIds = $user->profiles->pluck('id')->toArray();

        // Retrieve transactions for these profiles
        $transactions = Transaction::whereIn('profile_id', $profileIds)
            ->with(['profile', 'category'])
            ->paginate(10);

        // Retrieve only categories belonging to the logged-in user
        $categories = Category::where('user_id', $user->id)->get();

        // Retrieve only profiles belonging to the logged-in user
        $profiles = $user->profiles;

        // Compute Income Chart Data
        $incomeDataQuery = Transaction::selectRaw('category_id, SUM(amount) as total')
            ->whereIn('profile_id', $profileIds)
            ->where('type', 'income')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $incomeCategories = $incomeDataQuery->pluck('category.name');
        $incomeData = $incomeDataQuery->pluck('total');

        // Compute Expense Chart Data
        $expenseDataQuery = Transaction::selectRaw('category_id, SUM(amount) as total')
            ->whereIn('profile_id', $profileIds)
            ->where('type', 'expense')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $expenseCategories = $expenseDataQuery->pluck('category.name');
        $expenseData = $expenseDataQuery->pluck('total');
        

        return view('dashboard', compact(
            'transactions', 
            'categories',
            'profiles', 
            'incomeCategories', 
            'incomeData',
            'expenseCategories', 
            'expenseData'
        ));
    }

    public function create()
    {
        // Need categories to populate a dropdown
        $categories = Category::all();
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $profileId = session('selected_profile_id');

        $request->validate([
            'date'             => 'required|date',
            'amount'           => 'required|numeric',
            'description'      => 'nullable|string',
            'category_id'      => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
        ]);

        Transaction::create([
            'profile_id'       => $profileId,
            'date'             => $request->date,
            'amount'           => $request->amount,
            'description'      => $request->description,
            'category_id'      => $request->category_id,
            'type' => $request->type,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction created!');
    }

    public function edit(Transaction $transaction)
    {
        // Ensure the transaction belongs to the selected profile
        $this->authorizeTransaction($transaction);

        $categories = Category::all();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $request->validate([
            'date'             => 'required|date',
            'amount'           => 'required|numeric',
            'description'      => 'nullable|string',
            'category_id'      => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
        ]);

        $transaction->update($request->only([
            'date', 'amount', 'description', 'category_id', 'type'
        ]));

        return redirect()->route('transactions.index')->with('success', 'Transaction updated!');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted!');
    }

    private function authorizeTransaction(Transaction $transaction)
    {
        if ($transaction->profile_id !== session('selected_profile_id')) {
            abort(403, 'Unauthorized');
        }
    }

    public function chart()
    {
        $profileId = session('selected_profile_id');

        // Sum of amounts grouped by category for EXPENSE transactions
        $data = Transaction::selectRaw('category_id, SUM(amount) as total')
            ->where('profile_id', $profileId)
            ->where('type', 'expense')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $labels = $data->pluck('category.name');
        $totals = $data->pluck('total');

        return view('transactions.chart', compact('labels', 'totals'));
    }

}
