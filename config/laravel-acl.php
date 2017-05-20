<?php

return [
    /**
     * The authentication model class
     */

    'user' => \App\User::class,

    /**
     * Table names
     */
    
    'tables' => [
        'user' => 'users',
        'level' => 'levels',
        'permission' => 'permissions',
        'permission_user' => 'permission_user'
    ],

    /**
     * Model definitions.
     * You can extend the models in the package
     * or leave the defaults. Just update the paths.
     */

    'level' => \z1haze\Acl\Models\Level::class,
    'permission' => \z1haze\Acl\Models\Permission::class,

    /**
     * Cache Minutes
     * Set the minutes that levels and permissions will be cached.
     */

    'cacheMinutes' => 1,
];