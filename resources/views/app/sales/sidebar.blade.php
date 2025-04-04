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
    <a href="{{ route('sales.create') }}" class="sidebar-item">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
        </span>
        <span class="text">Add Product</span>
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