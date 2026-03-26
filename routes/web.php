<?php

declare(strict_types=1);

return [
    ['method' => 'GET', 'path' => '/', 'handler' => 'DashboardController@index', 'auth' => true, 'roles' => ['ADMIN', 'STAFF', 'EXECUTIVE']],
    ['method' => 'GET', 'path' => '/register', 'handler' => 'AuthController@showRegister', 'auth' => false],
    ['method' => 'POST', 'path' => '/register', 'handler' => 'AuthController@register', 'auth' => false],
    ['method' => 'GET', 'path' => '/login', 'handler' => 'AuthController@showLogin', 'auth' => false],
    ['method' => 'POST', 'path' => '/login', 'handler' => 'AuthController@login', 'auth' => false],
    ['method' => 'POST', 'path' => '/logout', 'handler' => 'AuthController@logout', 'auth' => true],

    ['method' => 'GET', 'path' => '/dashboard', 'handler' => 'DashboardController@index', 'auth' => true, 'roles' => ['ADMIN', 'STAFF', 'EXECUTIVE']],

    ['method' => 'GET', 'path' => '/projects', 'handler' => 'ProjectController@index', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],
    ['method' => 'GET', 'path' => '/projects/{id}', 'handler' => 'ProjectController@show', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],
    ['method' => 'POST', 'path' => '/projects', 'handler' => 'ProjectController@store', 'auth' => true, 'roles' => ['ADMIN']],
    ['method' => 'POST', 'path' => '/projects/{id}/update', 'handler' => 'ProjectController@update', 'auth' => true, 'roles' => ['ADMIN']],
    ['method' => 'POST', 'path' => '/projects/{id}/delete', 'handler' => 'ProjectController@destroy', 'auth' => true, 'roles' => ['ADMIN']],

    ['method' => 'GET', 'path' => '/plans', 'handler' => 'PlanController@index', 'auth' => true, 'roles' => ['ADMIN', 'STAFF', 'EXECUTIVE']],
    ['method' => 'GET', 'path' => '/plans/create', 'handler' => 'PlanController@create', 'auth' => true, 'roles' => ['ADMIN']],
    ['method' => 'POST', 'path' => '/plans', 'handler' => 'PlanController@store', 'auth' => true, 'roles' => ['ADMIN']],
    ['method' => 'POST', 'path' => '/plans/{id}/update', 'handler' => 'PlanController@update', 'auth' => true, 'roles' => ['ADMIN']],
    ['method' => 'POST', 'path' => '/plans/{id}/delete', 'handler' => 'PlanController@destroy', 'auth' => true, 'roles' => ['ADMIN']],

    ['method' => 'GET', 'path' => '/kpis', 'handler' => 'KpiController@index', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],
    ['method' => 'POST', 'path' => '/kpis', 'handler' => 'KpiController@store', 'auth' => true, 'roles' => ['ADMIN']],
    ['method' => 'POST', 'path' => '/kpis/{id}/update', 'handler' => 'KpiController@update', 'auth' => true, 'roles' => ['ADMIN']],
    ['method' => 'POST', 'path' => '/kpis/{id}/delete', 'handler' => 'KpiController@destroy', 'auth' => true, 'roles' => ['ADMIN']],

    ['method' => 'GET', 'path' => '/activities', 'handler' => 'ActivityController@index', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],
    ['method' => 'POST', 'path' => '/activities', 'handler' => 'ActivityController@store', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],
    ['method' => 'POST', 'path' => '/activities/{id}/update', 'handler' => 'ActivityController@update', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],
    ['method' => 'POST', 'path' => '/activities/{id}/delete', 'handler' => 'ActivityController@destroy', 'auth' => true, 'roles' => ['ADMIN']],

    ['method' => 'GET', 'path' => '/reports/create', 'handler' => 'ReportController@create', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],
    ['method' => 'GET', 'path' => '/reports/{id}/edit', 'handler' => 'ReportController@edit', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],
    ['method' => 'POST', 'path' => '/reports', 'handler' => 'ReportController@store', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],
    ['method' => 'POST', 'path' => '/reports/{id}/update', 'handler' => 'ReportController@update', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],
    ['method' => 'GET', 'path' => '/reports/load-project-data', 'handler' => 'ReportController@loadProjectData', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],

    ['method' => 'GET', 'path' => '/budget-reports/create', 'handler' => 'BudgetReportController@create', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],
    ['method' => 'POST', 'path' => '/budget-reports', 'handler' => 'BudgetReportController@store', 'auth' => true, 'roles' => ['ADMIN', 'STAFF']],

    ['method' => 'POST', 'path' => '/imports/{entity}', 'handler' => 'ImportController@store', 'auth' => true, 'roles' => ['ADMIN']],
];
