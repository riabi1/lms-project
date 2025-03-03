<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3" style="background: linear-gradient(90deg, #5a7db5, #3e5f9c); padding: 15px;">
            <div class="mobile-toggle-menu" style="color: #ebba4d; font-size: 1.5rem;">
                <i class='bx bx-menu'></i>
            </div>

            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-1">
                    <li class="nav-item dark-mode d-none d-sm-flex">
                        <a class="nav-link dark-mode-icon" href="javascript:;" style="color: #ebba4d;">
                            <i class='bx bx-moon'></i>
                        </a>
                    </li>
                </ul>
            </div>

            @php
                $id = Auth::user()->id;
                $profileData = App\Models\User::find($id);
            @endphp

            <div class="user-box dropdown px-3">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #ffffff;">
                    <img src="{{ (!empty($profileData->photo)) ? url('upload/admin_images/'.$profileData->photo) : url('upload/no_image.jpg') }}" class="user-img" alt="user avatar" style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid #ebba4d;">
                    <div class="user-info">
                        <p class="user-name mb-0" style="color: #ebba4d;">{{ $profileData->name }}</p>
                        <p class="designattion mb-0" style="color: #f18786; font-size: 0.85rem;">{{ $profileData->email }}</p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 8px;">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.profile') }}" style="color: #2c3e50;">
                            <i class="bx bx-user fs-5" style="color: #5a7db5;"></i><span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.change.password') }}" style="color: #2c3e50;">
                            <i class="bx bx-cog fs-5" style="color: #5a7db5;"></i><span>Change Password</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider mb-0" style="border-color: #e0e0e0;"></div>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.logout') }}" style="color: #2c3e50;">
                            <i class="bx bx-log-out-circle" style="color: #f18786;"></i><span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>