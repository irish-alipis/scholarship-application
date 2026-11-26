<li class="slide__category"><span class="category-name">Main</span></li>
@include('layouts.partials.menu-item', ['label' => 'Dashboard', 'route' => 'evaluator.dashboard', 'icon' => 'dashboard'])

<li class="slide__category"><span class="category-name">Evaluation</span></li>
@include('layouts.partials.menu-item', ['label' => 'Assigned Applications', 'route' => 'evaluator.applications.index', 'pattern' => 'evaluator.applications.*', 'icon' => 'document'])
@include('layouts.partials.menu-item', ['label' => 'Completed Evaluations', 'route' => 'evaluator.evaluations.index', 'pattern' => 'evaluator.evaluations.*', 'icon' => 'userCheck'])

<li class="slide__category"><span class="category-name">Monitoring</span></li>
@include('layouts.partials.menu-item', ['label' => 'Reports', 'route' => 'evaluator.reports.evaluations', 'pattern' => 'evaluator.reports.*', 'icon' => 'chart'])
