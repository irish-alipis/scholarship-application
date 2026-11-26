<li class="slide__category"><span class="category-name">Main</span></li>
@include('layouts.partials.menu-item', ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'dashboard'])

<li class="slide__category"><span class="category-name">Management</span></li>
@include('layouts.partials.menu-item', ['label' => 'Users', 'route' => 'admin.users.index', 'pattern' => 'admin.users.*', 'icon' => 'people'])
@include('layouts.partials.menu-item', ['label' => 'Evaluator Requests', 'route' => 'admin.evaluator-requests.index', 'pattern' => 'admin.evaluator-requests.*', 'icon' => 'userCheck'])
@include('layouts.partials.menu-item', ['label' => 'Scholarships', 'route' => 'admin.scholarships.index', 'pattern' => 'admin.scholarships.*', 'icon' => 'school'])
@include('layouts.partials.menu-item', ['label' => 'Applications', 'route' => 'admin.applications.index', 'pattern' => 'admin.applications.*', 'icon' => 'document'])

<li class="slide__category"><span class="category-name">Monitoring</span></li>
@include('layouts.partials.menu-item', ['label' => 'Reports', 'route' => 'admin.reports.applications', 'pattern' => 'admin.reports.*', 'icon' => 'chart'])
@include('layouts.partials.menu-item', ['label' => 'Audit Logs', 'route' => 'admin.audit-logs.index', 'pattern' => 'admin.audit-logs.*', 'icon' => 'history'])
