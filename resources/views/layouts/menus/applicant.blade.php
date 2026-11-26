<li class="slide__category"><span class="category-name">Main</span></li>
@include('layouts.partials.menu-item', ['label' => 'Dashboard', 'route' => 'applicant.dashboard', 'pattern' => 'applicant.dashboard', 'icon' => 'dashboard'])

<li class="slide__category"><span class="category-name">Account</span></li>
@include('layouts.partials.menu-item', ['label' => 'My Profile', 'route' => 'profile.edit', 'pattern' => 'profile.*', 'icon' => 'person'])