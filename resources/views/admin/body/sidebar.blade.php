<div class="sidebar-wrapper" data-simplebar="true">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');
        .sidebar-wrapper {
            background: linear-gradient(180deg, #f8f9fc, #e0e6ed);
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            transition: all 0.3s ease;
            font-family: 'Open Sans', sans-serif;
        }
        .sidebar-header {
            background: #5a7db5;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .sidebar-header .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }
        .sidebar-header:hover .logo-icon {
            transform: scale(1.1);
        }
        .sidebar-header .logo-text {
            color: #ffffff;
            font-size: 1.6rem;
            font-weight: 600;
            margin: 0 0 0 10px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }
        .sidebar-header .toggle-icon {
            color: #ebba4d;
            font-size: 1.5rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .sidebar-header .toggle-icon:hover {
            transform: rotate(180deg);
        }
        .metismenu {
            padding: 20px 0;
        }
        .metismenu .menu-label {
            color: #5a7db5;
            font-size: 0.9rem;
            padding: 10px 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(90, 125, 181, 0.2);
        }
        .metismenu li a {
            color: #2c3e50;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 5px 10px;
        }
        .metismenu li a:hover, .metismenu li a.active {
            background: linear-gradient(135deg, #f18786, #ebba4d);
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
        .metismenu .has-arrow::after {
            border-color: #2c3e50;
            transition: transform 0.3s ease;
        }
        .metismenu .has-arrow[aria-expanded="true"]::after {
            transform: rotate(180deg);
            border-color: #ffffff;
        }
        .metismenu .parent-icon {
            width: 30px;
            text-align: center;
            font-size: 1.4rem;
            transition: transform 0.3s ease;
            color: #5a7db5;
        }
        .metismenu li a:hover .parent-icon, .metismenu li a.active .parent-icon {
            transform: scale(1.1);
            color: #ffffff;
        }
        .metismenu .menu-title {
            font-size: 1rem;
            margin-left: 10px;
            flex-grow: 1;
        }
        .metismenu ul {
            background: rgba(90, 125, 181, 0.05);
            padding: 10px 0;
            border-radius: 8px;
            margin: 0 15px;
            display: none; /* Sous-menus fermés par défaut */
        }
        .metismenu li a[aria-expanded="true"] + ul {
            display: block; /* Ouvre les sous-menus lorsque l'élément parent est actif */
        }
        .metismenu ul li a {
            padding: 10px 20px 10px 40px;
            font-size: 0.95rem;
            color: #2c3e50;
        }
        .metismenu ul li a:hover {
            background: rgba(241, 135, 134, 0.3);
            color: #f18786;
        }
        .metismenu ul li a i {
            margin-right: 10px;
            font-size: 0.8rem;
            color: #5a7db5;
        }
        .metismenu ul li a:hover i {
            color: #f18786;
        }
    </style>

    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Admin</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i></div>
    </div>

    <!-- Navigation -->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        <li class="menu-label">UI Elements</li>

        @if (Auth::user()->can('category.menu'))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cart'></i></div>
                <div class="menu-title">Manage Category</div>
            </a>
            <ul>
                @if (Auth::user()->can('category.all'))
                <li><a href="{{ route('all.category') }}"><i class='bx bx-radio-circle'></i>All Category</a></li>
                @endif
                @if (Auth::user()->can('subcategory.all'))
                <li><a href="{{ route('all.subcategory') }}"><i class='bx bx-radio-circle'></i>All SubCategory</a></li>
                @endif
            </ul>
        </li>
        @endif

        @if (Auth::user()->can('instructor.menu'))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i></div>
                <div class="menu-title">Manage Instructor</div>
            </a>
            <ul>
                <li><a href="{{ route('all.instructor') }}"><i class='bx bx-radio-circle'></i>All Instructor</a></li>
            </ul>
        </li>
        @endif

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i></div>
                <div class="menu-title">Manage Courses</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.all.course') }}"><i class='bx bx-radio-circle'></i>All Courses</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i></div>
                <div class="menu-title">Manage Coupon</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.all.coupon') }}"><i class='bx bx-radio-circle'></i>All Coupon</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i></div>
                <div class="menu-title">Manage Setting</div>
            </a>
            <ul>
                <li><a href="{{ route('smtp.setting') }}"><i class='bx bx-radio-circle'></i>Manage SMTP</a></li>
                <li><a href="{{ route('site.setting') }}"><i class='bx bx-radio-circle'></i>Site Setting</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i></div>
                <div class="menu-title">Manage Orders</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.pending.order') }}"><i class='bx bx-radio-circle'></i>Pending Orders</a></li>
                <li><a href="{{ route('admin.confirm.order') }}"><i class='bx bx-radio-circle'></i>Confirm Orders</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i></div>
                <div class="menu-title">Manage Report</div>
            </a>
            <ul>
                <li><a href="{{ route('report.view') }}"><i class='bx bx-radio-circle'></i>Report View</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i></div>
                <div class="menu-title">Manage Review</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.pending.review') }}"><i class='bx bx-radio-circle'></i>Pending Review</a></li>
                <li><a href="{{ route('admin.active.review') }}"><i class='bx bx-radio-circle'></i>Active Review</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i></div>
                <div class="menu-title">Manage All User</div>
            </a>
            <ul>
                <li><a href="{{ route('all.user') }}"><i class='bx bx-radio-circle'></i>All User</a></li>
                <li><a href="{{ route('all.instructor') }}"><i class='bx bx-radio-circle'></i>All Instructor</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i></div>
                <div class="menu-title">Manage Blog</div>
            </a>
            <ul>
                <li><a href="{{ route('blog.category') }}"><i class='bx bx-radio-circle'></i>Blog Category</a></li>
                <li><a href="{{ route('blog.post') }}"><i class='bx bx-radio-circle'></i>Blog Post</a></li>
            </ul>
        </li>

        <li class="menu-label">Role & Permission</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-line-chart"></i></div>
                <div class="menu-title">Role & Permission</div>
            </a>
            <ul>
                <li><a href="{{ route('all.permission') }}"><i class='bx bx-radio-circle'></i>All Permission</a></li>
                <li><a href="{{ route('all.roles') }}"><i class='bx bx-radio-circle'></i>All Roles</a></li>
                <li><a href="{{ route('add.roles.permission') }}"><i class='bx bx-radio-circle'></i>Role In Permission</a></li>
                <li><a href="{{ route('all.roles.permission') }}"><i class='bx bx-radio-circle'></i>All Role In Permission</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-line-chart"></i></div>
                <div class="menu-title">Manage Admin</div>
            </a>
            <ul>
                <li><a href="{{ route('all.admin') }}"><i class='bx bx-radio-circle'></i>All Admin</a></li>
            </ul>
        </li>
    </ul>
    <!--end navigation-->

    <!-- Script pour fermer les sous-menus par défaut -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sélectionne tous les éléments avec la classe has-arrow
            const menuItems = document.querySelectorAll('.metismenu .has-arrow');
            menuItems.forEach(item => {
                // Retire l'attribut aria-expanded="true" et ferme les sous-menus
                item.setAttribute('aria-expanded', 'false');
                const subMenu = item.nextElementSibling;
                if (subMenu && subMenu.tagName === 'UL') {
                    subMenu.style.display = 'none';
                }
            });

            // Ajoute un événement de clic pour ouvrir/fermer manuellement
            menuItems.forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !isExpanded);
                    const subMenu = this.nextElementSibling;
                    if (subMenu && subMenu.tagName === 'UL') {
                        subMenu.style.display = isExpanded ? 'none' : 'block';
                    }
                });
            });
        });
    </script>
</div>