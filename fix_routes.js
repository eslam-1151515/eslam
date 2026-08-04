const fs = require('fs');
let file = fs.readFileSync('routes/web.php', 'utf8');

const target = Route::get('/tenants/{tenant}/impersonate', [App\\Http\\Controllers\\SuperAdmin\\TenantController::class, 'impersonate'])->name('superadmin.tenants.impersonate');;
const replacement = target + 
        Route::post('/tenants/{tenant}/add-wallet-balance', [App\\Http\\Controllers\\SuperAdmin\\TenantController::class, 'addWalletBalance'])->name('superadmin.tenants.add-wallet-balance');
        Route::delete('/tenants/{tenant}', [App\\Http\\Controllers\\SuperAdmin\\TenantController::class, 'destroy'])->name('superadmin.tenants.destroy');;

file = file.replace(target, replacement);

fs.writeFileSync('routes/web.php', file);
