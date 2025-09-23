<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
{
    /**
     * Display a listing of the user's businesses.
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $businesses = $user->businesses()->with(['incomes.transaction', 'expenses.transaction'])->get();

            return response()->json([
                'success' => true,
                'message' => 'Businesses retrieved successfully',
                'data' => [
                    'businesses' => $businesses,
                    'total' => $businesses->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve businesses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created business.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'industry' => 'required|string|max:100',
            'created_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $business = Business::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'industry' => $request->industry,
                'created_date' => $request->created_date,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Business created successfully',
                'data' => [
                    'business' => $business
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create business',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified business.
     */
    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();
            $business = $user->businesses()
                ->with([
                    'incomes.transaction.category',
                    'expenses.transaction.category',
                    'user'
                ])
                ->find($id);

            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Business retrieved successfully',
                'data' => [
                    'business' => $business
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve business',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified business.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'industry' => 'sometimes|string|max:100',
            'created_date' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $business = $user->businesses()->find($id);

            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business not found'
                ], 404);
            }

            $business->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Business updated successfully',
                'data' => [
                    'business' => $business
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update business',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified business.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            $business = $user->businesses()->find($id);

            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business not found'
                ], 404);
            }

            // Check if business has any incomes or expenses
            if ($business->incomes()->count() > 0 || $business->expenses()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete business with existing transactions. Please delete associated transactions first.'
                ], 422);
            }

            $business->delete();

            return response()->json([
                'success' => true,
                'message' => 'Business deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete business',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get business summary with financial data.
     */
    public function summary(Request $request, $id)
    {
        try {
            $user = $request->user();
            $business = $user->businesses()->find($id);

            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business not found'
                ], 404);
            }

            // Calculate total income
            $totalIncome = $business->incomes()
                ->with('transaction')
                ->get()
                ->sum(function ($income) {
                    return $income->transaction->amount ?? 0;
                });

            // Calculate total expenses
            $totalExpenses = $business->expenses()
                ->with('transaction')
                ->get()
                ->sum(function ($expense) {
                    return $expense->transaction->amount ?? 0;
                });

            // Calculate profit/loss
            $profitLoss = $totalIncome - $totalExpenses;

            // Get recent transactions
            $recentTransactions = $business->incomes()
                ->with('transaction')
                ->get()
                ->merge($business->expenses()->with('transaction')->get())
                ->sortByDesc('created_at')
                ->take(10)
                ->values();

            return response()->json([
                'success' => true,
                'message' => 'Business summary retrieved successfully',
                'data' => [
                    'business' => $business,
                    'summary' => [
                        'total_income' => $totalIncome,
                        'total_expenses' => $totalExpenses,
                        'profit_loss' => $profitLoss,
                        'transaction_count' => $business->incomes()->count() + $business->expenses()->count()
                    ],
                    'recent_transactions' => $recentTransactions
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve business summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all businesses with their summaries.
     */
    public function allSummaries(Request $request)
    {
        try {
            $user = $request->user();
            $businesses = $user->businesses()->get();

            $businessesWithSummary = $businesses->map(function ($business) {
                $totalIncome = $business->incomes()
                    ->with('transaction')
                    ->get()
                    ->sum(function ($income) {
                        return $income->transaction->amount ?? 0;
                    });

                $totalExpenses = $business->expenses()
                    ->with('transaction')
                    ->get()
                    ->sum(function ($expense) {
                        return $expense->transaction->amount ?? 0;
                    });

                return [
                    'business' => $business,
                    'summary' => [
                        'total_income' => $totalIncome,
                        'total_expenses' => $totalExpenses,
                        'profit_loss' => $totalIncome - $totalExpenses,
                        'transaction_count' => $business->incomes()->count() + $business->expenses()->count()
                    ]
                ];
            });

            // Calculate consolidated summary
            $consolidatedSummary = [
                'total_businesses' => $businesses->count(),
                'consolidated_income' => $businessesWithSummary->sum('summary.total_income'),
                'consolidated_expenses' => $businessesWithSummary->sum('summary.total_expenses'),
                'consolidated_profit_loss' => $businessesWithSummary->sum('summary.profit_loss'),
                'total_transactions' => $businessesWithSummary->sum('summary.transaction_count')
            ];

            return response()->json([
                'success' => true,
                'message' => 'Businesses summary retrieved successfully',
                'data' => [
                    'businesses' => $businessesWithSummary,
                    'consolidated_summary' => $consolidatedSummary
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve businesses summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}