<?php

return [
    [
        'label' => 'Dashboard',
        'route' => 'dashboard.index',
        'permission' => null, // accessible by all logged-in users
        'icon' => 'fa-solid fa-home',
    ],
    [
        'label' => 'Role Permissions',
        'route' => 'permissions.edit',
        'params' => ['Admin'], // optional route parameters
        'permission' => 'manage_roles',
        'icon' => 'fa-solid fa-user-shield',
    ],
    [
        'label' => 'Staff Management',
        'route' => 'staff.index',
        'permission' => 'manage_staff',
        'icon' => 'fa-solid fa-users',
    ],
    [
        'label' => 'Products',
        'route' => 'products.index',
        'permission' => 'view_products',
        'icon' => 'fa-solid fa-box',
    ],
    // add more links here
];