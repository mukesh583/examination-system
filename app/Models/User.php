<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User Model (Student)
 * 
 * Represents a student user who takes examinations and views their results.
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $student_id
 * @property int $enrollment_year
 * @property string $program
 * @property \Carbon\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'student_id',
        'enrollment_year',
        'program',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'enrollment_year' => 'integer',
    ];

    /**
     * Get all results for this student.
     *
     * @return HasMany
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'student_id');
    }

    /**
     * Get all semesters this student has results in.
     * 
     * This relationship goes through the results table to get distinct semesters.
     *
     * @return HasManyThrough
     */
    public function semesters(): HasManyThrough
    {
        return $this->hasManyThrough(
            Semester::class,
            Result::class,
            'student_id',    // Foreign key on results table
            'id',            // Foreign key on semesters table
            'id',            // Local key on users table
            'semester_id'    // Local key on results table
        );
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a student.
     *
     * @return bool
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
}
