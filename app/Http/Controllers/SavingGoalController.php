<?php
namespace App\Http\Controllers;
use App\Models\SavingGoal;
use App\Models\Profile;
use App\Models\Transaction; // Added Transaction model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingGoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Get all profiles associated with the authenticated user
    $profileIds = Auth::user()->profiles->pluck('id');

    // Calculate the total account balance across all profiles
    $totalIncome = Transaction::whereIn('profile_id', $profileIds)
        ->where('type', 'income')
        ->sum('amount');

    $totalExpenses = Transaction::whereIn('profile_id', $profileIds)
        ->where('type', 'expense')
        ->sum('amount');

    $accountBalance = $totalIncome - $totalExpenses;

    // Retrieve all saving goals for the account (across all profiles)
    $savingGoals = SavingGoal::whereIn('profile_id', $profileIds)
        ->with('profile')
        ->get();

    return view('savingGoals', [
        'savingGoals'    => $savingGoals,
        'profileId'      => session('selected_profile_id'),
        'accountBalance' => $accountBalance
    ]);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('saving-goals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'profile_id' => 'required|exists:profiles,id',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $savingGoal = new SavingGoal();
        $savingGoal->profile_id = $request->profile_id;
        $savingGoal->title = $request->title;
        $savingGoal->target_amount = $request->target_amount;
        $savingGoal->current_amount = 0;
        $savingGoal->description = $request->description;
        $savingGoal->deadline = $request->deadline;
        $savingGoal->save();

        return redirect('/saving-goals');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SavingGoal $savingGoal)
    {
        $savingGoal->delete();
        return redirect('/saving-goals');
    }

    /**
     * Add funds to a saving goal.
     */
    public function addFunds(Request $request, SavingGoal $savingGoal)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'source' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $savingGoal->current_amount += $request->amount;
        $savingGoal->save();

        return redirect('/saving-goals');
    }

    /**
     * Create a transaction from a saving goal.
     */
    public function createTransaction(Request $request, SavingGoal $savingGoal)
    {
        $profileId = session('selected_profile_id');

        // Recalculate account balance for additional safety:
        $incomes = Transaction::where('profile_id', $profileId)
                            ->where('type', 'income')
                            ->sum('amount');
        $expenses = Transaction::where('profile_id', $profileId)
                                ->where('type', 'expense')
                                ->sum('amount');
        $accountBalance = $incomes - $expenses;
        // Ensure sufficient funds before allowing the transaction
        if ($accountBalance < $savingGoal->target_amount) {
            return redirect('/saving-goals')->with('error', 'Insufficient funds for this goal.');
        }

        // Use the target amount from the saving goal
        $amount = $savingGoal->target_amount;

        // Create the expense transaction
        $transaction = new Transaction();
        $transaction->profile_id   = $profileId;
        $transaction->amount       = $amount; // Use target amount
        $transaction->type         = 'expense';
        $transaction->category_id  = 1;
        $transaction->description  = 'Expense for goal: ' . $savingGoal->title;
        $transaction->date         = now();
        $transaction->save();

        $savingGoal->delete();

        return redirect('/dashboard')->with('success', 'Transaction created successfully.');
    }
}