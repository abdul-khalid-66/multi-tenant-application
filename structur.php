Here’s the **structured documentation** for your database tables, including **column names**, **data types**, and
**purpose** for each column. This will help you understand the structure and ensure consistency in your database design.
### **0. Users Table**
Stores Users table.
| **Column** | name | email | password |

### **1. Products Table**
Stores product details.

| **Column** | **Data Type** | **Purpose** |
|-------------------|-----------------------|-----------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each product. |
| `name` | `VARCHAR(255)` | Name of the product. |
| `image` | `TEXT` | Image URL or path for the product. |
| `status` | `VARCHAR(20)` | Availability status (e.g., "available", "out of stock"). |
| `description` | `TEXT` | Description of the product. |
| `category_id` | `INT` | Links to the category (`categories.id`). |
| `reorder_level` | `INT` | Minimum stock level to trigger restock alerts. |
| `supplier_id` | `INT` | Links to the supplier (`suppliers.id`). |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---

### **2. Categories Table**
Stores product and expense categories.

| **Column** | **Data Type** | **Purpose** |
|-------------------|-----------------------|-------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each category. |
| `category_name` | `VARCHAR(100)` | Name of the category (e.g., "Engine Parts"). |
| `subcategory` | `VARCHAR(100)` | Subcategory name (e.g., "Oil Filters"). |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---


### **3. Product_Variants Table**
Stores variants of products (e.g., sizes, colors).

| **Column** | **Data Type** | **Purpose** |
|-------------------|-----------------------|-----------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each variant. |
| `product_id` | `INT` | Links to the product (`products.id`). |
| `name` | `VARCHAR(100)` | Name of the variant (e.g., "Large"). |
| `sku` | `VARCHAR(100)` | Stock Keeping Unit (unique identifier for the variant). |
| `price_sale` | `DECIMAL(10,2)` | Selling price of the variant. |
| `price_cost` | `DECIMAL(10,2)` | Cost price of the variant. |
| `status` | `VARCHAR(20)` | Availability status (e.g., "available", "out of stock"). |
| `stock_quantity` | `INT` | Current stock quantity. |
| `weight` | `DECIMAL(10,2)` | Weight of the variant (for shipping calculations). |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---


### **4. Suppliers Table**
Tracks supplier details.

| **Column** | **Data Type** | **Purpose** |
|---------------|-----------------------|-------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each supplier. |
| `name` | `VARCHAR(255)` | Name of the supplier. |
| `contact` | `VARCHAR(100)` | Contact information of the supplier. |
| `address` | `TEXT` | Address of the supplier. |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---

### **5. Customers Table**
Tracks customer details.

| **Column** | **Data Type** | **Purpose** |
|---------------|-----------------------|-------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each customer. |
| `name` | `VARCHAR(255)` | Name of the customer. |
| `contact` | `VARCHAR(100)` | Contact information of the customer. |
| `address` | `TEXT` | Address of the customer. |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---

### **6. Inventory_Logs Table**
Tracks inventory changes.

| **Column** | **Data Type** | **Purpose** |
|---------------|---------------|---------------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each inventory log. |
| `product_id` | `INT` | Links to the product (`products.id`). |
| `variant_id` | `INT` | Links to the product variant (`product_variants.id`). |
| `old_stock` | `INT` | Stock quantity before the change. |
| `new_stock` | `INT` | Stock quantity after the change. |
| `reason` | `VARCHAR(100)`| Reason for the change (e.g., "sale", "restock", "return"). |
| `date` | `TIMESTAMP` | Date of the inventory change. |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---


### **7. Investments Table**
Tracks investments made into the business.

| **Column** | **Data Type** | **Purpose** |
|--------------|------------------------|-------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each investment. |
| `amount` | `DECIMAL(10,2)` | Amount invested. |
| `type` | `VARCHAR(50)` | Type of investment (e.g., "initial", "additional"). |
| `description`| `TEXT` | Description of the investment. |
| `date` | `TIMESTAMP` | Date of the investment. |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable) | Timestamp when the record was deleted (soft delete). |

---




### **8. Sales Table**
Tracks sales transactions.

| **Column** | **Data Type** | **Purpose** |
|-------------------|-----------------------|-------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each sale. |
| `product_id` | `INT` | Links to the product (`products.id`). |
| `variant_id` | `INT` | Links to the product variant (`product_variants.id`). |
| `invoice_no` | `VARCHAR(100)` | Unique invoice number for the sale. |
| `total_amount` | `DECIMAL(10,2)` | Total amount of the sale. |
| `cost_price` | `DECIMAL(10,2)` | Total cost price of the sold items. |
| `date` | `TIMESTAMP` | Date of the sale. |
| `customer_id` | `INT` | Links to the customer (`customers.id`). |
| `payment_status` | `VARCHAR(20)` | Payment status (e.g., "paid", "pending"). |
| `payment_method` | `VARCHAR(50)` | Payment method (e.g., "cash", "credit card"). |
| `discount` | `DECIMAL(10,2)` | Discount applied to the sale. |
| `tax` | `DECIMAL(10,2)` | Tax applied to the sale. |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---






### **9. Sale_Details Table**
Tracks itemized details of sales.

| **Column** | **Data Type** | **Purpose** |
|-------------------|-----------------------|-------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each sale detail. |
| `sale_id` | `INT` | Links to the sale (`sales.id`). |
| `product_id` | `INT` | Links to the product (`products.id`). |
| `variant_id` | `INT` | Links to the product variant (`product_variants.id`). |
| `quantity` | `INT` | Quantity sold. |
| `cost_price` | `DECIMAL(10,2)` | Cost price per unit. |
| `sell_price` | `DECIMAL(10,2)` | Selling price per unit. |
| `unit` | `VARCHAR(50)` | Unit of measurement (e.g., "liters", "pieces"). |
| `line_item_note` | `TEXT` | Optional notes for the item. |
| `total_price` | `DECIMAL(10,2)` | Total price for the item (`quantity * sell_price`). |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---



### **10. Returns Table**
Tracks customer return requests.

| **Column** | **Data Type** | **Purpose** |
|-----------------------|-----------------------|-------------------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each return. |
| `sale_id` | `INT` | Links to the original sale (`sales.id`). |
| `customer_id` | `INT` | Links to the customer (`customers.id`). |
| `return_date` | `TIMESTAMP` | Date and time of the return request. |
| `reason` | `TEXT` | Reason for the return (e.g., "defective product"). |
| `status` | `VARCHAR(20)` | Status of the return (e.g., "pending", "approved", "rejected"). |
| `total_refund_amount` | `DECIMAL(10,2)` | Total refund amount for the return. |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---




### **11. Return_Details Table**
Tracks itemized details of returned products.

| **Column** | **Data Type** | **Purpose** |
|----------------------------|-----------------------|-----------------------------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each return detail. |
| `return_id` | `INT` | Links to the return request (`returns.id`). |
| `product_id` | `INT` | Links to the product (`products.id`). |
| `variant_id` | `INT` | Links to the product variant (`product_variants.id`). |
| `quantity_returned` | `INT` | Number of units returned. |
| `refund_amount_per_unit` | `DECIMAL(10,2)` | Refund amount per unit. |
| `total_refund_amount` | `DECIMAL(10,2)` | Total refund amount for this item (`quantity_returned *
refund_amount_per_unit`). |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---

### **12. Expenses Table**
Tracks business expenses.

| **Column** | **Data Type** | **Purpose** |
|------------------------|-----------------------|-----------------------------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each expense. |
| `amount` | `DECIMAL(10,2)` | Amount of the expense. |
| `category` | `VARCHAR(50)` | Category of the expense (e.g., "rent", "utilities"). |
| `description` | `TEXT` | Description of the expense. |
| `date` | `TIMESTAMP` | Date of the expense. |
| `verified_by` | `VARCHAR(100)` | User/staff who verified the expense. |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---

### **13. Cash_in_Hand_Details Table**
Tracks all cash movements in the business.

| **Column** | **Data Type** | **Purpose** |
|------------------------|-----------------------|-----------------------------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each cash movement. |
| `date` | `TIMESTAMP` | Date of the cash movement. |
| `amount` | `DECIMAL(10,2)` | Amount added (positive) or deducted (negative). |
| `transaction_type` | `VARCHAR(50)` | Type of transaction (e.g., "sale", "investment", "expense", "refund"). |
| `reference_id` | `INT` | Links to the source table (`sales.id`, `investments.id`, `expenses.id`, etc.). |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---

### **14. Profit_Losses Table**
Tracks profit and loss details.

| **Column** | **Data Type** | **Purpose** |
|------------------------|-----------------------|-----------------------------------------------------------------------------|
| `id` | `INT` (Primary Key) | Unique identifier for each profit/loss entry. |
| `sale_id` | `INT` | Links to the sale (`sales.id`). |
| `profit` | `DECIMAL(10,2)` | Profit amount. |
| `loss` | `DECIMAL(10,2)` | Loss amount. |
| `description` | `TEXT` | Reason for profit/loss (e.g., "bulk discount", "damaged goods"). |
| `category` | `VARCHAR(50)` | Category of profit/loss (e.g., "operational", "sales"). |
| `verified_by` | `VARCHAR(100)` | User/staff who verified the entry. |
| `date` | `TIMESTAMP` | Date of the profit/loss entry. |
| `created_at` | `TIMESTAMP` | Timestamp when the record was created. |
| `updated_at` | `TIMESTAMP` | Timestamp when the record was last updated. |
| `deleted_at` | `TIMESTAMP` (Nullable)| Timestamp when the record was deleted (soft delete). |

---

### **Key Notes:**
1. **Soft Delete:** The `deleted_at` column is used for soft deletes, allowing you to retain historical data without
permanently deleting records.
2. **Foreign Keys:** Relationships between tables are maintained using foreign keys (e.g



Core Modules:

Products Management
    ✔️ All Products  
    ✔️ Add New Product  
    ✔️ Product Categories  
    ✔️ Product Variants  
    ✔️ Inventory Levels  
Sales Management
    ✔️ Sales Transactions  
    ✔️ Invoices  
    ✔️ Customers  
    🔄 Returns/Refunds  
        ↳ 📝 Return Requests Processing  
        ↳ ✅ Refund Approval Workflow  
        📊 Return Reason Analytics *(New)*  
    � Discounts/Promotions  
        ↳ 🎟️ Coupon Code Management *(New)*  
        ↳ 🎁 Seasonal Offers Tracking *(New)*  
    📦 Inventory Control  
        ↳ ⚡ Automatic Stock Deduction *(New)*  
        ↳ 🔴 Low Stock Warnings  

Stock Management 
    🔔 Reorder Alerts  
    ↳ 🧠 Smart Alerts *(New)*  
    📝 Inventory Logs  
    ↳ 🔍 Filter by Reason *(New)*  
    ↳ ⏳ Stock Adjustment History *(New)*  

Supplier Management
    🚚 Stock Transfers  
    ↳ 📝 Purchase Order Generation *(New)*  
    💳 Financial Tracking  
    ↳ 📜 Supplier Payment History *(New)*  
    ↳ ⚖️ Outstanding Balances *(New)*  
    📈 Supplier Performance Dashboard *(New)*  
    📊 Revenue Reports  
    💹 Profit/Loss Analysis  
    💸 Expenses Tracking  
    💰 Investments  
    💵 Cash Flow  

Reporting & Analytics
    📈 Sales Reports  
    📦 Inventory Reports  
    👥 Customer Reports  
    💲 Financial Reports  
    ⚙️ Operational Reports *(New)*  
        ↳ ⏱️ Fulfillment Time Reports  
        ↳ 🔄 Return Processing Efficiency  
    🔮 Predictive Analytics *(New)*  
        ↳ 📅 Demand Forecasting  
        ↳ 🛒 Reorder Suggestions  
    🏆 Product Performance  
    ⚙️ System Administration  

User Management
    🛡️ Roles & Permissions  
    ⚙️ System Settings  
    💾 Backup/Restore  
    📜 Activity Logs  
        ↳ 🔍 Financial Changes Audit Trail *(New)*  
        ↳ 🚨 Sensitive Action Alerts *(New)*  





<!-- Mukammal Business Hisaab Kitab - Database ke Hisab se

1. Maliyat ka Khulaasa Hisaab
Gross Revenue:
Pure saal ki kamaai (Sales Table ke total_amount ka jama).

Net Revenue (After Returns):
Gross Revenue minus wapis li gayi cheezon ki raqam (Returns Table ke total_refund_amount).

Cost of Goods Sold (COGS):
Bechi gayi cheezon ki asal qeemat (Sale_Details ke cost_price × quantity ka jama).

Gross Profit:
Net Revenue minus COGS.

2. Adaigi Halat ki Tafseel
Paid Amounts:
Poori ada ki gayi sales (payment_status = "paid").

Partial Payments:
Adai ka kuch hissa (payment_status = "partial").

Pending Payments:
Baqi ada (payment_status = "pending").

3. Maal ka Qeemat Lagana
Current Inventory Value:
Har product/variant ke stock_quantity × unki price_cost.

Inventory Turnover Ratio:
Saal mein kitni dafa maal bikta hai (COGS ÷ average inventory value).

4. Naqdi Flow Ka Tajzia
Cash In Hand:
Cash_in_Hand_Details ke amount ka total.

Cash Flow Breakdown:
Har transaction type (sale, investment, etc.) ke hisab se naqdi ka aana/jaana.

5. Faida/Nuqssan ka Bayan
Net Profit/Loss:
Gross Profit minus expenses aur investments.

Profit/Loss by Category:
Har category ke products se hone wala faida/nuqsaan.

6. Chhoot aur Tax ka Hisaab
Total Discounts Given:
Tamam sales par di gayi chhoot (sales.discount ka jama).

Total Taxes Collected:
Sales par jama kiye gaye tax (sales.tax ka jama).

7. Investment Ka Tajzia
Total Investments:
Investments Table ke amount ka jama.

Investment vs Profit:
Investments aur net profit ka muqabla.

8. Customer Tajzia
Top Customers by Spending:
Sabse zyada kharch karne wale customers (sales.total_amount ke hisab se).

9. Product Ka Performance
Top Selling Products:
Sabse zyada bikne wale products/variants (sale_details.quantity ke hisab se).

10. Wapisii Ka Tajzia
Return Rate by Product:
Har product ka wapis hone ka percentage (returned quantity ÷ total sold quantity).

11. Supplier Ka Performance
On-time Delivery Rate:
Supplier ne kitni baar time par maal pohunchaya (inventory_logs.reason = "restock" ke entries ke sath).

Total Spend per Supplier:
Har supplier se khareede gaye maal ki qeemat (products.supplier_id + product_variants.price_cost).

12. Customer Loyalty ke Nuqta
Repeat Purchase Rate:
Ek customer kitni baar dobara khareedta hai (sales.customer_id ki history dekho).

Customer Lifetime Value:
Ek customer ne zindagi bhar mein kitni kamaai di (sales.total_amount ka jama).

13. Stock Harkat Ka Tajzia
Fast vs Slow-Moving Items:
Jaldi bikne wale aur dheere bikne wale items (inventory_logs ki frequency dekho).

Stockout Frequency:
Kitni baar kisi product ka stock khatam hua (inventory_logs.reason = "sale" jab stock 0 ho).

14. Qeemat ki Behtari
Price Change Impact:
Qeemat badalne ka asar bikri par (product_variants.price_sale ki history vs sales).

Cost vs Sale Price Margin:
Har variant par kitna faida (price_sale - price_cost).

15. Kaam ki Kaifiyat
Average Order Fulfillment Time:
Order dene aur maal update hone ka waqt (sales.created_at vs inventory_logs timestamps).

Return Processing Time:
Wapisii ka application aur uska hal hone ka waqt (returns.return_date vs status update).

16. Category/Segment Ka Tajzia
Profitability by Category:
Har category ke products se kitna faida hua.

Subcategory Comparison:
Engine oil vs filters vs tires ka performance.

17. Tax aur Chhoot ka Asar
Discount Effectiveness:
Chhoot dene se bikri badi ya nahi? (sales.discount vs bikri ka trend).

Tax Liability Forecasting:
Agle mahine/saal kitna tax dena parega (sales.tax ka hisaab).

18. Maal ki Sehat
Days of Inventory Remaining:
Abhi kitne din ka maal bacha hai (current stock ÷ rozana bikri).

Dead Stock Identification:
Woh items jo lambay arse se nahi biktay (X din mein koi sale nahi).

19. Users ki Harkat
Most Active Staff:
Kaunse employees ne sabse zyada sales/returns kiye (sales/returns tables ke created_by).

Peak Usage Times:
System kab zyada use hota hai (created_at timestamps ka analysis).

20. Future ke Andazay
Demand Forecasting:
Agle mahine mein kitna maal chahiye hoga (purane sales + mausam ka asar).

Reorder Timing Suggestions:
Kab naya maal mangwana chahiye (inventory_logs ke trends se).

Tameer ke Mashwaray:

Har hisaab ko mahine/saal ke hisab se track karo.

Automated reports banane ke liye dashboards istemal karo.

Alerts set karo (jaise stock kam hone par ya tax deadlines).
<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Return;
use App\Models\ProductVariant;
use App\Models\CashInHandDetail;
use App\Models\ProfitLoss;
use App\Models\Investment;

class BusinessMetricsService
{
    public function getGrossRevenue()
    {
        return Sale::whereNotNull('payment_status')
                 ->where('payment_status', '!=', 'pending')
                 ->sum('total_amount');
    }

    public function getNetRevenue()
    {
        $grossSales = Sale::sum('total_amount');
        $returns = Return::sum('total_refund_amount');
        
        return $grossSales - $returns;
    }

    public function getInventoryValue()
    {
        return ProductVariant::sum(\DB::raw('stock_quantity * price_cost'));
    }

    public function getCashPosition()
    {
        return CashInHandDetail::sum('amount');
    }

    public function getProfitLossStatement()
    {
        return [
            'gross_profit' => $this->getGrossProfit(),
            'expenses' => $this->getTotalExpenses(),
            'net_profit' => $this->getNetProfit(),
            'roi' => $this->getROI()
        ];
    }

    // Add all other calculation methods here...
}
Then use it in your controller:

php
Copy
public function dashboard()
{
    $metrics = new BusinessMetricsService();
    
    return view('dashboard', [
        'grossRevenue' => $metrics->getGrossRevenue(),
        'netRevenue' => $metrics->getNetRevenue(),
        'inventoryValue' => $metrics->getInventoryValue(),
        'cashPosition' => $metrics->getCashPosition(),
        'profitLoss' => $metrics->getProfitLossStatement()
    ]);
}
This comprehensive approach will give you complete visibility into all aspects of your business performance. Would you like me to elaborate on any specific calculation or visualization approach? -->























suppliers/
    │       ├── stock_transfers/
    │       │   ├── index.blade.php
    │       │   └── show.blade.php
    │       │
    │       ├── purchase_orders/
    │       │   ├── index.blade.php
    │       │   ├── create.blade.php
    │       │   ├── show.blade.php
    │       │   └── edit.blade.php
    │       │
    │       ├── financial_tracking/
    │       │   ├── payments/
    │       │   │   ├── index.blade.php
    │       │   │   └── show.blade.php
    │       │   │
    │       │   └── balances/
    │       │       ├── index.blade.php
    │       │       └── show.blade.php
    │       │
    │       ├── performance/
    │       │   └── dashboard.blade.php
    │       │
    │       └── financial_reports/
    │           ├── revenue.blade.php
    │           ├── profit_loss.blade.php
    │           ├── expenses.blade.php
    │           ├── investments.blade.php
    │           └── cash_flow.blade.php



app/
└── Http/
    └── Controllers/
        └── App/
            └── SupplierManagement/
            |   ├── StockTransferController.php
            |   ├── PurchaseOrderController.php
            |   ├── FinancialTrackingController.php
            |   ├── SupplierPerformanceController.php
            |   └── FinancialReportsController.php
            └──suppliercontorller.php    