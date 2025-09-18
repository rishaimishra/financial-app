<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultDataSeeder extends Seeder
{
    public function run()
    {
        // Insert transaction types
        DB::table('transaction_types')->insert([
            ['name' => 'manual', 'description' => 'Manually entered transaction'],
            ['name' => 'voice', 'description' => 'Added through voice command'],
            ['name' => 'sms', 'description' => 'Automatically detected from SMS'],
            ['name' => 'auto_sms', 'description' => 'Automatically processed from SMS'],
            ['name' => 'bill_upload', 'description' => 'Extracted from uploaded bill/image'],
        ]);

        // Insert default categories
        $categories = [
            // Income categories
            ['name' => 'Salary', 'type' => 'income', 'description' => 'Monthly salary income', 'is_user_defined' => false],
            ['name' => 'Freelance', 'type' => 'income', 'description' => 'Freelance work income', 'is_user_defined' => false],
            ['name' => 'Investment', 'type' => 'income', 'description' => 'Returns from investments', 'is_user_defined' => false],
            ['name' => 'Rental', 'type' => 'income', 'description' => 'Income from property rental', 'is_user_defined' => false],
            ['name' => 'Business', 'type' => 'income', 'description' => 'Business income', 'is_user_defined' => false],
            ['name' => 'Gift', 'type' => 'income', 'description' => 'Gifts received', 'is_user_defined' => false],
            ['name' => 'Other Income', 'type' => 'income', 'description' => 'Other income sources', 'is_user_defined' => false],

            // Expense categories
            ['name' => 'Food & Dining', 'type' => 'expense', 'description' => 'Expenses on food and dining', 'is_user_defined' => false],
            ['name' => 'Groceries', 'type' => 'expense', 'description' => 'Grocery shopping', 'is_user_defined' => false],
            ['name' => 'Transportation', 'type' => 'expense', 'description' => 'Transportation costs', 'is_user_defined' => false],
            ['name' => 'Rent', 'type' => 'expense', 'description' => 'House rent', 'is_user_defined' => false],
            ['name' => 'Utilities', 'type' => 'expense', 'description' => 'Electricity, water, gas bills', 'is_user_defined' => false],
            ['name' => 'Entertainment', 'type' => 'expense', 'description' => 'Movies, events, etc.', 'is_user_defined' => false],
            ['name' => 'Shopping', 'type' => 'expense', 'description' => 'General shopping', 'is_user_defined' => false],
            ['name' => 'Healthcare', 'type' => 'expense', 'description' => 'Medical expenses', 'is_user_defined' => false],
            ['name' => 'Education', 'type' => 'expense', 'description' => 'Education-related expenses', 'is_user_defined' => false],
            ['name' => 'Personal Care', 'type' => 'expense', 'description' => 'Personal grooming', 'is_user_defined' => false],
            ['name' => 'Travel', 'type' => 'expense', 'description' => 'Travel expenses', 'is_user_defined' => false],
            ['name' => 'Gifts & Donations', 'type' => 'expense', 'description' => 'Gifts and donations', 'is_user_defined' => false],
            ['name' => 'Insurance', 'type' => 'expense', 'description' => 'Insurance premiums', 'is_user_defined' => false],
            ['name' => 'EMI', 'type' => 'expense', 'description' => 'Loan EMIs', 'is_user_defined' => false],
            ['name' => 'Taxes', 'type' => 'expense', 'description' => 'Tax payments', 'is_user_defined' => false],
            ['name' => 'Other Expense', 'type' => 'expense', 'description' => 'Other expenses', 'is_user_defined' => false],
        ];

        DB::table('categories')->insert($categories);
    }
}