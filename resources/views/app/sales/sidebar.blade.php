<style>
    /* Base Sidebar Styles */
    .sidebar {
        width: 250px;
        transition: all 0.3s ease;
        background: white;
        color: #000000;
        height: 100vh;
        position: absolute;
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
    <div class="toggle-sidebar hidden md:flex" onclick="toggleSidebar()">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
        </svg>
    </div>
    
    <div class="sidebar-group-title">Sale</div>
    <a href="{{ route('sales.index') }}" class="sidebar-item active">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
            </svg>
        </span>
        <span class="text">Sales Transactions</span>
    </a>

    {{-- {{ route('invoices.index') }} --}}
    <a href="{{ route('invoices.index') }}" class="sidebar-item {{ request()->routeIs('invoices.index') ? 'active' : '' }}">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm6.207.293a1 1 0 00-1.414 0l-6 6a1 1 0 101.414 1.414l6-6a1 1 0 000-1.414zM12.5 10a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd" />
            </svg>
        </span>
        <span class="text">Invoices</span>
    </a>

    {{-- {{ route('customers.index') }} --}}
    <a href="" class="sidebar-item {{ request()->routeIs('customers.index') ? 'active' : '' }}">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z" />
            </svg>
        </span>
        <span class="text">Customers</span>
    </a>

    {{-- {{ route('returns.index') }} --}}
    <a href="" class="sidebar-item {{ request()->routeIs('returns.index') ? 'active' : '' }}">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </span>
        <span class="text">Returns/Refunds</span>
    </a>

    {{-- {{ route('discounts.index') }} --}}
    <a href="" class="sidebar-item {{ request()->routeIs('discounts.index') ? 'active' : '' }}">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-percent-icon lucide-percent"><line x1="19" x2="5" y1="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
        </span>
        <span class="text">Discounts</span>
    </a>

    {{-- {{ route('inventory-control.index') }} --}}
    <a href="" class="sidebar-item {{ request()->routeIs('inventory-control.index') ? 'active' : '' }}">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
            </svg>
        </span>
        <span class="text">Inventory Control</span>
    </a>
    
</div>

<script>
    // Toggle sidebar collapse/expand
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('contentArea');
        
        sidebar.classList.toggle('sidebar-collapsed');
        if (content) {
            content.classList.toggle('content-collapsed');
        }
        
        // Store preference in localStorage
        const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    }
    
    // Toggle mobile sidebar visibility
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('sidebar-open');
    }
    
    // Check for saved preference on load
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('contentArea');
        
        // Only apply desktop preferences on desktop
        if (window.innerWidth >= 768) {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('sidebar-collapsed');
                if (content) {
                    content.classList.add('content-collapsed');
                }
            }
        } else {
            // On mobile, start with sidebar closed
            sidebar.classList.remove('sidebar-open');
        }
    });
    
    // Close sidebar when clicking outside on mobile
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