<li class="slide__category"><span class="category-name">Main</span></li>
@include('layouts.partials.menu-item', ['label' => 'Dashboard', 'route' => 'applicant.dashboard', 'icon' => 'dashboard'])
@include('layouts.partials.menu-item', ['label' => 'Student Profile', 'route' => 'applicant.profile.edit', 'pattern' => 'applicant.profile.*', 'icon' => 'person'])

<li class="slide__category"><span class="category-name">Scholarships</span></li>
@include('layouts.partials.menu-item', ['label' => 'Scholarships', 'route' => 'applicant.scholarships.index', 'pattern' => 'applicant.scholarships.*', 'icon' => 'school'])
@include('layouts.partials.menu-item', ['label' => 'My Applications', 'route' => 'applicant.applications.index', 'pattern' => 'applicant.applications.*', 'icon' => 'document'])

<li class="slide__category"><span class="category-name">Updates</span></li>
@include('layouts.partials.menu-item', ['label' => 'Notifications', 'route' => 'applicant.notifications.index', 'pattern' => 'applicant.notifications.*', 'icon' => 'bell'])
