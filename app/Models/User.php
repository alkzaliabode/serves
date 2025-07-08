<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // لاستخدام صلاحيات Spatie
use Illuminate\Support\Facades\Storage; // لاستخدام Storage facade
use Illuminate\Database\Eloquent\Relations\BelongsTo; // *** NEW: تم إضافة هذا السطر لاستخدام BelongsTo ***
use App\Models\Employee; // تم إضافته: للعلاقة employees()

class User extends Authenticatable // تأكد من أنك لا تستخدم MustVerifyEmail إذا لم تكن بحاجة لها
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id', // حقل جديد
        'job_title',   // حقل جديد
        'unit',        // حقل جديد
        'is_active',   // حقل جديد
        'profile_photo_path', // تم إضافة هذا الحقل
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
        'password' => 'hashed',
        'is_active' => 'boolean', // لضمان تحويلها إلى boolean
    ];

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string|null
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        // إذا كان هناك مسار للصورة، قم بإنشاء URL باستخدام Storage
        if ($this->profile_photo_path) {
            return Storage::disk('public')->url($this->profile_photo_path);
        }

        // إذا لم تكن هناك صورة، يمكنك إرجاع صورة افتراضية
        // يمكنك تغيير هذه الصورة الافتراضية إلى مسار صورة افتراضية أخرى في تطبيقك
        return 'https://placehold.co/150x150/cccccc/ffffff?text=U';
    }

    /**
     * Get the employee associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
