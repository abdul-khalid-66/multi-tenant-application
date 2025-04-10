<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<!-- Sidebar Styles -->
<style>
    .sidebar {
        width: 250px;
        transition: all 0.3s ease;
        background: white;
        color: #000;
        height: 100vh;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 40;
        transform: translateX(-100%);
        transition: all 0.3s ease;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

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
        padding: 0.75rem 1.25rem;
        margin: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #000;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
        white-space: nowrap;
    }

    .sidebar-item:hover {
        background-color: #f1f5f9;
    }

    .sidebar-item.active {
        background-color: #3b82f6;
        color: white;
        font-weight: 500;
    }

    .sidebar-item .icon {
        font-size: 1.25rem;
        width: 1.5rem;
        text-align: center;
        color: #030303;
    }

    .sidebar-item.active .icon {
        color: white;
    }

    .sidebar-divider {
        border-top: 1px solid #e2e8f0;
        margin: 0.75rem 1rem;
    }

    .sidebar-group-title {
        padding: 0.5rem 1.5rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #64748b;
        margin-top: 0.75rem;
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
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
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
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    @media (min-width: 768px) {
        .mobile-menu-button {
            display: none;
        }
    }
    .sidebar-collapsed .toggle-sidebar svg {
        transform: rotate(180deg);
    }
</style>

<!-- Mobile Toggle Button -->
<button class="mobile-menu-button md:hidden" onclick="toggleMobileSidebar()">
    <i class="fas fa-bars text-xl text-gray-700"></i>
</button>

<!-- Product Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Collapse Toggle (Desktop only) -->
    <div class="toggle-sidebar hidden md:flex" onclick="toggleSidebar()" title="Toggle Sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </div>

    <div class="sidebar-group-title">Products</div>

    <a href="{{ route('products.index') }}" class="sidebar-item {{ request()->routeIs('products.index') ? 'active' : '' }}">
        <i class="fas fa-box icon"></i>
        <span class="text">Products</span>
    </a>


    <a href="{{ route('categories.index') }}" class="sidebar-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
        <i class="fas fa-layer-group icon"></i>
        <span class="text">Categories</span>
    </a>

    <a href="{{ route('product-variants.index') }}" class="sidebar-item {{ request()->routeIs('product-variants.index') ? 'active' : '' }}">
        <i class="fas fa-palette icon"></i>
        <span class="text">Variants</span>
    </a>

    <a href="{{ route('inventory-logs.index') }}" class="sidebar-item {{ request()->routeIs('inventory-logs.index') ? 'active' : '' }}">
        <i class="fas fa-clipboard-list icon"></i>
        <span class="text">Inventory Logs</span>
    </a>
</div>

<!-- JavaScript -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('contentArea');
        sidebar.classList.toggle('sidebar-collapsed');
        if (content) content.classList.toggle('content-collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('sidebar-collapsed'));
    }

    function toggleMobileSidebar() {
        document.getElementById('sidebar').classList.toggle('sidebar-open');
    }

    document.addEventListener('DOMContentLoaded', () => {
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

    document.addEventListener('click', (e) => {
        const sidebar = document.getElementById('sidebar');
        const mobileBtn = document.querySelector('.mobile-menu-button');
        if (window.innerWidth < 768 && !sidebar.contains(e.target) && !mobileBtn.contains(e.target)) {
            sidebar.classList.remove('sidebar-open');
        }
    });
</script>
