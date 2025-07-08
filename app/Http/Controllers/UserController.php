<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role; // استيراد نموذج الدور
use Spatie\Permission\Models\Permission; // استيراد نموذج الصلاحية (مهم للوضوح حتى لو لم يستخدم مباشرة هنا)


class UserController extends Controller
{
    /**
     * قم بحماية هذا المتحكم باستخدام صلاحيات Spatie.
     * يتطلب هذا أن يكون المستخدم لديه صلاحية "manage users" للوصول.
     *
     * @return void
     */
    public function __construct()
    {
        // تم تفعيل هذا السطر لحماية المتحكم بصلاحية "manage users".
        // هذا يضمن أن المستخدمين الذين لديهم دور "super_admin" أو أي دور آخر يمتلك صلاحية "manage users"
        // يمكنهم الوصول إلى هذا المتحكم.
        $this->middleware('permission:manage users'); // تتطلب صلاحية "manage users" للوصول إلى هذا المتحكم
    }

    /**
     * عرض قائمة بجميع المستخدمين.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب المستخدمين مع أدوارهم لضمان عرضها في الجدول
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * عرض نموذج إنشاء مستخدم جديد.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::all(); // جلب جميع الأدوار المتاحة من Spatie
        return view('users.create', compact('roles'));
    }

    /**
     * تخزين مستخدم جديد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
            'employee_id' => ['nullable', 'string', 'max:255', 'unique:users'], // حقل جديد
            'job_title' => ['nullable', 'string', 'max:100'], // حقل جديد
            'unit' => ['nullable', 'string', 'max:100'], // حقل جديد
            'is_active' => ['boolean'], // حقل جديد
            'roles' => ['required', 'array'], // يجب أن يكون مصفوفة لأننا نستخدم Select::multiple
            'roles.*' => ['exists:roles,name'], // التحقق من أن كل دور موجود
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'employee_id' => $request->employee_id,
            'job_title' => $request->job_title,
            'unit' => $request->unit,
            'is_active' => $request->boolean('is_active'), // لضمان تحويلها إلى boolean
            'email_verified_at' => now(), // يمكن تعيينها مباشرة عند الإنشاء
        ]);

        // تعيين الأدوار للمستخدم
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    /**
     * عرض نموذج تعديل مستخدم موجود.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        // تمرير الأدوار الحالية للمستخدم لتحديدها في الفورم
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * تحديث معلومات مستخدم موجود في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
            'employee_id' => ['nullable', 'string', 'max:255', 'unique:users,employee_id,' . $user->id], // حقل جديد
            'job_title' => ['nullable', 'string', 'max:100'], // حقل جديد
            'unit' => ['nullable', 'string', 'max:100'], // حقل جديد
            'is_active' => ['boolean'], // حقل جديد
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->employee_id = $request->employee_id;
        $user->job_title = $request->job_title;
        $user->unit = $request->unit;
        $user->is_active = $request->boolean('is_active'); // لضمان تحويلها إلى boolean

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // مزامنة الأدوار: إزالة جميع الأدوار الحالية وتعيين الأدوار الجديدة
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح.');
    }

    /**
     * حذف مستخدم من قاعدة البيانات.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // حماية لمنع حذف المستخدم الحالي
        if (auth()->user()->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح.');
    }
}
