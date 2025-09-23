<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessExpense;
use App\Models\BusinessIncome;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BusinessApiController extends Controller
{
    /**
     * Create a new transaction as a business income.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createIncome(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'business_id' => 'required|exists:businesses,id',
                'amount' => 'required|numeric|min:0',
                'description' => 'nullable|string|max:255',
                'currency' => 'nullable|string|max:3',
                'source' => 'nullable|string|max:50',
                'category_id' => 'required|exists:categories,id',
                'transaction_date' => 'required|date',
            ]);

            DB::beginTransaction();

            // Create a new transaction
            $transaction = Transaction::create([
                'user_id' => auth()->id(), // Assuming a logged-in user
                'category_id' => $validatedData['category_id'],
                'type_id' => 1, // 1 for income
                'amount' => $validatedData['amount'],
                'description' => $validatedData['description'],
                'currency' => $validatedData['currency'] ?? 'INR',
                'source' => $validatedData['source'] ?? 'api',
                'transaction_date' => $validatedData['transaction_date'],
            ]);

            // Create a new business income record
            BusinessIncome::create([
                'business_id' => $validatedData['business_id'],
                'transaction_id' => $transaction->id,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Business income created successfully.',
                'transaction' => $transaction,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new transaction as a business expense.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createExpense(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'business_id' => 'required|exists:businesses,id',
                'amount' => 'required|numeric|min:0',
                'description' => 'nullable|string|max:255',
                'currency' => 'nullable|string|max:3',
                'source' => 'nullable|string|max:50',
                'category_id' => 'required|exists:categories,id',
                'transaction_date' => 'required|date',
            ]);

            DB::beginTransaction();

            // Create a new transaction
            $transaction = Transaction::create([
                'user_id' => auth()->id(), // Assuming a logged-in user
                'category_id' => $validatedData['category_id'],
                'type_id' => 2, // 2 for expense
                'amount' => $validatedData['amount'],
                'description' => $validatedData['description'],
                'currency' => $validatedData['currency'] ?? 'INR',
                'source' => $validatedData['source'] ?? 'api',
                'transaction_date' => $validatedData['transaction_date'],
            ]);

            // Create a new business expense record
            BusinessExpense::create([
                'business_id' => $validatedData['business_id'],
                'transaction_id' => $transaction->id,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Business expense created successfully.',
                'transaction' => $transaction,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }
}
