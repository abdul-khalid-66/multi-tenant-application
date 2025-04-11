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
<!-- Supplier Management Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Collapse Button for Desktop -->
    <div class="toggle-sidebar hidden md:flex" onclick="toggleSidebar()" title="Toggle Sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </div>
    
    <div class="sidebar-group-title">Supplier Management</div>

    <!-- Stock Transfers -->
    {{-- {{ route('suppliers.stock-transfers') }}" --}}
    {{-- <a href=" class="sidebar-item {{ request()->routeIs('suppliers.stock-transfers') ? 'active' : '' }}">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1v-1a1 1 0 011-1h2a1 1 0 011 1v1a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1V5a1 1 0 00-1-1H3z" />
            </svg>
        </span>
        <span class="text">Stock Transfers</span>
    </a> --}}

    <a href="{{ route('suppliers.index') }}" class="sidebar-item {{ request()->routeIs('suppliers.stock-transfers') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
            <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1v-1a1 1 0 011-1h2a1 1 0 011 1v1a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1V5a1 1 0 00-1-1H3z" />
        </svg>
        <span class="text">Suppliers</span>
    </a>

    <a href="" class="sidebar-item {{ request()->routeIs('suppliers.stock-transfers') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
            <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1v-1a1 1 0 011-1h2a1 1 0 011 1v1a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1V5a1 1 0 00-1-1H3z" />
        </svg>
        <span class="text">Stock Transfers</span>
    </a>
    <!-- Purchase Orders Dropdown -->
    <div x-data="{ open: {{ request()->routeIs('suppliers.purchase-orders.*') ? 'true' : 'false' }} }">
        <div class="sidebar-item cursor-pointer" @click="open = !open">
            <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                </svg>
            </span>
            <span class="text">Purchase Orders</span>
            <svg :class="{'rotate-180': open}" class="ml-auto h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div class="pl-8 space-y-1" x-show="open" x-transition>
            {{-- {{ route('suppliers.purchase-orders.create') }} --}}
            <a href="" class="sidebar-item {{ request()->routeIs('suppliers.purchase-orders.create') ? 'active' : '' }}">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="text">Generate PO</span>
            </a>
            {{-- {{ route('suppliers.purchase-orders.index') }} --}}
            <a href="" class="sidebar-item {{ request()->routeIs('suppliers.purchase-orders.index') ? 'active' : '' }}">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="text">PO History</span>
            </a>
        </div>
    </div>

    <!-- Financial Tracking Dropdown -->
    <div x-data="{ open: {{ request()->routeIs('suppliers.financial.*') ? 'true' : 'false' }} }">
        <div class="sidebar-item cursor-pointer" @click="open = !open">
            <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                </svg>
            </span>
            <span class="text">Financial Tracking</span>
            <svg :class="{'rotate-180': open}" class="ml-auto h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div class="pl-8 space-y-1" x-show="open" x-transition>
            {{-- {{ route('suppliers.payment-history') }} --}}
            <a href="" class="sidebar-item {{ request()->routeIs('suppliers.payment-history') ? 'active' : '' }}">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="text">Payment History</span>
            </a>
            {{-- {{ route('suppliers.outstanding-balances') }} --}}
            <a href="" class="sidebar-item {{ request()->routeIs('suppliers.outstanding-balances') ? 'active' : '' }}">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="text">Outstanding Balances</span>
            </a>
        </div>
    </div>

    <!-- Performance Dashboard -->
    {{-- {{ route('suppliers.performance') }} --}}
    <a href="" class="sidebar-item {{ request()->routeIs('suppliers.performance') ? 'active' : '' }}">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
            </svg>
        </span>
        <span class="text">Performance Dashboard</span>
    </a>

    <div class="sidebar-divider"></div>
    <div class="sidebar-group-title">Financial Reports</div>

    <!-- Revenue Reports -->
    {{-- {{ route('suppliers.revenue-reports') }} --}}
    <a href="" class="sidebar-item {{ request()->routeIs('suppliers.revenue-reports') ? 'active' : '' }}">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
            </svg>
        </span>
        <span class="text">Revenue Reports</span>
    </a>

    <!-- Profit/Loss Analysis -->
    {{-- {{ route('suppliers.profit-loss') }} --}}
    <a href="" class="sidebar-item {{ request()->routeIs('suppliers.profit-loss') ? 'active' : '' }}">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </span>
        <span class="text">Profit/Loss Analysis</span>
    </a>

    <!-- Expenses Tracking -->
    {{-- {{ route('suppliers.expenses') }} --}}
    <a href="" class="sidebar-item {{ request()->routeIs('suppliers.expenses') ? 'active' : '' }}">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
            </svg>
        </span>
        <span class="text">Expenses Tracking</span>
    </a>

    <!-- Investments -->
    {{--  --}}
    <a href="{{ route('investments.index') }}" class="sidebar-item {{ request()->routeIs('investments.index') ? 'active' : '' }}">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12 8a1 1 0 100-2H7.707l-.707-.707a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2A1 1 0 0012 8zm-5 4a1 1 0 100 2h4.293l.707.707a1 1 0 001.414-1.414l-2-2a1 1 0 00-1.414 0l-2 2A1 1 0 007 12z" clip-rule="evenodd" />
            </svg>
        </span>
        <span class="text">Investments</span>
    </a>

    <!-- Cash Flow -->
    {{-- {{ route('suppliers.cash-flow') }} --}}
    <a href="" class="sidebar-item {{ request()->routeIs('suppliers.cash-flow') ? 'active' : '' }}">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
            </svg>
        </span>
        <span class="text">Cash Flow</span>
    </a>
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
