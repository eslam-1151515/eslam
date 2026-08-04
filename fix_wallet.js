const fs = require('fs');
let file = fs.readFileSync('database/migrations/2026_07_09_214341_add_wallet_balance_to_tenants_table.php', 'utf8');

const upContent =         Schema::table('tenants', function (Blueprint \\) {
            \\->decimal('wallet_balance', 10, 2)->default(0)->after('is_active');
        });

        Schema::table('subscription_receipts', function (Blueprint \\) {
            \\->unsignedBigInteger('plan_id')->nullable()->change();
            \\->string('type')->default('subscription')->after('id'); // subscription or wallet
        });;

const downContent =         Schema::table('subscription_receipts', function (Blueprint \\) {
            \\->dropColumn('type');
            // Reverting plan_id to not nullable can be tricky if there are null records, 
            // but we leave it as is or omit it for safety.
        });

        Schema::table('tenants', function (Blueprint \\) {
            \\->dropColumn('wallet_balance');
        });;

file = file.replace(/Schema::table\('tenants', function \(Blueprint \\) {\s*\/\/.*?}\);/s, upContent);
file = file.replace(/Schema::table\('tenants', function \(Blueprint \\) {\s*\/\/.*?}\);/s, downContent);

fs.writeFileSync('database/migrations/2026_07_09_214341_add_wallet_balance_to_tenants_table.php', file);
