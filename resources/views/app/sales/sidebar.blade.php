<style>
    /* Base Sidebar Styles */
    .sidebar {
        width: 250px;
        transition: all 0.3s ease;
        background: white;
        color: #000000;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        /* overflow-y: auto; */
        z-index: 40;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        transform: translateX(-100%);
    }

    /* Desktop Styles */
    @media (min-width: 768px) {
        .sidebar {
            transform: translateX(0);
            height: calc(100vh - 4rem);
            top: 4.5rem;
        }
        
        .content-area {
            margin-left: 250px;
            transition: margin 0.3s;
        }
        
        .content-collapsed {
            margin-left: 70px;
        }
    }

    /* Mobile Open State */
    .sidebar-open {
        transform: translateX(0);
    }

    /* Collapsed State */
    .sidebar-collapsed {
        width: 70px;
    }
    
    .sidebar-collapsed .text,
    .sidebar-collapsed .sidebar-group-title {
        display: none;
    }

    /* Sidebar Items */
    .sidebar-item {
        padding: 0.75rem 1.5rem;
        margin: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s;
        color: #000000;
        white-space: nowrap;
    }
    
    .sidebar-item:hover {
        background: rgba(0, 0, 0, 0.1);
    }
    
    .sidebar-item.active {
        background: #3b82f6;
        color: white;
        font-weight: 500;
    }
    
    .sidebar-item .icon {
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    
    .sidebar-item .text {
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Divider & Group Titles */
    .sidebar-divider {
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        margin: 0.75rem 1rem;
    }
    
    .sidebar-group-title {
        padding: 0.5rem 1.5rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-top: 0.5rem;
    }
    
    /* Toggle Button */
    .toggle-sidebar {
        position: absolute;
        right: -12px;
        top: 20px;
        background: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        cursor: pointer;
        z-index: 10;
        border: 1px solid #e2e8f0;
    }
    
    /* Mobile Menu Button */
    .mobile-menu-button {
        display: block;
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 30;
        background: white;
        border-radius: 0.375rem;
        padding: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    @media (min-width: 768px) {
        .mobile-menu-button {
            display: none;
        }
    }
</style>

<!-- Mobile Menu Button (visible only on small screens) -->
<button class="mobile-menu-button md:hidden" onclick="toggleMobileSidebar()">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Collapse Button for Desktop -->
    <div class="toggle-sidebar hidden md:flex" onclick="toggleSidebar()" title="Toggle Sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </div>
    <div class="sidebar-group-title">Sales Management</div>

    <!-- Sales Links -->
    <a href="{{ route('sales.index') }}" class="sidebar-item {{ request()->routeIs('sales.index') ? 'active' : '' }}">
        <span class="icon">🛒</span>
        <span class="text">Sales Transactions</span>
    </a>
    <a href="{{ route('invoices.index') }}" class="sidebar-item {{ request()->routeIs('invoices.index') ? 'active' : '' }}">
        <span class="icon">📝</span>
        <span class="text">Invoices</span>
    </a>
    <a href="{{ route('customers.index') }}" class="sidebar-item {{ request()->routeIs('customers.index') ? 'active' : '' }}">
        <span class="icon">👥</span>
        <span class="text">Customers</span>
    </a>

    <!-- Returns/Refunds Dropdown -->
    <div x-data="{ open: {{ request()->routeIs('returns.*') ? 'true' : 'false' }} }">
        <div class="sidebar-item cursor-pointer" @click="open = !open">
            <span class="icon">🔄</span>
            <span class="text">Returns/Refunds</span>
            <svg :class="{'rotate-180': open}" class="ml-auto h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div class="pl-8 space-y-1" x-show="open" x-transition>
            <a href="#" class="sidebar-item {{ request()->routeIs('returns.index') ? 'active' : '' }}">
                <span class="icon">📝</span>
                <span class="text">Return Requests</span>
            </a>
            <a href="#" class="sidebar-item {{ request()->routeIs('refunds.approvals') ? 'active' : '' }}">
                <span class="icon">✅</span>
                <span class="text">Approval Workflow</span>
            </a>
            <a href="#" class="sidebar-item {{ request()->routeIs('returns.analytics') ? 'active' : '' }}">
                <span class="icon">📊</span>
                <span class="text">Reason Analytics</span>
            </a>
        </div>
    </div>

    <!-- Discounts/Promotions Dropdown -->
    <div x-data="{ open: {{ request()->routeIs('discounts.*') ? 'true' : 'false' }} }">
        <div class="sidebar-item cursor-pointer" @click="open = !open">
            <span class="icon">🎁</span>
            <span class="text">Discounts/Promotions</span>
            <svg :class="{'rotate-180': open}" class="ml-auto h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div class="pl-8 space-y-1" x-show="open" x-transition>
            <a href="#" class="sidebar-item {{ request()->routeIs('coupons.index') ? 'active' : '' }}">
                <span class="icon">🎟️</span>
                <span class="text">Coupon Codes</span>
            </a>
            <a href="#" class="sidebar-item {{ request()->routeIs('promotions.index') ? 'active' : '' }}">
                <span class="icon">🎁</span>
                <span class="text">Seasonal Offers</span>
            </a>
        </div>
    </div>

    <!-- Inventory Control Dropdown -->
    <div x-data="{ open: {{ request()->routeIs('inventory.*') ? 'true' : 'false' }} }">
        <div class="sidebar-item cursor-pointer" @click="open = !open">
            <span class="icon">📦</span>
            <span class="text">Inventory Control</span>
            <svg :class="{'rotate-180': open}" class="ml-auto h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div class="pl-8 space-y-1" x-show="open" x-transition>
            <a href="#" class="sidebar-item {{ request()->routeIs('inventory.automatic') ? 'active' : '' }}">
                <span class="icon">⚡</span>
                <span class="text">Auto Deduction</span>
            </a>
            <a href="#" class="sidebar-item {{ request()->routeIs('inventory.alerts') ? 'active' : '' }}">
                <span class="icon">🔴</span>
                <span class="text">Low Stock Warnings</span>
            </a>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    .sidebar {
        width: 250px;
        background: white;
        color: #000;
        height: 100vh;
        position: absolute;
        top: 0;
        left: 0;
        overflow-y: auto;
        z-index: 40;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        transform: translateX(-100%);
        transition: all 0.3s ease;
    }

    @media (min-width: 768px) {
        .sidebar {
            transform: translateX(0);
            height: calc(100vh - 4rem);
            top: 4.5rem;
            overflow-y: auto;
        }

        .content-area {
            margin-left: 250px;
            transition: margin 0.3s;
        }

        .content-collapsed {
            margin-left: 70px;
        }
    }

    .sidebar-open {
        transform: translateX(0);
    }

    .sidebar-collapsed {
        width: 70px;
    }

    .sidebar-collapsed .text,
    .sidebar-collapsed .sidebar-group-title {
        display: none;
    }

    .sidebar-item {
        padding: 0.75rem 1.5rem;
        margin: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s;
        color: #000;
        white-space: nowrap;
    }

    .sidebar-item:hover {
        background: rgba(0, 0, 0, 0.1);
    }

    .sidebar-item.active {
        background: #3b82f6;
        color: white;
        font-weight: 500;
    }

    .sidebar-item .icon {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .sidebar-item .text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        flex: 1;
    }

    .sidebar-divider {
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        margin: 0.75rem 1rem;
    }

    .sidebar-group-title {
        padding: 0.5rem 1.5rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-top: 0.5rem;
    }

    .toggle-sidebar {
        position: absolute;
        right: -12px;
        top: 20px;
        background: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        cursor: pointer;
        z-index: 10;
        border: 1px solid #e2e8f0;
    }

    .mobile-menu-button {
        display: block;
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 30;
        background: white;
        border-radius: 0.375rem;
        padding: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    @media (min-width: 768px) {
        .mobile-menu-button {
            display: none;
        }
    }

    .pl-8 {
        padding-left: 2rem;
    }
</style>

<!-- Script -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('contentArea');
        sidebar.classList.toggle('sidebar-collapsed');
        if (content) content.classList.toggle('content-collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('sidebar-collapsed'));
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('sidebar-open');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('contentArea');
        if (window.innerWidth >= 768) {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('sidebar-collapsed');
                if (content) content.classList.add('content-collapsed');
            }
        } else {
            sidebar.classList.remove('sidebar-open');
        }
    });

    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const mobileButton = document.querySelector('.mobile-menu-button');
        if (window.innerWidth < 768 &&
            !sidebar.contains(event.target) &&
            !mobileButton.contains(event.target)) {
            sidebar.classList.remove('sidebar-open');
        }
    });
</script>
