<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FinancialGoal;
use App\Models\GoalProgress;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FinancialGoalController extends Controller
{
    /**
     * Display a listing of the user's financial goals.
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            // Get query parameters
            $type = $request->query('type');
            $status = $request->query('status'); // active, completed, overdue
            
            $query = $user->financialGoals()->with(['progress.transaction']);
            
            // Filter by type
            if ($type) {
                $query->where('type', $type);
            }
            
            // Filter by status
            if ($status) {
                switch ($status) {
                    case 'active':
                        $query->where('is_completed', false)
                              ->where('target_date', '>=', now());
                        break;
                    case 'completed':
                        $query->where('is_completed', true);
                        break;
                    case 'overdue':
                        $query->where('is_completed', false)
                              ->where('target_date', '<', now());
                        break;
                }
            }
            
            $goals = $query->orderBy('target_date')->get();
            
            // Calculate progress percentage for each goal
            $goals->each(function ($goal) {
                $goal->progress_percentage = $goal->target_amount > 0 
                    ? round(($goal->current_amount / $goal->target_amount) * 100, 2)
                    : 0;
                $goal->days_remaining = Carbon::parse($goal->target_date)->diffInDays(now());
                $goal->is_overdue = !$goal->is_completed && $goal->target_date < now();
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Financial goals retrieved successfully',
                'data' => [
                    'goals' => $goals,
                    'summary' => $this->calculateGoalsSummary($goals)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve financial goals',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created financial goal.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:savings,education,wedding,elder_care,emergency_fund,car,home',
            'target_amount' => 'required|numeric|min:0',
            'currency' => 'sometimes|string|max:3',
            'target_date' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $validated = $validator->validated();
            
            $goal = FinancialGoal::create([
                'user_id' => $request->user()->id,
                'name' => $validated['name'],
                'type' => $validated['type'],
                'target_amount' => $validated['target_amount'],
                'current_amount' => 0,
                'currency' => $validated['currency'] ?? 'INR', // Handle default here
                'target_date' => $validated['target_date'],
                'is_completed' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Financial goal created successfully',
                'data' => [
                    'goal' => $goal,
                    'progress_percentage' => 0,
                    'days_remaining' => Carbon::parse($goal->target_date)->diffInDays(now())
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create financial goal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified financial goal.
     */
    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();
            $goal = $user->financialGoals()
                ->with(['progress.transaction.category', 'user'])
                ->find($id);

            if (!$goal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Financial goal not found'
                ], 404);
            }

            // Calculate additional metrics
            $progressPercentage = $goal->target_amount > 0 
                ? round(($goal->current_amount / $goal->target_amount) * 100, 2)
                : 0;
                
            $daysRemaining = Carbon::parse($goal->target_date)->diffInDays(now());
            $isOverdue = !$goal->is_completed && $goal->target_date < now();
            
            // Get recent contributions
            $recentContributions = $goal->progress()
                ->with('transaction')
                ->orderBy('date', 'desc')
                ->take(10)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Financial goal retrieved successfully',
                'data' => [
                    'goal' => $goal,
                    'metrics' => [
                        'progress_percentage' => $progressPercentage,
                        'days_remaining' => $daysRemaining,
                        'is_overdue' => $isOverdue,
                        'amount_remaining' => $goal->target_amount - $goal->current_amount,
                        'monthly_saving_target' => $this->calculateMonthlyTarget($goal),
                    ],
                    'recent_contributions' => $recentContributions
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve financial goal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified financial goal.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|in:savings,education,wedding,elder_care,emergency_fund,car,home',
            'target_amount' => 'sometimes|numeric|min:0',
            'currency' => 'sometimes|string|max:3',
            'target_date' => 'sometimes|date|after:today',
            'is_completed' => 'sometimes|boolean',
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
            $goal = $user->financialGoals()->find($id);

            if (!$goal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Financial goal not found'
                ], 404);
            }

            $goal->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Financial goal updated successfully',
                'data' => [
                    'goal' => $goal
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update financial goal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified financial goal.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            $goal = $user->financialGoals()->find($id);

            if (!$goal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Financial goal not found'
                ], 404);
            }

            // Delete associated progress records
            $goal->progress()->delete();
            $goal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Financial goal deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete financial goal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add contribution to financial goal.
     */
    public function addContribution(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'sometimes|date',
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
            $goal = $user->financialGoals()->find($id);

            if (!$goal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Financial goal not found'
                ], 404);
            }

            // Check if transaction belongs to user
            $transaction = $user->transactions()->find($request->transaction_id);
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found or does not belong to user'
                ], 404);
            }

            // Check if transaction is already linked to another goal
            $existingProgress = GoalProgress::where('transaction_id', $request->transaction_id)->first();
            if ($existingProgress) {
                return response()->json([
                    'success' => false,
                    'message' => 'This transaction is already linked to another goal'
                ], 422);
            }

            // Create goal progress record
            $progress = GoalProgress::create([
                'goal_id' => $goal->id,
                'transaction_id' => $request->transaction_id,
                'amount' => $request->amount,
                'date' => $request->date ?? now(),
            ]);

            // Update goal's current amount
            $goal->increment('current_amount', $request->amount);

            // Check if goal is completed
            if ($goal->current_amount >= $goal->target_amount && !$goal->is_completed) {
                $goal->update(['is_completed' => true]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Contribution added successfully',
                'data' => [
                    'progress' => $progress,
                    'goal' => $goal->fresh(),
                    'progress_percentage' => $goal->target_amount > 0 
                        ? round(($goal->current_amount / $goal->target_amount) * 100, 2)
                        : 0
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add contribution',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove contribution from financial goal.
     */
    public function removeContribution(Request $request, $goalId, $contributionId)
    {
        try {
            $user = $request->user();
            $goal = $user->financialGoals()->find($goalId);

            if (!$goal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Financial goal not found'
                ], 404);
            }

            $progress = $goal->progress()->find($contributionId);
            if (!$progress) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contribution not found'
                ], 404);
            }

            // Update goal's current amount
            $goal->decrement('current_amount', $progress->amount);
            
            // If goal was completed, mark as incomplete if current amount is below target
            if ($goal->is_completed && $goal->current_amount < $goal->target_amount) {
                $goal->update(['is_completed' => false]);
            }

            $progress->delete();

            return response()->json([
                'success' => true,
                'message' => 'Contribution removed successfully',
                'data' => [
                    'goal' => $goal->fresh()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove contribution',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark goal as completed/incomplete.
     */
    public function toggleCompletion(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_completed' => 'sometimes|boolean',
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
            $goal = $user->financialGoals()->find($id);

            if (!$goal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Financial goal not found'
                ], 404);
            }

            $isCompleted = $request->has('is_completed') 
                ? $request->is_completed 
                : !$goal->is_completed;

            $goal->update([
                'is_completed' => $isCompleted
            ]);

            return response()->json([
                'success' => true,
                'message' => $goal->is_completed ? 'Goal marked as completed' : 'Goal marked as active',
                'data' => [
                    'goal' => $goal
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update goal status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get goals summary and analytics.
     */
    public function getSummary(Request $request)
    {
        try {
            $user = $request->user();
            $goals = $user->financialGoals()->get();

            return response()->json([
                'success' => true,
                'message' => 'Goals summary retrieved successfully',
                'data' => $this->calculateGoalsSummary($goals)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve goals summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to calculate goals summary.
     */
    private function calculateGoalsSummary($goals)
    {
        $totalGoals = $goals->count();
        $completedGoals = $goals->where('is_completed', true)->count();
        $activeGoals = $goals->where('is_completed', false)->count();
        $overdueGoals = $goals->where('is_completed', false)
            ->where('target_date', '<', now())
            ->count();

        $totalTargetAmount = $goals->sum('target_amount');
        $totalCurrentAmount = $goals->sum('current_amount');
        $totalProgress = $totalTargetAmount > 0 ? round(($totalCurrentAmount / $totalTargetAmount) * 100, 2) : 0;

        return [
            'total_goals' => $totalGoals,
            'completed_goals' => $completedGoals,
            'active_goals' => $activeGoals,
            'overdue_goals' => $overdueGoals,
            'completion_rate' => $totalGoals > 0 ? round(($completedGoals / $totalGoals) * 100, 2) : 0,
            'total_target_amount' => $totalTargetAmount,
            'total_current_amount' => $totalCurrentAmount,
            'total_progress_percentage' => $totalProgress,
            'amount_remaining' => $totalTargetAmount - $totalCurrentAmount,
        ];
    }

    /**
     * Helper method to calculate monthly saving target.
     */
    private function calculateMonthlyTarget($goal)
    {
        $monthsRemaining = Carbon::parse($goal->target_date)->diffInMonths(now());
        $amountRemaining = $goal->target_amount - $goal->current_amount;

        if ($monthsRemaining <= 0) {
            return $amountRemaining; // Return remaining amount if target date has passed
        }

        return $amountRemaining > 0 ? round($amountRemaining / $monthsRemaining, 2) : 0;
    }
}