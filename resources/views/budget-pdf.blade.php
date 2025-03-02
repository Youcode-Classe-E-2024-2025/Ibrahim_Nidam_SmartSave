<!DOCTYPE html>
<html>
<head>
    <title>Budget Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .section { margin-bottom: 25px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .chart-container { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Budget Report</h1>
        <p>Generated on {{ now()->format('F j, Y') }}</p>
    </div>

    <div class="section">
        <h2>Budget Allocation</h2>
        <table>
            <tr>
                <th>Category</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>Needs (50%)</td>
                <td class="text-right">{{ number_format($budgetDistribution['needs'], 2) }}</td>
            </tr>
            <tr>
                <td>Wants (30%)</td>
                <td class="text-right">{{ number_format($budgetDistribution['wants'], 2) }}</td>
            </tr>
            <tr>
                <td>Savings (20%)</td>
                <td class="text-right">{{ number_format($budgetDistribution['savings'], 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Monthly Summary</h2>
        <table>
            <tr>
                <th>Total Income</th>
                <td class="text-right">{{ number_format($totalMonthlyIncome, 2) }}</td>
            </tr>
            <tr>
                <th>Total Expenses</th>
                <td class="text-right">{{ number_format($totalSpent, 2) }}</td>
            </tr>
            <tr>
                <th>Remaining Budget</th>
                <td class="text-right">{{ number_format($remainingBudget, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Expense Breakdown</h2>
        <table>
            <tr>
                <th>Category</th>
                <th>Amount</th>
                <th>Percentage</th>
            </tr>
            @foreach($expensesByCategory as $category => $amount)
            <tr>
                <td>{{ $category }}</td>
                <td class="text-right">{{ number_format($amount, 2) }}</td>
                <td class="text-right">
                    {{ $totalSpent > 0 ? number_format(($amount / $totalSpent) * 100, 1) : 0 }}%
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>