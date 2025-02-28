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
        $savingGoals = SavingGoal::with('profile')->get();
        $profileId = session('selected_profile_id');
        return view('savingGoals', ['savingGoals'=>$savingGoals,'profileId'=> $profileId]);
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

        return redirect('/saving-goals')->with('success', 'Saving goal created successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SavingGoal $savingGoal)
    {
        $savingGoal->delete();
        return redirect('/saving-goals')->with('success', 'Saving goal deleted successfully!');
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

        return redirect('/saving-goals')->with('success', 'Funds added successfully!');
    }

    /**
     * Create a transaction from a saving goal.
     */
    public function createTransaction(Request $request, SavingGoal $savingGoal)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'category' => 'required|string',
            'account' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Ensure the goal has sufficient funds
        if ($savingGoal->current_amount < $request->amount) {
            return redirect('/saving-goals')->with('error', 'Insufficient funds in the saving goal!');
        }

        // Create a new transaction
        $transaction = new Transaction();
        $transaction->profile_id = $savingGoal->profile_id;
        $transaction->amount = $request->amount;
        $transaction->type = 'expense';
        $transaction->category = $request->category;
        $transaction->account = $request->account;
        $transaction->description = $savingGoal->title . ' goal expense';
        $transaction->notes = $request->notes;
        $transaction->date = now();
        $transaction->save();

        // Reduce the current amount in the saving goal
        $savingGoal->current_amount -= $request->amount;
        $savingGoal->save();

        return redirect('/saving-goals')->with('success', 'Transaction created successfully!');
    }
}