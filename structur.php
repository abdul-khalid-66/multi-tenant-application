Here’s the **structured documentation** for your database tables, including **column names**, **data types**, and
**purpose** for each column. This will help you understand the structure and ensure consistency in your database design.


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