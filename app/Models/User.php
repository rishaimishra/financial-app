<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'phone_number',
        'otp',
        'otp_expires_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     // Relationships
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function loanEmiDetails()
    {
        return $this->hasMany(LoanEmiDetail::class);
    }

    public function udhariRecords()
    {
        return $this->hasMany(UdhariRecord::class);
    }

    public function financialGoals()
    {
        return $this->hasMany(FinancialGoal::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function investmentRecords()
    {
        return $this->hasMany(InvestmentRecord::class);
    }

    public function insurancePolicies()
    {
        return $this->hasMany(InsurancePolicy::class);
    }

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    public function authDevices()
    {
        return $this->hasMany(AuthDevice::class);
    }

    public function consents()
    {
        return $this->hasMany(UserConsent::class);
    }
}
