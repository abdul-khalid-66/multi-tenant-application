Here’s the **structured documentation** for your database tables, including **column names**, **data types**, and
**purpose** for each column. This will help you understand the structure and ensure consistency in your database design.
### **0. Users Table**
Stores Users Details.
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

We Done this all product management
Products Management

All Products

Add New Product

Product Categories

Product Variants

Inventory Levels


Now We work on salase management
Sales Management

Sales Transactions

Invoices

Customers

Returns/Refunds

Discounts/Promotions

Inventory Control



Stock Management

Reorder Alerts

Inventory Logs



Supplier Management

Stock Transfers

Financial Tracking

Revenue Reports

Profit/Loss Analysis

Expenses Tracking

Investments

Cash Flow

Reporting & Analytics

Sales Reports

Inventory Reports

Customer Reports

Financial Reports

Product Performance

System Administration




User Management

Roles & Permissions

System Settings

Backup/Restore

Activity Logs



















<!-- Complete Business Activity Calculations Based on Your Database
Based on your database structure, here's how to calculate all key business metrics:

1. Financial Overview Calculations
Gross Revenue
sql
Copy
SELECT SUM(total_amount) AS gross_revenue 
FROM sales 
WHERE payment_status != 'pending' 
AND deleted_at IS NULL;
Net Revenue (After Returns)
sql
Copy
SELECT 
    (SELECT SUM(total_amount) FROM sales WHERE deleted_at IS NULL) - 
    (SELECT COALESCE(SUM(total_refund_amount), 0) FROM returns WHERE deleted_at IS NULL) 
AS net_revenue;
Cost of Goods Sold (COGS)
sql
Copy
SELECT SUM(sd.quantity * pv.price_cost) AS cogs
FROM sale_details sd
JOIN product_variants pv ON sd.variant_id = pv.id
JOIN sales s ON sd.sale_id = s.id
WHERE s.deleted_at IS NULL;
Gross Profit
sql
Copy
SELECT 
    (SELECT SUM(total_amount) FROM sales WHERE deleted_at IS NULL) - 
    (SELECT SUM(sd.quantity * pv.price_cost) 
     FROM sale_details sd 
     JOIN product_variants pv ON sd.variant_id = pv.id
     JOIN sales s ON sd.sale_id = s.id
     WHERE s.deleted_at IS NULL)
AS gross_profit;
2. Payment Status Breakdown
Paid Amounts
sql
Copy
SELECT SUM(total_amount) AS paid_amount
FROM sales
WHERE payment_status = 'paid'
AND deleted_at IS NULL;
Partial Payments
sql
Copy
SELECT 
    SUM(total_amount) AS partial_invoiced,
    (SELECT SUM(amount) FROM payments WHERE deleted_at IS NULL) AS partial_received,
    SUM(total_amount) - (SELECT SUM(amount) FROM payments WHERE deleted_at IS NULL) AS partial_outstanding
FROM sales
WHERE payment_status = 'partial'
AND deleted_at IS NULL;
Pending Payments
sql
Copy
SELECT SUM(total_amount) AS pending_amount
FROM sales
WHERE payment_status = 'pending'
AND deleted_at IS NULL;
3. Inventory Valuation
Current Inventory Value
sql
Copy
SELECT SUM(stock_quantity * price_cost) AS inventory_value
FROM product_variants
WHERE deleted_at IS NULL;
Inventory Turnover Ratio
sql
Copy
SELECT 
    (SELECT SUM(sd.quantity * pv.price_cost) 
     FROM sale_details sd 
     JOIN product_variants pv ON sd.variant_id = pv.id
     JOIN sales s ON sd.sale_id = s.id
     WHERE s.deleted_at IS NULL) / 
    NULLIF((SELECT AVG(stock_quantity * price_cost) 
           FROM product_variants 
           WHERE deleted_at IS NULL), 0)
AS inventory_turnover;
4. Cash Flow Analysis
Cash In Hand
sql
Copy
SELECT SUM(amount) AS cash_in_hand
FROM cash_in_hand_details
WHERE deleted_at IS NULL;
Cash Flow Breakdown
sql
Copy
SELECT 
    transaction_type,
    SUM(CASE WHEN amount > 0 THEN amount ELSE 0 END) AS cash_in,
    SUM(CASE WHEN amount < 0 THEN amount ELSE 0 END) AS cash_out,
    SUM(amount) AS net_cash_flow
FROM cash_in_hand_details
WHERE deleted_at IS NULL
GROUP BY transaction_type;
5. Profit & Loss Statement
Net Profit/Loss
sql
Copy
SELECT 
    (SELECT SUM(profit) FROM profit_losses WHERE deleted_at IS NULL) AS total_profit,
    (SELECT SUM(loss) FROM profit_losses WHERE deleted_at IS NULL) AS total_loss,
    (SELECT SUM(profit) - SUM(loss) FROM profit_losses WHERE deleted_at IS NULL) AS net_profit
FROM profit_losses
LIMIT 1;
Profit/Loss by Category
sql
Copy
SELECT 
    category,
    SUM(profit) AS profit,
    SUM(loss) AS loss,
    SUM(profit) - SUM(loss) AS net
FROM profit_losses
WHERE deleted_at IS NULL
GROUP BY category;
6. Discounts & Taxes
Total Discounts Given
sql
Copy
SELECT SUM(discount) AS total_discounts
FROM sales
WHERE deleted_at IS NULL;
Total Taxes Collected
sql
Copy
SELECT SUM(tax) AS total_taxes
FROM sales
WHERE deleted_at IS NULL;
7. Investment Analysis
Total Investments
sql
Copy
SELECT SUM(amount) AS total_investment
FROM investments
WHERE deleted_at IS NULL;
Investment vs Profit
sql
Copy
SELECT 
    (SELECT SUM(amount) FROM investments WHERE deleted_at IS NULL) AS total_investment,
    (SELECT SUM(profit) - SUM(loss) FROM profit_losses WHERE deleted_at IS NULL) AS net_profit,
    ((SELECT SUM(profit) - SUM(loss) FROM profit_losses WHERE deleted_at IS NULL) / 
    NULLIF((SELECT SUM(amount) FROM investments WHERE deleted_at IS NULL), 0) * 100 
    AS roi_percentage;
8. Customer Analysis
Top Customers by Spending
sql
Copy
SELECT 
    c.name,
    c.contact,
    SUM(s.total_amount) AS total_spent,
    COUNT(s.id) AS transaction_count
FROM sales s
JOIN customers c ON s.customer_id = c.id
WHERE s.deleted_at IS NULL
GROUP BY c.id, c.name, c.contact
ORDER BY total_spent DESC
LIMIT 10;
9. Product Performance
Top Selling Products
sql
Copy
SELECT 
    p.name,
    pv.name AS variant,
    SUM(sd.quantity) AS total_quantity,
    SUM(sd.total_price) AS total_revenue,
    SUM(sd.quantity * pv.price_cost) AS total_cost,
    SUM(sd.total_price) - SUM(sd.quantity * pv.price_cost) AS total_profit
FROM sale_details sd
JOIN products p ON sd.product_id = p.id
JOIN product_variants pv ON sd.variant_id = pv.id
JOIN sales s ON sd.sale_id = s.id
WHERE s.deleted_at IS NULL
GROUP BY p.id, p.name, pv.id, pv.name
ORDER BY total_revenue DESC
LIMIT 10;
10. Return Analysis
Return Rate by Product
sql
Copy
SELECT 
    p.name,
    pv.name AS variant,
    SUM(rd.quantity_returned) AS total_returned,
    SUM(sd.quantity) AS total_sold,
    (SUM(rd.quantity_returned) / NULLIF(SUM(sd.quantity), 0)) * 100 AS return_rate
FROM return_details rd
JOIN sale_details sd ON rd.product_id = sd.product_id AND rd.variant_id = sd.variant_id
JOIN products p ON rd.product_id = p.id
JOIN product_variants pv ON rd.variant_id = pv.id
WHERE rd.deleted_at IS NULL
GROUP BY p.id, p.name, pv.id, pv.name
ORDER BY return_rate DESC;
Implementation Recommendations
Create a Dashboard View that shows all these metrics in real-time

Schedule Daily Reports for key metrics like cash position and sales

Set Up Alerts for:

Low inventory levels (below reorder_level)

High-value pending payments

Negative cash flow situations

Implement Trend Analysis by comparing periods (week-over-week, month-over-month)

Sample PHP/Laravel Implementation
For your Laravel application, you could create a BusinessMetricsService class:

php
Copy
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