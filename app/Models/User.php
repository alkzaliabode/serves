<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // لاستخدام صلاحيات Spatie
use Illuminate\Support\Facades\Storage; // لاستخدام Storage facade
use Illuminate\Database\Eloquent\Relations\BelongsTo; // تم إضافة هذا السطر لاستخدام BelongsTo
use Illuminate\Database\Eloquent\Relations\HasMany; // *** NEW: تم إضافة هذا السطر لاستخدام HasMany ***
use App\Models\Employee; // تم إضافته: للعلاقة employees()
use App\Models\GeneralCleaningTask; // *** NEW: تم إضافة هذا السطر لاستخدام GeneralCleaningTask ***

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
        'employee_id',
        'job_title',
        'unit',
        'is_active',
        'profile_photo_path',
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
        'is_active' => 'boolean',
    ];

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string|null
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        // إذا كان هناك مسار للصورة، قم بإنشاء URL باستخدام asset helper
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
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

    /**
     * Get the general cleaning tasks created by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdGeneralCleaningTasks(): HasMany
    {
        // 'created_by' هو اسم العمود في جدول GeneralCleaningTasks الذي يشير إلى معرف المستخدم (User ID)
        return $this->hasMany(GeneralCleaningTask::class, 'created_by');
    }

    /**
     * Get the general cleaning tasks edited by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function editedGeneralCleaningTasks(): HasMany
    {
        // 'edited_by' هو اسم العمود في جدول GeneralCleaningTasks الذي يشير إلى معرف المستخدم (User ID)
        return $this->hasMany(GeneralCleaningTask::class, 'edited_by');
    }

    // ملاحظة: إذا كنت تستخدم Spatie/Laravel-permission، فإن علاقة الأدوار (roles)
    // يتم توفيرها تلقائيًا بواسطة الـ trait HasRoles. لا تحتاج لتعريفها يدوياً.
    // public function roles() { ... }
}