<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    public function getBudgetData()
    {
        // Get the authenticated user
        $user = Auth::user();
        if (!$user) {
            return redirect('/');
        }
        
        // Get all profile IDs for the current user
        $profileIds = $user->profiles->pluck('id')->toArray();
        
        // Calculate total income across all user profiles
        $totalIncome = Transaction::whereIn('profile_id', $profileIds)
                                   ->where('type', 'income')
                                   ->sum('amount');
        
        // Apply the 50/30/20 rule
        $budgetDistribution = [
            'needs' => $totalIncome * 0.50,
            'wants' => $totalIncome * 0.30,
            'savings' => $totalIncome * 0.20,
        ];
        
        // Get income and expense categories for reference
        $incomeCategories = Category::where('type', 'income')
                                   ->whereHas('transactions', function($query) use ($profileIds) {
                                       $query->whereIn('profile_id', $profileIds);
                                   })
                                   ->get();
        
        $expenseCategories = Category::where('type', 'expense')
                                    ->whereHas('transactions', function($query) use ($profileIds) {
                                        $query->whereIn('profile_id', $profileIds);
                                    })
                                    ->get();
        
        // Get current month and year
        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');
        
        // Calculate expenses by category for current month
        $expensesByCategory = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->whereIn('transactions.profile_id', $profileIds)
            ->where('transactions.type', 'expense')
            ->whereMonth('transactions.date', $currentMonth)
            ->whereYear('transactions.date', $currentYear)
            ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('categories.name')
            ->get()
            ->pluck('total', 'name')
            ->toArray();
        
        // Calculate total spent this month
        $totalSpent = array_sum($expensesByCategory);
        
        // Get total income this month
        $totalMonthlyIncome = DB::table('transactions')
            ->whereIn('profile_id', $profileIds)
            ->where('type', 'income')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');
        
        // Calculate budget allocations for this month
        $monthlyBudget = [
            'needs' => $totalMonthlyIncome * 0.50,
            'wants' => $totalMonthlyIncome * 0.30,
            'savings' => $totalMonthlyIncome * 0.20,
        ];
        
        // Calculate remaining budget
        $remainingBudget = $totalMonthlyIncome - $totalSpent;
        
        // Calculate spending breakdown by month for the last 6 months
        $lastSixMonths = collect([]);
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $lastSixMonths->push([
                'month' => $month->format('M Y'),
                'month_num' => $month->format('m'),
                'year_num' => $month->format('Y')
            ]);
        }
        
        $monthlySpending = [];
        foreach ($lastSixMonths as $month) {
            $spending = DB::table('transactions')
                ->whereIn('profile_id', $profileIds)
                ->where('type', 'expense')
                ->whereMonth('date', $month['month_num'])
                ->whereYear('date', $month['year_num'])
                ->sum('amount');
            
            $monthlySpending[$month['month']] = $spending;
        }
        
        return compact(
            'budgetDistribution', 
            'totalIncome',
            'incomeCategories', 
            'expenseCategories',
            'expensesByCategory', 
            'totalSpent', 
            'totalMonthlyIncome', 
            'monthlyBudget',
            'remainingBudget',
            'monthlySpending'
        );
    }

    public function exportCSV()
    {
        $data = $this->getBudgetData();
        
        $fileName = 'budget-export-' . now()->format('Y-m-d') . '.csv';
        
        return response()->streamDownload(function() use ($data) {
            $handle = fopen('php://output', 'w');
            
            // Budget Allocation
            fputcsv($handle, ['Budget Allocation', 'Amount']);
            fputcsv($handle, ['Needs (50%)', $data['budgetDistribution']['needs']]);
            fputcsv($handle, ['Wants (30%)', $data['budgetDistribution']['wants']]);
            fputcsv($handle, ['Savings (20%)', $data['budgetDistribution']['savings']]);
            fputcsv($handle, ['']);

            // Monthly Summary
            fputcsv($handle, ['Monthly Summary', 'Amount']);
            fputcsv($handle, ['Total Income', $data['totalMonthlyIncome']]);
            fputcsv($handle, ['Total Expenses', $data['totalSpent']]);
            fputcsv($handle, ['Remaining Budget', $data['remainingBudget']]);
            fputcsv($handle, ['']);

            // Expense Breakdown
            fputcsv($handle, ['Category', 'Amount', 'Percentage']);
            foreach ($data['expensesByCategory'] as $category => $amount) {
                $percentage = $data['totalSpent'] > 0 ? ($amount / $data['totalSpent']) * 100 : 0;
                fputcsv($handle, [
                    $category,
                    $amount,
                    number_format($percentage, 2) . '%'
                ]);
            }

            fclose($handle);
        }, $fileName);
    }

    public function index()
    {
        $data = $this->getBudgetData();
        return view('budget', $data);
    }
    
}