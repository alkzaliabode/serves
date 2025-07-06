<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * قم بحماية هذا المتحكم باستخدام صلاحية "manage roles".
     * تأكد من أن middleware 'permission' مسجل بشكل صحيح في Kernel.php
     *
     * @return void
     */
    public function __construct()
    {
        // تم تفعيل هذا السطر لحماية المتحكم بصلاحية "manage roles".
        // هذا يضمن أن المستخدمين الذين لديهم دور "super_admin" أو أي دور آخر يمتلك صلاحية "manage roles"
        // يمكنهم الوصول إلى هذا المتحكم.
        $this->middleware('permission:manage roles'); // تتطلب صلاحية "manage roles" للوصول إلى هذا المتحكم
    }

    /**
     * عرض قائمة بجميع الأدوار.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب الأدوار مع صلاحياتها لضمان عرضها في الجدول
        $roles = Role::with('permissions')->paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * عرض نموذج إنشاء دور جديد.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $permissions = Permission::all(); // جلب جميع الصلاحيات المتاحة
        return view('roles.create', compact('permissions'));
    }

    /**
     * تخزين دور جديد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'], // التحقق من أن كل صلاحية موجودة
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'تم إنشاء الدور بنجاح.');
    }

    /**
     * عرض نموذج تعديل دور موجود.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all(); // جلب جميع الصلاحيات المتاحة
        // تمرير الصلاحيات الحالية للدور لتحديدها في الفورم
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * تحديث معلومات دور موجود في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->update(['name' => $request->name]);

        // مزامنة الصلاحيات: إزالة جميع الصلاحيات الحالية وتعيين الصلاحيات الجديدة
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            // إذا لم يتم تحديد أي صلاحيات، قم بإزالة جميع الصلاحيات من الدور
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')->with('success', 'تم تحديث الدور بنجاح.');
    }

    /**
     * حذف دور من قاعدة البيانات.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        // حماية لمنع حذف دور 'super_admin' أو أي أدوار حساسة أخرى إذا لزم الأمر
        if ($role->name === 'super_admin') {
            return redirect()->route('roles.index')->with('error', 'لا يمكن حذف دور Super Admin.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'تم حذف الدور بنجاح.');
    }
}
