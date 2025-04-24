
php artisan make:migration create_tenants_table
php artisan make:migration create_tenant_users_table
php artisan make:migration create_users_table
php artisan make:migration create_password_reset_tokens_table
php artisan make:migration create_sessions_table
php artisan make:migration create_businesses_table
php artisan make:migration create_branches_table
php artisan make:migration create_categories_table
php artisan make:migration create_brands_table
php artisan make:migration create_suppliers_table
php artisan make:migration create_products_table
php artisan make:migration create_product_variants_table
php artisan make:migration create_inventory_logs_table
php artisan make:migration create_customers_table
php artisan make:migration create_payment_methods_table
php artisan make:migration create_sales_table
php artisan make:migration create_sale_items_table
php artisan make:migration create_sale_payments_table
php artisan make:migration create_returns_table
php artisan make:migration create_return_items_table
php artisan make:migration create_purchases_table
php artisan make:migration create_purchase_items_table
php artisan make:migration create_purchase_payments_table
php artisan make:migration create_accounts_table
php artisan make:migration create_transactions_table
php artisan make:migration create_expense_categories_table
php artisan make:migration create_expenses_table
php artisan make:migration create_tax_rates_table
php artisan make:migration create_daily_sales_table
php artisan make:migration create_stock_history_table
php artisan make:migration create_settings_table
php artisan make:migration create_notifications_table
php artisan make:migration create_activity_log_table


php artisan make:model Tenant
php artisan make:model TenantUser
php artisan make:model User
php artisan make:model PasswordResetToken
php artisan make:model Session
php artisan make:model Business
php artisan make:model Branch
php artisan make:model Category
php artisan make:model Brand
php artisan make:model Supplier
php artisan make:model Product
php artisan make:model ProductVariant
php artisan make:model InventoryLog
php artisan make:model Customer
php artisan make:model PaymentMethod
php artisan make:model Sale
php artisan make:model SaleItem
php artisan make:model SalePayment
php artisan make:model Return
php artisan make:model ReturnItem
php artisan make:model Purchase
php artisan make:model PurchaseItem
php artisan make:model PurchasePayment
php artisan make:model Account
php artisan make:model Transaction
php artisan make:model ExpenseCategory
php artisan make:model Expense
php artisan make:model TaxRate
php artisan make:model DailySale
php artisan make:model StockHistory
php artisan make:model Setting
php artisan make:model Notification
php artisan make:model ActivityLog



==========================================================================================================================

1.Core Multi-Tenancy Tables

Schema::create('tenants', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('domain')->unique();
    $table->string('database_name')->unique();
    $table->string('timezone')->default('UTC');
    $table->string('currency', 3)->default('USD');
    $table->string('locale', 10)->default('en_US');
    $table->boolean('is_active')->default(true);
    $table->json('settings')->nullable();
    $table->timestamp('trial_ends_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
});

Schema::create('tenant_users', function (Blueprint $table) {
    $table->unsignedBigInteger('tenant_id');
    $table->unsignedBigInteger('user_id');
    $table->string('role')->default('member');
    
    $table->primary(['tenant_id', 'user_id']);
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
2. User Management (Extended from Laravel Breeze)

Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('phone', 20)->nullable();
    $table->text('address')->nullable();
    $table->string('profile_photo_path', 2048)->nullable();
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();
});

Schema::create('password_reset_tokens', function (Blueprint $table) {
    $table->string('email')->primary();
    $table->string('token');
    $table->timestamp('created_at')->nullable();
});

Schema::create('sessions', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->foreignId('user_id')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->longText('payload');
    $table->integer('last_activity')->index();
});
3. Business Structure

Schema::create('businesses', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('name');
    $table->string('tax_number')->nullable();
    $table->string('registration_number')->nullable();
    $table->string('phone', 20);
    $table->string('email');
    $table->text('address');
    $table->string('logo_path', 2048)->nullable();
    $table->string('receipt_header', 2048)->nullable();
    $table->string('receipt_footer', 2048)->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('branches', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->unsignedBigInteger('business_id');
    $table->string('name');
    $table->string('code', 10)->unique();
    $table->string('phone', 20);
    $table->string('email');
    $table->text('address');
    $table->boolean('is_main')->default(false);
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
    $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
});

4. Inventory Management
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('name', 100);
    $table->string('code', 20)->nullable();
    $table->unsignedBigInteger('parent_id')->nullable();
    $table->text('description')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
    $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
});

Schema::create('brands', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('name', 100);
    $table->text('description')->nullable();
    $table->string('logo_path', 2048)->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('suppliers', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('name', 255);
    $table->string('contact_person', 255)->nullable();
    $table->string('email')->nullable();
    $table->string('phone', 20);
    $table->string('alternate_phone', 20)->nullable();
    $table->text('address');
    $table->string('tax_number')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('name', 255);
    $table->string('sku', 100)->unique();
    $table->string('barcode', 100)->nullable();
    $table->foreignId('category_id')->constrained()->onDelete('set null');
    $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
    $table->text('description')->nullable();
    $table->text('image_paths')->nullable(); // JSON array of images
    $table->string('status', 20)->default('active');
    $table->boolean('is_taxable')->default(true);
    $table->boolean('track_inventory')->default(true);
    $table->integer('reorder_level')->default(5);
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('product_variants', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->string('name', 100);
    $table->string('sku', 100)->unique();
    $table->string('barcode', 100)->nullable();
    $table->decimal('purchase_price', 12, 2);
    $table->decimal('selling_price', 12, 2);
    $table->integer('current_stock')->default(0);
    $table->string('unit_type', 50)->default('pcs');
    $table->decimal('weight', 10, 3)->nullable();
    $table->string('status', 20)->default('active');
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('inventory_logs', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
    $table->foreignId('branch_id')->constrained()->onDelete('cascade');
    $table->integer('quantity_change');
    $table->integer('new_quantity');
    $table->string('reference_type'); // purchase, sale, adjustment, etc.
    $table->unsignedBigInteger('reference_id')->nullable();
    $table->text('notes')->nullable();
    $table->foreignId('user_id')->constrained()->onDelete('set null');
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

5. Sales & Customers

Schema::create('customers', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('name', 255);
    $table->string('email')->nullable();
    $table->string('phone', 20);
    $table->string('address')->nullable();
    $table->string('tax_number')->nullable();
    $table->decimal('credit_limit', 12, 2)->default(0);
    $table->decimal('balance', 12, 2)->default(0);
    $table->string('customer_group', 50)->default('retail');
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('payment_methods', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('name', 50);
    $table->string('code', 20)->unique();
    $table->string('type', 50); // cash, card, bank_transfer, etc.
    $table->boolean('is_active')->default(true);
    $table->json('settings')->nullable(); // For payment processor config
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('sales', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('branch_id')->constrained()->onDelete('restrict');
    $table->string('invoice_number')->unique();
    $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('user_id')->constrained()->onDelete('set null'); // Cashier
    $table->decimal('subtotal', 12, 2);
    $table->decimal('tax_amount', 12, 2)->default(0);
    $table->decimal('discount_amount', 12, 2)->default(0);
    $table->decimal('shipping_amount', 12, 2)->default(0);
    $table->decimal('total_amount', 12, 2);
    $table->decimal('amount_paid', 12, 2);
    $table->decimal('change_amount', 12, 2)->default(0);
    $table->string('payment_status', 20)->default('paid'); // paid, partial, pending
    $table->string('status', 20)->default('completed'); // completed, pending, cancelled
    $table->text('notes')->nullable();
    $table->timestamp('sale_date')->useCurrent();
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('sale_items', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('sale_id')->constrained()->onDelete('cascade');
    $table->foreignId('product_id')->constrained()->onDelete('restrict');
    $table->foreignId('variant_id')->constrained('product_variants')->onDelete('restrict');
    $table->decimal('quantity', 10, 2);
    $table->decimal('unit_price', 12, 2);
    $table->decimal('cost_price', 12, 2);
    $table->decimal('tax_rate', 5, 2)->default(0);
    $table->decimal('tax_amount', 12, 2)->default(0);
    $table->decimal('discount_rate', 5, 2)->default(0);
    $table->decimal('discount_amount', 12, 2)->default(0);
    $table->decimal('total_price', 12, 2);
    $table->text('notes')->nullable();
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('sale_payments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('sale_id')->constrained()->onDelete('cascade');
    $table->foreignId('payment_method_id')->constrained()->onDelete('restrict');
    $table->decimal('amount', 12, 2);
    $table->string('reference')->nullable();
    $table->text('notes')->nullable();
    $table->foreignId('user_id')->constrained()->onDelete('set null');
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('returns', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('sale_id')->constrained()->onDelete('cascade');
    $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('user_id')->constrained()->onDelete('set null');
    $table->string('return_number')->unique();
    $table->decimal('total_amount', 12, 2);
    $table->string('status', 20)->default('pending');
    $table->text('reason');
    $table->timestamp('return_date')->useCurrent();
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('return_items', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('return_id')->constrained()->onDelete('cascade');
    $table->foreignId('sale_item_id')->constrained('sale_items')->onDelete('restrict');
    $table->decimal('quantity', 10, 2);
    $table->decimal('unit_price', 12, 2);
    $table->decimal('total_price', 12, 2);
    $table->text('reason')->nullable();
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});
6. Purchasing & Suppliers

Schema::create('purchases', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('supplier_id')->constrained()->onDelete('restrict');
    $table->foreignId('branch_id')->constrained()->onDelete('restrict');
    $table->string('invoice_number');
    $table->decimal('subtotal', 12, 2);
    $table->decimal('tax_amount', 12, 2)->default(0);
    $table->decimal('discount_amount', 12, 2)->default(0);
    $table->decimal('shipping_amount', 12, 2)->default(0);
    $table->decimal('total_amount', 12, 2);
    $table->string('status', 20)->default('received'); // ordered, partial, received, cancelled
    $table->text('notes')->nullable();
    $table->timestamp('purchase_date')->useCurrent();
    $table->timestamp('expected_delivery_date')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('purchase_items', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
    $table->foreignId('product_id')->constrained()->onDelete('restrict');
    $table->foreignId('variant_id')->constrained('product_variants')->onDelete('restrict');
    $table->decimal('quantity', 10, 2);
    $table->decimal('quantity_received', 10, 2)->default(0);
    $table->decimal('unit_price', 12, 2);
    $table->decimal('tax_rate', 5, 2)->default(0);
    $table->decimal('tax_amount', 12, 2)->default(0);
    $table->decimal('discount_rate', 5, 2)->default(0);
    $table->decimal('discount_amount', 12, 2)->default(0);
    $table->decimal('total_price', 12, 2);
    $table->text('notes')->nullable();
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('purchase_payments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
    $table->foreignId('payment_method_id')->constrained()->onDelete('restrict');
    $table->decimal('amount', 12, 2);
    $table->string('reference')->nullable();
    $table->text('notes')->nullable();
    $table->foreignId('user_id')->constrained()->onDelete('set null');
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});
7. Financial Management

Schema::create('accounts', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('name');
    $table->string('type'); // cash, bank, credit_card, mobile_money, etc.
    $table->string('account_number')->nullable();
    $table->decimal('opening_balance', 12, 2)->default(0);
    $table->decimal('current_balance', 12, 2)->default(0);
    $table->string('currency', 3)->default('USD');
    $table->boolean('is_default')->default(false);
    $table->boolean('is_active')->default(true);
    $table->text('description')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('account_id')->constrained()->onDelete('restrict');
    $table->string('type'); // income, expense, transfer
    $table->decimal('amount', 12, 2);
    $table->string('reference')->nullable();
    $table->text('description')->nullable();
    $table->string('category')->nullable();
    $table->foreignId('user_id')->constrained()->onDelete('set null');
    $table->timestamp('date')->useCurrent();
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('expense_categories', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('name');
    $table->text('description')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('expenses', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('expense_category_id')->constrained()->onDelete('restrict');
    $table->foreignId('account_id')->constrained()->onDelete('restrict');
    $table->decimal('amount', 12, 2);
    $table->string('reference')->nullable();
    $table->text('description')->nullable();
    $table->foreignId('user_id')->constrained()->onDelete('set null');
    $table->timestamp('date')->useCurrent();
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('tax_rates', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('name');
    $table->decimal('rate', 5, 2);
    $table->string('type')->default('percentage'); // percentage or fixed
    $table->boolean('is_inclusive')->default(false);
    $table->text('description')->nullable();
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

8. Reporting & Analytics

Schema::create('daily_sales', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('branch_id')->constrained()->onDelete('cascade');
    $table->date('date');
    $table->integer('total_sales')->default(0);
    $table->decimal('total_amount', 12, 2)->default(0);
    $table->decimal('total_tax', 12, 2)->default(0);
    $table->decimal('total_discount', 12, 2)->default(0);
    $table->decimal('total_profit', 12, 2)->default(0);
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
    $table->unique(['tenant_id', 'branch_id', 'date']);
});

Schema::create('stock_history', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->foreignId('branch_id')->constrained()->onDelete('cascade');
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
    $table->date('date');
    $table->integer('opening_stock')->default(0);
    $table->integer('purchases')->default(0);
    $table->integer('sales')->default(0);
    $table->integer('adjustments')->default(0);
    $table->integer('closing_stock')->default(0);
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
    $table->unique(['tenant_id', 'branch_id', 'product_id', 'variant_id', 'date']);
});

9. System & Settings

Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('key');
    $table->text('value')->nullable();
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
    $table->unique(['tenant_id', 'key']);
});

Schema::create('notifications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->unsignedBigInteger('tenant_id');
    $table->string('type');
    $table->morphs('notifiable');
    $table->text('data');
    $table->timestamp('read_at')->nullable();
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});

Schema::create('activity_log', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('log_name')->nullable();
    $table->text('description');
    $table->nullableMorphs('subject', 'subject');
    $table->nullableMorphs('causer', 'causer');
    $table->json('properties')->nullable();
    $table->timestamps();
    
    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
    $table->index(['tenant_id', 'log_name']);
});
===============================================================================================================
// app/Models/Tenant.php
class Tenant extends Model
{
    use HasFactory, SoftDeletes;
    protected $casts = [
        'settings' => 'json',
        'is_active' => 'boolean',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_users')
            ->withPivot('role')
            ->withTimestamps();
    }
    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    // Add relationships for all other tenant-related models
    public function branches() { return $this->hasMany(Branch::class); }
    public function categories() { return $this->hasMany(Category::class); }
    public function brands() { return $this->hasMany(Brand::class); }
    public function suppliers() { return $this->hasMany(Supplier::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function customers() { return $this->hasMany(Customer::class); }
    public function sales() { return $this->hasMany(Sale::class); }
    public function purchases() { return $this->hasMany(Purchase::class); }
    public function accounts() { return $this->hasMany(Account::class); }
}
// app/Models/TenantUser.php
class TenantUser extends Pivot
{
    protected $table = 'tenant_users';
}

// app/Models/User.php
class User extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;
    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_users')
            ->withPivot('role')
            ->withTimestamps();
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}

// app/Models/Business.php
class Business extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
// app/Models/Branch.php
class Branch extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
// app/Models/Category.php
class Category extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

// app/Models/Brand.php
class Brand extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
// app/Models/Supplier.php
class Supplier extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
// app/Models/Product.php
class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $casts = [
        'image_paths' => 'array',
        'is_taxable' => 'boolean',
        'track_inventory' => 'boolean',
    ];
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}


// app/Models/ProductVariant.php
class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }
}

// app/Models/InventoryLog.php
class InventoryLog extends Model
{
    use HasFactory;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// app/Models/Customer.php
class Customer extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    public function returns()
    {
        return $this->hasMany(Return::class);
    }
}

// app/Models/PaymentMethod.php
class PaymentMethod extends Model
{
    use HasFactory;
    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'json',
    ];
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function salePayments()
    {
        return $this->hasMany(SalePayment::class);
    }
    public function purchasePayments()
    {
        return $this->hasMany(PurchasePayment::class);
    }
}

// app/Models/Sale.php
class Sale extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
    public function payments()
    {
        return $this->hasMany(SalePayment::class);
    }
    public function returns()
    {
        return $this->hasMany(Return::class);
    }
}

// app/Models/SaleItem.php
class SaleItem extends Model
{
    use HasFactory;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
    public function returnItems()
    {
        return $this->hasMany(ReturnItem::class);
    }
}

// app/Models/SalePayment.php
class SalePayment extends Model
{
    use HasFactory;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
// app/Models/Return.php
class Return extends Model
{
    use HasFactory;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(ReturnItem::class);
    }
}
// app/Models/ReturnItem.php
class ReturnItem extends Model
{
    use HasFactory;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function return()
    {
        return $this->belongsTo(Return::class);
    }
    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class);
    }
}
// app/Models/Purchase.php
class Purchase extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    public function payments()
    {
        return $this->hasMany(PurchasePayment::class);
    }
}

// app/Models/PurchaseItem.php
class PurchaseItem extends Model
{
    use HasFactory;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}

// app/Models/PurchasePayment.php
class PurchasePayment extends Model
{
    use HasFactory;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
// app/Models/Account.php
class Account extends Model
{
    use HasFactory, SoftDeletes;
    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
// app/Models/Transaction.php
class Transaction extends Model
{
    use HasFactory;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
// app/Models/ExpenseCategory.php
class ExpenseCategory extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}

// app/Models/Expense.php
class Expense extends Model
{
    use HasFactory;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
// app/Models/TaxRate.php
class TaxRate extends Model
{
    use HasFactory;
    protected $casts = [
        'is_inclusive' => 'boolean',
    ];
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}

// app/Models/DailySale.php
class DailySale extends Model
{
    use HasFactory;

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}

// app/Models/StockHistory.php
class StockHistory extends Model
{
    use HasFactory;

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
// app/Models/Setting.php
class Setting extends Model
{
    use HasFactory;

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
// app/Models/Notification.php
class Notification extends Model
{
    use HasFactory;

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }
}
// app/Models/ActivityLog.php
class ActivityLog extends Model
{
    use HasFactory;

    protected $casts = [
        'properties' => 'collection',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function causer()
    {
        return $this->morphTo();
    }
}

=====================================================================================================

here's a comprehensive list of tables, forms, and screens you'll need to create for your multi-tenant application:

##### 1. Core Multi-Tenancy (5 screens)
- **Tenants Management**
  - List tenants (table view)
  - Create/Edit tenant form
  - Tenant details view
- **Tenant Users Management**
  - Assign users to tenants (form)
  - Manage user roles (form)

##### 2. User Management (4 screens)
- **Users**
  - Users list (table)
  - User create/edit form
  - User profile view
- **Authentication**
  - Login/Register screens (handled by Breeze)

##### 3. Business Structure (4 screens)
- **Businesses**
  - List businesses (table)
  - Create/Edit business form
- **Branches**
  - List branches (table)
  - Create/Edit branch form

##### 4. Inventory Management (15 screens)
- **Categories**
  - Category tree/list view
  - Create/Edit category form
- **Brands**
  - Brands list (table)
  - Create/Edit brand form
- **Suppliers**
  - Suppliers list (table)
  - Create/Edit supplier form
- **Products**
  - Products list (table with filters)
  - Create/Edit product form (with variants)
  - Product details view
- **Product Variants**
  - Variants list (table)
  - Create/Edit variant form
- **Inventory**
  - Current stock levels (table)
  - Stock adjustment form
  - Inventory movement log (table)
  - Low stock alerts view

##### 5. Sales & Customers (12 screens)
- **Customers**
  - Customers list (table)
  - Create/Edit customer form
  - Customer details view
- **Sales**
  - Sales list (table with filters)
  - POS interface
  - Sales create form
  - Sales details/invoice view
  - Sales returns form
- **Payments**
  - Payment methods list
  - Payment method form
  - Payment processing form
- **Returns**
  - Returns list
  - Return processing form

##### 6. Purchasing & Suppliers (6 screens)
- **Purchases**
  - Purchases list (table)
  - Purchase order form
  - Purchase details view
  - Goods receipt form
- **Supplier Payments**
  - Payment processing form
  - Payment history (table)

##### 7. Financial Management (10 screens)
- **Accounts**
  - Chart of accounts (tree/list)
  - Account form
  - Account balance view
- **Transactions**
  - Transactions ledger (table)
  - Transaction entry form
- **Expenses**
  - Expense categories list
  - Expense category form
  - Expense entry form
  - Expense reports
- **Taxes**
  - Tax rates list
  - Tax rate form

##### 8. Reporting & Analytics (6 screens)
- **Sales Reports**
  - Daily sales dashboard
  - Sales by period
  - Sales by product/category
- **Inventory Reports**
  - Stock movement reports
  - Stock valuation
- **Financial Reports**
  - Profit & Loss
  - Balance Sheet
  - Cash Flow

##### 9. System & Settings (4 screens)
- **System Settings**
  - Global settings form
  - Tenant-specific settings
- **Notifications**
  - Notifications center
- **Activity Log**
  - Audit log viewer

##### Summary Count:
- **Total Tables**: 29 (from your schema)
- **Total Forms**: ~50 (including create/edit forms for each entity)
- **Total Screens**: ~65 (including list views, detail views, and special interfaces like POS)

##### Key Complex Screens:
1. **POS Interface** - Real-time sales with barcode scanning
2. **Inventory Management Dashboard** - With stock level indicators
3. **Multi-tenant Admin Dashboard** - Overview of all tenants
4. **Financial Reporting Suite** - With export capabilities
5. **Product Variant Matrix** - For managing product options

##### Recommended Additional Screens:
1. **Dashboard** - Main overview with KPIs
2. **Data Import/Export** - For bulk operations
3. **Barcode/Label Printing** - For inventory items
4. **User Permissions Manager** - Fine-grained access control

This structure provides complete coverage for all database entities while maintaining a logical workflow through the application. Each main entity 
(products, sales, purchases, etc.) gets its own set of list, create, edit, and detail views, with additional specialized screens for complex operations.