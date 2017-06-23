<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'ReStore',

    'title_prefix' => '',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => '<b>Re</b>Store',

    'logo_mini' => '<b>R</b>S',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'purple',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    |
    | Choose a widget for your admin panel.
    |
    */

    'widget' => [
        'message'       => true,
        'notification'  => true,
        'task'          => true,
        'user'          => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => 'inventories#good',

    'logout_url' => 'logout',

    'logout_method' => null,

    'login_url' => 'login',

    'register_url' => 'register',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        'MAIN NAVIGATION',
        [
            'text'    => 'Inventory',
            'url'     => '#',
            'icon'    => 'th',
            'url'     => 'inventories', 
            // 'label'       => 4,
            // 'label_color' => 'success',
            // 'can'     => 'manager-receiving-coordinator-access',
            'submenu' => [
                [
                    'text' => 'For Review',
                    'url'  => 'inventories#for-review',
                    'can'  => 'receiving-coordinator-access',
                    'icon_color' => 'green',
                ],
                [
                    'text' => 'Under Repair',
                    'url'  => 'inventories#under-repair',
                    'can'  => 'receiving-coordinator-access',
                    'icon_color' => 'aqua',
                ],
                [
                    'text' => 'For Approval',
                    'can'  => 'manager-access',
                    'url'  => '#',
                    'icon_color' => 'aqua',
                    'submenu' => [
                        [
                            'text' => 'Floor Item',
                            'url'  => 'inventories#for-approval',
                        ],
                        [
                            'text' => 'Transfer',
                            'url'  => 'inventories#for-transfer',
                        ],
                        [
                            'text' => 'Disposal',
                            'url'  => 'inventories#for-disposal',
                        ],
                    ],    
                ],
                [
                    'text' => 'Good',
                    'url'  => 'inventories#good',
                    'icon_color' => 'green',
                ],
                [
                    'text' => 'Sold',
                    'url'  => 'inventories#sold',
                    'can'  => 'manager-access',
                    'icon_color' => 'yellow',
                ],
                [
                    'text' => 'Transferred',
                    'can'  => 'manager-access',
                    'url'  => 'inventories#transferred',
                ],
                [
                    'text' => 'Disposed',
                    'can'  => 'manager-access',
                    'url'  => 'inventories#disposed',
                ],
                [
                    'text' => 'Refunded',
                    'url'  => 'inventories#refunded',
                    'can'  => 'manager-access',
                ],
                [
                    'text' => 'Returned',
                    'url'  => 'inventories#returned',
                    'can'  => 'receiving-coordinator-access',
                    'icon_color' => 'red',
                ],
            ],    
        ],
        [
            'text' => 'Item',
            'url'  => 'items',
            'icon' => 'files-o',
            'can'  => 'manager-receiving-coordinator-access',
        ],
        [
            'text' => 'Discount',
            'url'  => 'discounts',
            'icon' => 'edit',
            'can'  => 'manager-receiving-coordinator-access',
        ],

        'ACCOUNT SETTINGS',
        [
            'text' => 'Profile',
            'url'  => 'admin/settings',
            'icon' => 'user',
        ],
        [
            'text' => 'Change Password',
            'url'  => 'admin/settings',
            'icon' => 'lock',
            'can'  => 'receiving-coordinator-access',
        ],
        [
            'text'    => 'Multilevel',
            'icon'    => 'share',
            'submenu' => [
                [
                    'text' => 'Level One',
                    'url'  => '#',
                ],
                [
                    'text'    => 'Level One',
                    'url'     => '#',
                    'submenu' => [
                        [
                            'text' => 'Level Two',
                            'url'  => '#',
                        ],
                        [
                            'text'    => 'Level Two',
                            'url'     => '#',
                            'submenu' => [
                                [
                                    'text' => 'Level Three',
                                    'url'  => '#',
                                ],
                                [
                                    'text' => 'Level Three',
                                    'url'  => '#',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'Level One',
                    'url'  => '#',
                ],
            ],
        ],
        'QUICK ACCESS',
        [
            'text'       => 'Good Item',
            'url'        => 'inventories#good',
            'icon_color' => 'green',
        ],
        [
            'text'       => 'Sold Item',
            'url'        => 'inventories#sold',
            'icon_color' => 'yellow',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => false,
    ],
];
