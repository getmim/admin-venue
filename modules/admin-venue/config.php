<?php

return [
    '__name' => 'admin-venue',
    '__version' => '0.0.3',
    '__git' => 'git@github.com:getmim/admin-venue.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/admin-venue' => ['install','update','remove'],
        'theme/admin/venue' => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'admin' => NULL
            ],
            [
                'lib-formatter' => NULL
            ],
            [
                'lib-form' => NULL
            ],
            [
                'lib-pagination' => NULL
            ],
            [
                'venue' => NULL
            ],
            [
                'admin-site-meta' => NULL
            ]
        ],
        'optional' => [
            [
                'admin-venue-food' => NULL
            ],
            [
                'admin-venue-facility' => NULL
            ],
            [
                'admin-venue-category' => NULL
            ]
        ]
    ],
    'autoload' => [
        'classes' => [
            'AdminVenue\\Controller' => [
                'type' => 'file',
                'base' => 'modules/admin-venue/controller'
            ],
            'AdminVenue\\Library' => [
                'type' => 'file',
                'base' => 'modules/admin-venue/library'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'admin' => [
            'adminVenue' => [
                'path' => [
                    'value' => '/venue/object'
                ],
                'method' => 'GET',
                'handler' => 'AdminVenue\\Controller\\Venue::index'
            ],
            'adminVenueEdit' => [
                'path' => [
                    'value' => '/venue/object/(:id)',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET|POST',
                'handler' => 'AdminVenue\\Controller\\Venue::edit'
            ],
            'adminVenueRemove' => [
                'path' => [
                    'value' => '/venue/object/(:id)/remove',
                    'params' => [
                        'id' => 'number'
                    ]
                ],
                'method' => 'GET',
                'handler' => 'AdminVenue\\Controller\\Venue::remove'
            ]
        ]
    ],
    'adminUi' => [
        'sidebarMenu' => [
            'items' => [
                'venue' => [
                    'label' => 'Venue',
                    'icon' => '<i class="fas fa-map-marker-alt"></i>',
                    'priority' => 0,
                    'children' => [
                        'all-venue' => [
                            'label' => 'All Venue',
                            'icon' => '<i></i>',
                            'route' => ['adminVenue'],
                            'perms' => 'manage_venue'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'libForm' => [
        'forms' => [
            'admin.venue.edit' => [
                '@extends' => ['std-site-meta','std-cover'],
                'title' => [
                    'label' => 'Title',
                    'type' => 'text',
                    'rules' => [
                        'required' => TRUE
                    ]
                ],
                'slug' => [
                    'label' => 'Slug',
                    'type' => 'text',
                    'slugof' => 'title',
                    'rules' => [
                        'required' => TRUE,
                        'empty' => FALSE,
                        'unique' => [
                            'model' => 'Venue\\Model\\Venue',
                            'field' => 'slug',
                            'self' => [
                                'service' => 'req.param.id',
                                'field' => 'id'
                            ]
                        ]
                    ]
                ],
                'content' => [
                    'label' => 'About',
                    'type' => 'summernote',
                    'rules' => []
                ],
                'logo' => [
                    'label' => 'Logo',
                    'type' => 'image',
                    'form' => 'std-image',
                    'rules' => [
                        'upload' => TRUE
                    ]
                ],
                'open_hours-open' => [
                    'label' => 'From',
                    'type' => 'time',
                    'rules' => []
                ],
                'open_hours-close' => [
                    'label' => 'Close',
                    'type' => 'time',
                    'rules' => []
                ],
                'open_days' => [
                    'label' => 'Days',
                    'type' => 'checkbox-group',
                    'options' => [
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday',
                        6 => 'Saturday',
                        7 => 'Sunday'
                    ],
                    'rules' => []
                ],
                'prices-currency' => [
                    'label' => 'Currency',
                    'type' => 'text',
                    'options' => [
                        'IDR' => 'IDR',
                        'SGD' => 'SGD',
                        'USD' => 'USD'
                    ],
                    'rules' => []
                ],
                'prices-min' => [
                    'label' => 'Start From',
                    'type' => 'number',
                    'rules' => [
                        'numeric' => [
                            'min' => 0
                        ]
                    ]
                ],
                'prices-max' => [
                    'label' => 'Up To',
                    'type' => 'number',
                    'rules' => [
                        'numeric' => [
                            'min' => 1
                        ]
                    ]
                ],
                'contact-phone' => [
                    'label' => 'Phone',
                    'type' => 'tel',
                    'rules' => []
                ],
                'contact-email' => [
                    'label' => 'Email',
                    'type' => 'email',
                    'rules' => [
                        'email' => TRUE
                    ]
                ],
                'contact-map' => [
                    'label' => 'Map',
                    'type' => 'textarea',
                    'rules' => []
                ],
                'contact-address' => [
                    'label' => 'Address',
                    'type' => 'textarea',
                    'rules' => []
                ],
                'socials-facebook' => [
                    'label' => 'Facebook',
                    'type' => 'url',
                    'rules' => [
                        'url' => TRUE
                    ]
                ],
                'socials-instagram' => [
                    'label' => 'Instagram',
                    'type' => 'url',
                    'rules' => [
                        'url' => TRUE
                    ]
                ],
                'socials-youtube' => [
                    'label' => 'Youtube',
                    'type' => 'url',
                    'rules' => [
                        'url' => TRUE
                    ]
                ],
                'socials-gplus' => [
                    'label' => 'Google Plus',
                    'type' => 'url',
                    'rules' => [
                        'url' => TRUE
                    ]
                ],
                'socials-twitter' => [
                    'label' => 'Twitter',
                    'type' => 'url',
                    'rules' => [
                        'url' => TRUE
                    ]
                ],
                'socials-vimeo' => [
                    'label' => 'Vimeo',
                    'type' => 'url',
                    'rules' => [
                        'url' => TRUE
                    ]
                ],
                'socials-soundcloud' => [
                    'label' => 'SoundCloud',
                    'type' => 'url',
                    'rules' => [
                        'url' => TRUE
                    ]
                ],
                'socials-website' => [
                    'label' => 'Website',
                    'type' => 'url',
                    'rules' => [
                        'url' => TRUE
                    ]
                ],
                'status' => [
                    'label' => 'Status',
                    'type' => 'select',
                    'options' => [
                        '1' => 'Draft',
                        '2' => 'Published'
                    ],
                    'rules' => []
                ],
                'meta-schema' => [
                    'options' => [
                        'LocalBusiness' => 'LocalBusiness'
                    ]
                ]
            ],
            'admin.venue.index' => [
                'q' => [
                    'label' => 'Search',
                    'type' => 'search',
                    'nolabel' => TRUE,
                    'rules' => []
                ],
                'status' => [
                    'label' => 'Status',
                    'type' => 'select',
                    'nolabel' => TRUE,
                    'options' => [
                        '0' => 'All',
                        '1' => 'Draft',
                        '2' => 'Published'
                    ],
                    'rules' => []
                ]
            ]
        ]
    ],
    'admin' => [
        'objectFilter' => [
            'handlers' => [
                'venue' => 'AdminVenue\\Library\\Filter'
            ]
        ]
    ]
];
