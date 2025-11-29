# QUICK FIX: Operator Views Problem

## Problem
Operator sidebar berubah jadi Super Admin sidebar saat klik:
- Penggajian Dosen
- Keuangan  
- KRS Approval

## Root Cause
- Routes menggunakan `Admin\...Controller`
- Views menggunakan `@extends('layouts.admin')`
- Sidebar context berubah karena layout

## Solution Strategy
**Pattern: Extend Admin Controller, Override View Methods Only**

### 1. Create Operator Controllers (Extend Admin)
```php
// app/Http/Controllers/Operator/PenggajianDosenController.php
class PenggajianDosenController extends \App\Http\Controllers\Admin\PenggajianDosenController
{
    // Override ONLY view methods
    public function index() {
        // Same logic, return operator view
    }
}
```

### 2. Copy Views to Operator Folder
```bash
cp -r resources/views/admin/penggajian-dosen resources/views/operator/
cp -r resources/views/admin/keuangan resources/views/operator/
cp -r resources/views/admin/krs-approval resources/views/operator/
```

### 3. Update @extends in All Operator Views
```blade
@extends('layouts.admin')  →  @extends('layouts.operator')
```

### 4. Update Route Names in Views
```blade
route('admin.penggajian.index')  →  route('operator.penggajian.index')
route('admin.keuangan.index')    →  route('operator.keuangan.index')
route('admin.krs-approval.index') →  route('operator.krs-approval.index')
```

### 5. Update Routes
```php
// routes/web.php - Operator section
Route::prefix('penggajian-dosen')->name('penggajian.')->group(function() {
    Route::get('/', [\App\Http\Controllers\Operator\PenggajianDosenController::class, 'index']);
    // etc...
});
```

## Affected Modules

### 1. Penggajian Dosen
- **Controller**: Create `Operator\PenggajianDosenController`
- **Views**: 
  - `operator/penggajian-dosen/index.blade.php`
  - `operator/penggajian-dosen/show.blade.php`
  - `operator/penggajian-dosen/payment.blade.php`
- **Routes**: Update operator section

### 2. Keuangan
- **Controller**: Create `Operator\KeuanganController`
- **Views**:
  - `operator/keuangan/index.blade.php`
  - `operator/keuangan/create.blade.php`
  - `operator/keuangan/edit.blade.php`
  - `operator/keuangan/show.blade.php`
- **Routes**: Update operator section

### 3. KRS Approval  
- **Controller**: Create `Operator\KrsApprovalController`
- **Views**:
  - `operator/krs-approval/index.blade.php`
  - `operator/krs-approval/detail.blade.php`
  - `operator/krs-approval/show.blade.php`
- **Routes**: Update operator section

## Implementation Steps

1. ✅ Create 3 Operator controllers
2. ⏳ Implement controller methods (override view methods only)
3. ⏳ Copy admin views to operator folders
4. ⏳ Update @extends in all operator views
5. ⏳ Update route names in views
6. ⏳ Update routes.php
7. ⏳ Test sidebar consistency

## Why This Approach?

**Pros:**
- ✅ Minimal code duplication (logic inherited from Admin)
- ✅ Easy maintenance (business logic in one place)
- ✅ Consistent pattern (same as JalurSeleksi fix)
- ✅ Sidebar stays consistent for each role

**Cons:**
- ❌ View duplication (but necessary for role separation)
- ❌ Route duplication (but necessary for proper naming)

## Alternative Considered

**Dynamic Layout Detection:**
```php
// In Admin controller
public function index() {
    $layout = auth()->user()->role === 'operator' ? 'operator' : 'admin';
    return view("$layout.penggajian.index");
}
```

**Why Not:**
- Route names still confusing
- Less explicit
- Harder to debug
- Mixing concerns

## Long-term Solution

Consider creating a **shared views** approach with layout injection:
```php
// ViewServiceProvider
View::composer('admin.*', function($view) {
    $view->with('layout', 'layouts.admin');
});

View::composer('operator.*', function($view) {
    $view->with('layout', 'layouts.operator');
});
```

But for now, explicit separation is clearer.
