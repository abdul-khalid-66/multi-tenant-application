
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












\\\\\\\\\\\\\\\\\\\\|  |\\\\\/  /\\|  |\\\\\\\\|  |\\|  |\\\\\\\\\\\             \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
\\\\\\\\\\\\\\\\\\\\|  |\\\\/  //\\|  |\\\\\\\\|  |\\|  |\\\\\\\\\\\\\\\|  |\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
\\\\\\\\\\\\\\\\\\\\|  |\\\/  //\\\|  |\\\\\\\\|  |\\|  |\\\\\\\\\\\\\\\|  |\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
\\\\\\\\\\\\\\\\\\\\|  |\\/  //\\\\|  |\\\\\\\\|  |\\|  |\\\\\\\\\\\\\\\|  |\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
\\\\\\\\\\\\\\\\\\\\|  |\/  //\\\\\|              |\\|  |\\\\\\\\\\\\\\\|  |\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
\\\\\\\\\\\\\\\\\\\\|  |\\  \\\\\\\|  |\\\\\\\\|  |\\|  |\\\\\\\\\\\\\\\|  |\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
\\\\\\\\\\\\\\\\\\\\|  |\\\  \\\\\\|  |\\\\\\\\|  |\\|  |\\\\\\\\\\\\\\\|  |\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
\\\\\\\\\\\\\\\\\\\\|  |\\\\  \\\\\|  |\\\\\\\\|  |\\|  |\\\\\\\\\\\\\\\|  |\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
\\\\\\\\\\\\\\\\\\\\|  |\\\\\  \\\\|  |\\\\\\\\|  |\\|  _________|\\            \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\







Schema Structure should be like this:
1.Core Multi-Tenancy Tables

tenants = { id | name | slug | domain | database_name | timezone | currency | locale | is_active | settings | trial_ends_at | created_at | updated_at | deleted_at }
tenant_users = { tenant_id | user_id | role }


2. User Management (Extended from Laravel Breeze)
users = { id | name | email | email_verified_at | password | phone | address | profile_photo_path | remember_token | created_at | updated_at | deleted_at }
password_reset_tokens = { email | token | created_at }
sessions = { id | user_id | ip_address | user_agent | payload | last_activity }

3. Business Structure
businesses = { id | tenant_id | name | tax_number | registration_number | phone | email | address | logo_path | receipt_header | receipt_footer | created_at | updated_at | deleted_at }
branches = { id | tenant_id | business_id | name | code | phone | email | address | is_main | created_at | updated_at | deleted_at }


4. Inventory Management

categories = { id | tenant_id | name | code | parent_id | description | created_at | updated_at | deleted_at }
brands = { id | tenant_id | name | description | logo_path | created_at | updated_at | deleted_at }
suppliers = { id | tenant_id | name | contact_person | email | phone | alternate_phone | address | tax_number | created_at | updated_at | deleted_at }
products = { id | tenant_id | name | sku | barcode | category_id | brand_id | supplier_id | description | image_paths | status | is_taxable | track_inventory | reorder_level | created_at | updated_at | deleted_at }
product_variants = { id | tenant_id | product_id | name | sku | barcode | purchase_price | selling_price | current_stock | unit_type | weight | status | created_at | updated_at | deleted_at }
inventory_logs = { id | tenant_id | product_id | variant_id | branch_id | quantity_change | new_quantity | reference_type | reference_id | notes | user_id | created_at | updated_at }

5. Sales & Customers
customers = { id | tenant_id | name | email | phone | address | tax_number | credit_limit | balance | customer_group | created_at | updated_at | deleted_at }
payment_methods = { id | tenant_id | name | code | type | is_active | settings | created_at | updated_at }
sales = { id | tenant_id | branch_id | invoice_number | customer_id | user_id | subtotal | tax_amount | discount_amount | shipping_amount | total_amount | amount_paid | change_amount | payment_status | status | notes | sale_date | created_at | updated_at | deleted_at }
sales_items = { id | tenant_id | sale_id | product_id | variant_id | quantity | unit_price | cost_price | tax_rate | tax_amount | discount_rate | discount_amount | total_price | notes | created_at | updated_at }
sale_payments = { id | tenant_id | sale_id | payment_method_id | amount | reference | notes | user_id | created_at | updated_at }   
returns = { id | tenant_id | sale_id | customer_id | user_id | return_number | total_amount | status | reason | return_date | created_at | updated_at }
return_items = { id | tenant_id | return_id | sale_item_id | quantity | unit_price | total_price | reason | created_at | updated_at }

6. Purchasing & Suppliers

purchases = { id | tenant_id | supplier_id | branch_id | invoice_number | subtotal | tax_amount | discount_amount | shipping_amount | total_amount | status | notes | purchase_date | expected_delivery_date | created_at | updated_at | deleted_at }
purchase_items = { id | tenant_id | purchase_id | product_id | variant_id | quantity | quantity_received | unit_price | tax_rate | tax_amount | discount_rate | discount_amount | total_price | notes | created_at | updated_at }
purchase_payments = { id | tenant_id | purchase_id | payment_method_id | amount | reference | notes | user_id | created_at | updated_at }

7. Financial Management

accounts = { id | tenant_id | name | type | account_number | opening_balance | current_balance | currency | is_default | is_active | description | created_at | updated_at | deleted_at }
transactions = { id | tenant_id | account_id | type | amount | reference | description | category | user_id | date | created_at | updated_at }
expense_categories = { id | tenant_id | name | description | created_at | updated_at | deleted_at }
expenses = { id | tenant_id | expense_category_id | account_id | amount | reference | description | user_id | date | created_at | updated_at }
tax_rates = { id | tenant_id | name | rate | type | is_inclusive | description | created_at | updated_at }

8. Reporting & Analytics

daily_sales = { id | tenant_id | branch_id | date | total_sales | total_amount | total_tax | total_discount | total_profit | created_at | updated_at }
stock_history = { id | tenant_id | branch_id | product_id | variant_id | date | opening_stock | purchases | sales | adjustments | closing_stock | created_at | updated_at }


9. System & Settings

settings = { id | tenant_id | key | value | created_at | updated_at }
notifications = { id | tenant_id | type | notifiable_type | notifiable_id | data | read_at | created_at | updated_at }
activity_log = { id | tenant_id | log_name | description | subject_type | subject_id | causer_type | causer_id | properties | created_at | updated_at }

===================================================================================================================



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


===================================================================================================================



# Controller and Observer Structure for Multi-Tenant POS System

I'll help you create a comprehensive controller and observer structure for your multi-tenant POS system. We'll follow RESTful conventions and implement best practices for each component.

## Controller Structure

### 1. Core Multi-Tenancy Controllers

```php
// app/Http/Controllers/Tenant/TenantController.php
class TenantController extends Controller
{
    public function index() {}
    public function create() {}
    public function store(Request $request) {}
    public function show(Tenant $tenant) {}
    public function edit(Tenant $tenant) {}
    public function update(Request $request, Tenant $tenant) {}
    public function destroy(Tenant $tenant) {}
    public function deactivate(Tenant $tenant) {}
    public function activate(Tenant $tenant) {}
    public function settings(Tenant $tenant) {}
    public function updateSettings(Request $request, Tenant $tenant) {}
}

// app/Http/Controllers/Tenant/TenantUserController.php
class TenantUserController extends Controller
{
    public function index(Tenant $tenant) {}
    public function create(Tenant $tenant) {}
    public function store(Request $request, Tenant $tenant) {}
    public function show(Tenant $tenant, User $user) {}
    public function updateRole(Request $request, Tenant $tenant, User $user) {}
    public function destroy(Tenant $tenant, User $user) {}
}
```

### 2. User Management Controllers

```php
// app/Http/Controllers/Auth/AuthController.php (extends Laravel Breeze)
// app/Http/Controllers/User/ProfileController.php
class ProfileController extends Controller
{
    public function show() {}
    public function edit() {}
    public function update(ProfileUpdateRequest $request) {}
    public function destroy(Request $request) {}
    public function updatePassword(PasswordUpdateRequest $request) {}
}
```

### 3. Business Structure Controllers

```php
// app/Http/Controllers/Business/BusinessController.php
class BusinessController extends Controller
{
    public function index() {}
    public function create() {}
    public function store(BusinessRequest $request) {}
    public function show(Business $business) {}
    public function edit(Business $business) {}
    public function update(BusinessRequest $request, Business $business) {}
    public function destroy(Business $business) {}
}

// app/Http/Controllers/Business/BranchController.php
class BranchController extends Controller
{
    public function index(Business $business) {}
    public function create(Business $business) {}
    public function store(BranchRequest $request, Business $business) {}
    public function show(Business $business, Branch $branch) {}
    public function edit(Business $business, Branch $branch) {}
    public function update(BranchRequest $request, Business $business, Branch $branch) {}
    public function destroy(Business $business, Branch $branch) {}
    public function setMain(Business $business, Branch $branch) {}
}
```

### 4. Inventory Management Controllers

```php
// app/Http/Controllers/Inventory/CategoryController.php
class CategoryController extends Controller
{
    public function index() {}
    public function create() {}
    public function store(CategoryRequest $request) {}
    public function show(Category $category) {}
    public function edit(Category $category) {}
    public function update(CategoryRequest $request, Category $category) {}
    public function destroy(Category $category) {}
    public function tree() {} // For hierarchical view
}

// app/Http/Controllers/Inventory/BrandController.php
class BrandController extends Controller
{
    // Standard CRUD methods
}

// app/Http/Controllers/Inventory/SupplierController.php
class SupplierController extends Controller
{
    // Standard CRUD methods + additional methods for supplier-specific actions
    public function products(Supplier $supplier) {}
    public function purchases(Supplier $supplier) {}
}

// app/Http/Controllers/Inventory/ProductController.php
class ProductController extends Controller
{
    public function index() {}
    public function create() {}
    public function store(ProductRequest $request) {}
    public function show(Product $product) {}
    public function edit(Product $product) {}
    public function update(ProductRequest $request, Product $product) {}
    public function destroy(Product $product) {}
    public function inventory(Product $product) {}
    public function barcode(Product $product) {}
    public function bulkUpdate(Request $request) {}
}

// app/Http/Controllers/Inventory/ProductVariantController.php
class ProductVariantController extends Controller
{
    public function index(Product $product) {}
    public function create(Product $product) {}
    public function store(ProductVariantRequest $request, Product $product) {}
    public function show(Product $product, ProductVariant $variant) {}
    public function edit(Product $product, ProductVariant $variant) {}
    public function update(ProductVariantRequest $request, Product $product, ProductVariant $variant) {}
    public function destroy(Product $product, ProductVariant $variant) {}
    public function inventory(Product $product, ProductVariant $variant) {}
}

// app/Http/Controllers/Inventory/InventoryLogController.php
class InventoryLogController extends Controller
{
    public function index() {}
    public function show(InventoryLog $log) {}
    public function adjust(Request $request) {} // For manual adjustments
    public function stockReport() {}
    public function lowStockReport() {}
}
```

### 5. Sales & Customers Controllers

```php
// app/Http/Controllers/Sales/CustomerController.php
class CustomerController extends Controller
{
    public function index() {}
    public function create() {}
    public function store(CustomerRequest $request) {}
    public function show(Customer $customer) {}
    public function edit(Customer $customer) {}
    public function update(CustomerRequest $request, Customer $customer) {}
    public function destroy(Customer $customer) {}
    public function sales(Customer $customer) {}
    public function balance(Customer $customer) {}
}

// app/Http/Controllers/Sales/PaymentMethodController.php
class PaymentMethodController extends Controller
{
    // Standard CRUD methods
}

// app/Http/Controllers/Sales/SaleController.php
class SaleController extends Controller
{
    public function index() {}
    public function create() {}
    public function store(SaleRequest $request) {}
    public function show(Sale $sale) {}
    public function edit(Sale $sale) {}
    public function update(SaleRequest $request, Sale $sale) {}
    public function destroy(Sale $sale) {}
    public function print(Sale $sale) {}
    public function email(Sale $sale, Request $request) {}
    public function addPayment(Sale $sale, Request $request) {}
    public function void(Sale $sale) {}
}

// app/Http/Controllers/Sales/SalePaymentController.php
class SalePaymentController extends Controller
{
    public function index(Sale $sale) {}
    public function show(Sale $sale, SalePayment $payment) {}
    public function destroy(Sale $sale, SalePayment $payment) {}
}

// app/Http/Controllers/Sales/ReturnController.php
class ReturnController extends Controller
{
    public function index() {}
    public function create(Sale $sale) {}
    public function store(ReturnRequest $request, Sale $sale) {}
    public function show(Return $return) {}
    public function print(Return $return) {}
}
```

### 6. Purchasing & Suppliers Controllers

```php
// app/Http/Controllers/Purchasing/PurchaseController.php
class PurchaseController extends Controller
{
    public function index() {}
    public function create() {}
    public function store(PurchaseRequest $request) {}
    public function show(Purchase $purchase) {}
    public function edit(Purchase $purchase) {}
    public function update(PurchaseRequest $request, Purchase $purchase) {}
    public function destroy(Purchase $purchase) {}
    public function receive(Purchase $purchase) {}
    public function addPayment(Purchase $purchase, Request $request) {}
}

// app/Http/Controllers/Purchasing/PurchasePaymentController.php
class PurchasePaymentController extends Controller
{
    public function index(Purchase $purchase) {}
    public function show(Purchase $purchase, PurchasePayment $payment) {}
    public function destroy(Purchase $purchase, PurchasePayment $payment) {}
}
```

### 7. Financial Management Controllers

```php
// app/Http/Controllers/Financial/AccountController.php
class AccountController extends Controller
{
    public function index() {}
    public function create() {}
    public function store(AccountRequest $request) {}
    public function show(Account $account) {}
    public function edit(Account $account) {}
    public function update(AccountRequest $request, Account $account) {}
    public function destroy(Account $account) {}
    public function transactions(Account $account) {}
    public function transfer() {}
    public function processTransfer(TransferRequest $request) {}
}

// app/Http/Controllers/Financial/TransactionController.php
class TransactionController extends Controller
{
    public function index() {}
    public function show(Transaction $transaction) {}
    public function destroy(Transaction $transaction) {}
}

// app/Http/Controllers/Financial/ExpenseCategoryController.php
class ExpenseCategoryController extends Controller
{
    // Standard CRUD methods
}

// app/Http/Controllers/Financial/ExpenseController.php
class ExpenseController extends Controller
{
    public function index() {}
    public function create() {}
    public function store(ExpenseRequest $request) {}
    public function show(Expense $expense) {}
    public function edit(Expense $expense) {}
    public function update(ExpenseRequest $request, Expense $expense) {}
    public function destroy(Expense $expense) {}
}

// app/Http/Controllers/Financial/TaxRateController.php
class TaxRateController extends Controller
{
    // Standard CRUD methods
}
```

### 8. Reporting & Analytics Controllers

```php
// app/Http/Controllers/Reports/SalesReportController.php
class SalesReportController extends Controller
{
    public function daily() {}
    public function weekly() {}
    public function monthly() {}
    public function yearly() {}
    public function custom(Request $request) {}
    public function byProduct() {}
    public function byCategory() {}
    public function byCustomer() {}
}

// app/Http/Controllers/Reports/InventoryReportController.php
class InventoryReportController extends Controller
{
    public function stockLevels() {}
    public function stockMovement() {}
    public function valuation() {}
    public function aging() {}
}

// app/Http/Controllers/Reports/FinancialReportController.php
class FinancialReportController extends Controller
{
    public function profitLoss() {}
    public function balanceSheet() {}
    public function cashFlow() {}
    public function expenseAnalysis() {}
}
```

### 9. System & Settings Controllers

```php
// app/Http/Controllers/Settings/SystemSettingsController.php
class SystemSettingsController extends Controller
{
    public function index() {}
    public function update(Request $request) {}
    public function backup() {}
    public function restore() {}
    public function logs() {}
}

// app/Http/Controllers/Settings/NotificationController.php
class NotificationController extends Controller
{
    public function index() {}
    public function markAsRead(Notification $notification) {}
    public function markAllAsRead() {}
    public function destroy(Notification $notification) {}
}
```

## Observer Structure
Observers will help you handle model events consistently across your application. Here's how to structure them:

### 1. Core Multi-Tenancy Observers

```php
// app/Observers/TenantObserver.php
class TenantObserver
{
    public function creating(Tenant $tenant)
    {// Create tenant database}

    public function created(Tenant $tenant)
    {// Create default business/branch}

    public function deleting(Tenant $tenant)
    {// Prevent deletion if has related data}

    public function deleted(Tenant $tenant)
    {// Cleanup tenant database}
}
```

### 2. User Management Observers

```php
// app/Observers/UserObserver.php
class UserObserver
{
    public function creating(User $user)
    {// Set default profile photo}

    public function created(User $user)
    {// Send welcome email}

    public function updating(User $user)
    {// Handle email change verification}

    public function deleted(User $user)
    {// Soft delete related data}
}
```

### 3. Business Structure Observers

```php
// app/Observers/BusinessObserver.php
class BusinessObserver
{
    public function creating(Business $business)
    {// Set default tax number format}

    public function created(Business $business)
    {// Create main branch if not exists}

    public function deleted(Business $business)
    {// Soft delete related branches}
}

// app/Observers/BranchObserver.php
class BranchObserver
{
    public function creating(Branch $branch)
    {// Generate branch code}

    public function saved(Branch $branch)
    {// If set as main, update other branches}
}
```

### 4. Inventory Management Observers

```php
// app/Observers/ProductObserver.php
class ProductObserver
{
    public function creating(Product $product)
    {// Generate SKU if empty}

    public function created(Product $product)
    {// Create default variant if none}

    public function updating(Product $product)
    {// Handle track_inventory change}

    public function deleted(Product $product)
    {// Soft delete variants}
}

// app/Observers/ProductVariantObserver.php
class ProductVariantObserver
{
    public function creating(ProductVariant $variant)
    {// Set default price from product}

    public function saved(ProductVariant $variant)
    {// Update product average price}

    public function deleted(ProductVariant $variant)
    {// Handle inventory adjustments}
}

// app/Observers/InventoryLogObserver.php
class InventoryLogObserver
{
    public function created(InventoryLog $log)
    {// Trigger low stock notifications}
}
```

### 5. Sales & Customers Observers

```php
// app/Observers/SaleObserver.php
class SaleObserver
{
    public function creating(Sale $sale)
    {// Set default status}

    public function created(Sale $sale)
    {// Update customer balance}

    public function updating(Sale $sale)
    {// Handle status changes}

    public function deleted(Sale $sale)
    {// Adjust customer balance}
}

// app/Observers/SalePaymentObserver.php
class SalePaymentObserver
{
    public function created(SalePayment $payment)
    {// Create transaction}

    public function deleted(SalePayment $payment)
    {// Remove transaction}
}
```

### 6. Purchasing Observers

```php
// app/Observers/PurchaseObserver.php
class PurchaseObserver
{
    public function creating(Purchase $purchase)
    {// Generate invoice number}

    public function created(Purchase $purchase)
    {// Create inventory logs when received}

    public function updated(Purchase $purchase)
    {// Handle received quantity changes}
}
```

### 7. Financial Observers

```php
// app/Observers/AccountObserver.php
class AccountObserver
{
    public function saving(Account $account)
    {// Ensure only one default account per type}

    public function deleted(Account $account)
    {// Prevent deletion if has transactions}
}

// app/Observers/TransactionObserver.php
class TransactionObserver
{
    public function created(Transaction $transaction)
    {// Update account balance}

    public function deleted(Transaction $transaction)
    {// Reverse account balance}
}
```

## Implementation Steps

1. Create all the controllers in their respective directories:
   ```
   app/Http/Controllers/
   ├── Tenant/
   ├── Auth/
   ├── User/
   ├── Business/
   ├── Inventory/
   ├── Sales/
   ├── Purchasing/
   ├── Financial/
   ├── Reports/
   └── Settings/
   ```
2. Create the observers directory and files:
   ```
   app/Observers/
   ├── TenantObserver.php
   ├── UserObserver.php
   ├── BusinessObserver.php
   ├── ProductObserver.php
   ├── SaleObserver.php
   ├── PurchaseObserver.php
   └── AccountObserver.php
   ```
3. Register observers in your `AppServiceProvider`:
```php
public function boot()
{
    Tenant::observe(TenantObserver::class);
    User::observe(UserObserver::class);
    Business::observe(BusinessObserver::class);
    Product::observe(ProductObserver::class);
    Sale::observe(SaleObserver::class);
    Purchase::observe(PurchaseObserver::class);
    Account::observe(AccountObserver::class);
    // Register other observers
}
```
4. Create corresponding form request classes for validation:
   ```
   app/Http/Requests/
   ├── TenantRequest.php
   ├── BusinessRequest.php
   ├── ProductRequest.php
   ├── SaleRequest.php
   └── etc.
   ```
This structure provides a solid foundation for multi-tenant POS system, following RESTful conventions and separating concerns appropriately. Each controller handles a specific resource, and observers take care of model events consistently across your application.

===================================================================================================================
// app/Models/Tenant.php
class Tenant extends Model
{
    use HasFactory, SoftDeletes;
    protected $casts = [
        'settings' => 'json',
        'is_active' => 'boolean',
    ];
    public function users()
    {return $this->belongsToMany(User::class, 'tenant_users')->withPivot('role')->withTimestamps();}
    public function businesses()
    {return $this->hasMany(Business::class);}

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
    {return $this->belongsToMany(Tenant::class, 'tenant_users')->withPivot('role')->withTimestamps();}
    public function sales()
    {return $this->hasMany(Sale::class);}
    public function purchases()
    {return $this->hasMany(Purchase::class);}
}
// app/Models/Business.php
class Business extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function branches()
    {return $this->hasMany(Branch::class);}
}
// app/Models/Branch.php
class Branch extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function business()
    {return $this->belongsTo(Business::class);}
    public function sales()
    {return $this->hasMany(Sale::class);}
    public function purchases()
    {return $this->hasMany(Purchase::class);}
}
// app/Models/Category.php
class Category extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function parent()
    {return $this->belongsTo(Category::class, 'parent_id');}
    public function children()
    {return $this->hasMany(Category::class, 'parent_id');}
    public function products()
    {return $this->hasMany(Product::class);}
}
// app/Models/Brand.php
class Brand extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}

    public function products()
    {return $this->hasMany(Product::class);}
}
// app/Models/Supplier.php
class Supplier extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function products()
    {return $this->hasMany(Product::class);}
    public function purchases()
    {return $this->hasMany(Purchase::class);}
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
    {return $this->belongsTo(Tenant::class);}
    public function category()
    {return $this->belongsTo(Category::class);}
    public function brand()
    {return $this->belongsTo(Brand::class);}
    public function supplier()
    {return $this->belongsTo(Supplier::class);}
    public function variants()
    {return $this->hasMany(ProductVariant::class);}
    public function saleItems()
    {return $this->hasMany(SaleItem::class);}
    public function purchaseItems()
    {return $this->hasMany(PurchaseItem::class);}
}


// app/Models/ProductVariant.php
class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function product()
    {return $this->belongsTo(Product::class);}
    public function saleItems()
    {return $this->hasMany(SaleItem::class);}
    public function purchaseItems()
    {return $this->hasMany(PurchaseItem::class);}
    public function inventoryLogs()
    {return $this->hasMany(InventoryLog::class);}
}
// app/Models/InventoryLog.php
class InventoryLog extends Model
{
    use HasFactory;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function product()
    {return $this->belongsTo(Product::class);}
    public function variant()
    {return $this->belongsTo(ProductVariant::class);}
    public function branch()
    {return $this->belongsTo(Branch::class);}
    public function user()
    {return $this->belongsTo(User::class);}
}
// app/Models/Customer.php
class Customer extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function sales()
    {return $this->hasMany(Sale::class);}
    public function returns()
    {return $this->hasMany(Return::class);}
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
    {return $this->belongsTo(Tenant::class);}
    public function salePayments()
    {return $this->hasMany(SalePayment::class);}
    public function purchasePayments()
    {return $this->hasMany(PurchasePayment::class);}
}
// app/Models/Sale.php
class Sale extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function branch()
    {return $this->belongsTo(Branch::class);}
    public function customer()
    {return $this->belongsTo(Customer::class);}
    public function user()
    {return $this->belongsTo(User::class);}
    public function items()
    {return $this->hasMany(SaleItem::class);}
    public function payments()
    {return $this->hasMany(SalePayment::class);}
    public function returns()
    {return $this->hasMany(Return::class);}
}
// app/Models/SaleItem.php
class SaleItem extends Model
{
    use HasFactory;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function sale()
    {return $this->belongsTo(Sale::class);}
    public function product()
    {return $this->belongsTo(Product::class);}
    public function variant()
    {return $this->belongsTo(ProductVariant::class);}
    public function returnItems()
    {return $this->hasMany(ReturnItem::class);}
}
// app/Models/SalePayment.php
class SalePayment extends Model
{
    use HasFactory;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function sale()
    {return $this->belongsTo(Sale::class);}
    public function paymentMethod()
    {return $this->belongsTo(PaymentMethod::class);}
    public function user()
    {return $this->belongsTo(User::class);}
}
// app/Models/Return.php
class Return extends Model
{
    use HasFactory;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function sale()
    {return $this->belongsTo(Sale::class);}
    public function customer()
    {return $this->belongsTo(Customer::class);}
    public function user()
    {return $this->belongsTo(User::class);}
    public function items()
    {return $this->hasMany(ReturnItem::class);}
}
// app/Models/ReturnItem.php
class ReturnItem extends Model
{
    use HasFactory;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function return()
    {return $this->belongsTo(Return::class);}
    public function saleItem()
    {return $this->belongsTo(SaleItem::class);}
}
// app/Models/Purchase.php
class Purchase extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function supplier()
    {return $this->belongsTo(Supplier::class);}
    public function branch()
    {return $this->belongsTo(Branch::class);}
    public function items()
    {return $this->hasMany(PurchaseItem::class);}
    public function payments()
    {return $this->hasMany(PurchasePayment::class);}
}
// app/Models/PurchaseItem.php
class PurchaseItem extends Model
{
    use HasFactory;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function purchase()
    {return $this->belongsTo(Purchase::class);}
    public function product()
    {return $this->belongsTo(Product::class);}
    public function variant()
    {return $this->belongsTo(ProductVariant::class);}
}
// app/Models/PurchasePayment.php
class PurchasePayment extends Model
{
    use HasFactory;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function purchase(){return $this->belongsTo(Purchase::class);}
    public function paymentMethod(){return $this->belongsTo(PaymentMethod::class);}
    public function user(){return $this->belongsTo(User::class);}
}
// app/Models/Account.php
class Account extends Model
{
    use HasFactory, SoftDeletes;
    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];
    public function tenant(){return $this->belongsTo(Tenant::class);}
    public function transactions(){return $this->hasMany(Transaction::class);}
    public function expenses(){return $this->hasMany(Expense::class);}
}
// app/Models/Transaction.php
class Transaction extends Model
{
    use HasFactory;
    public function tenant(){return $this->belongsTo(Tenant::class);}
    public function account(){return $this->belongsTo(Account::class);}
    public function user(){return $this->belongsTo(User::class);}
}
// app/Models/ExpenseCategory.php
class ExpenseCategory extends Model
{
    use HasFactory, SoftDeletes;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function expenses(){return $this->hasMany(Expense::class);}
}
// app/Models/Expense.php
class Expense extends Model
{
    use HasFactory;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function category()
    {return $this->belongsTo(ExpenseCategory::class);}
    public function account()
    {return $this->belongsTo(Account::class);}
    public function user(){return $this->belongsTo(User::class);}
}
// app/Models/TaxRate.php
class TaxRate extends Model
{
    use HasFactory;
    protected $casts = [
        'is_inclusive' => 'boolean',
    ];
    public function tenant(){return $this->belongsTo(Tenant::class);}
}
// app/Models/DailySale.php
class DailySale extends Model
{
    use HasFactory;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function branch()
    {return $this->belongsTo(Branch::class);}
}
// app/Models/StockHistory.php
class StockHistory extends Model
{
    use HasFactory;
    public function tenant()
    {return $this->belongsTo(Tenant::class);}
    public function branch(){return $this->belongsTo(Branch::class);}
    public function product(){return $this->belongsTo(Product::class);}
    public function variant(){return $this->belongsTo(ProductVariant::class);}
}
// app/Models/Setting.php
class Setting extends Model
{
    use HasFactory;
    public function tenant(){return $this->belongsTo(Tenant::class);}
}
// app/Models/Notification.php
class Notification extends Model
{
    use HasFactory;
    public function tenant(){return $this->belongsTo(Tenant::class);}
    public function notifiable(){return $this->morphTo();}
}
// app/Models/ActivityLog.php
class ActivityLog extends Model
{
    use HasFactory;
    protected $casts = [
        'properties' => 'collection',
    ];
    public function tenant(){return $this->belongsTo(Tenant::class);}
    public function subject(){return $this->morphTo();}
    public function causer(){return $this->morphTo();}
}
