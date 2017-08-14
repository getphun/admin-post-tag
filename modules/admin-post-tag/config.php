<?php
/**
 * admin-post-tag config file
 * @package admin-post-tag
 * @version 0.0.1
 * @upgrade true
 */

return [
    '__name' => 'admin-post-tag',
    '__version' => '0.0.1',
    '__git' => 'https://github.com/getphun/admin-post-tag',
    '__files' => [
        'modules/admin-post-tag' => [ 'install', 'remove', 'update' ],
        'theme/admin/post/tag'   => [ 'install', 'remove', 'update' ]
    ],
    '__dependencies' => [
        'admin',
        'post-tag'
    ],
    '_services' => [],
    '_autoload' => [
        'classes' => [
            'AdminPostTag\\Controller\\TagController' => 'modules/admin-post-tag/controller/TagController.php'
        ],
        'files' => []
    ],
    
    '_routes' => [
        'admin' => [
            'adminPostTag' => [
                'rule' => '/post/tag',
                'handler' => 'AdminPostTag\\Controller\\Tag::index'
            ],
            'adminPostTagEdit' => [
                'priority' => 0,
                'rule'  => '/post/tag/:id',
                'handler' => 'AdminPostTag\\Controller\\Tag::edit'
            ],
            'adminPostTagFilter' => [
                'rule'  => '/post/tag/filter',
                'handler' => 'AdminPostTag\\Controller\\Tag::filter'
            ],
            'adminPostTagRemove' => [
                'rule'  => '/post/tag/:id/remove',
                'handler' => 'AdminPostTag\\Controller\\Tag::remove'
            ]
        ]
    ],
    
    'admin' => [
        'menu' => [
            'post' => [
                'label'     => 'Post',
                'icon'      => 'newspaper-o',
                'order'     => 10,
                'submenu'   => [
                    'post-tag'  => [
                        'label'     => 'Tag',
                        'perms'     => 'read_post_tag',
                        'target'    => 'adminPostTag',
                        'order'     => 40
                    ]
                ]
            ]
        ]
    ],
    
    'form' => [
        'admin-post-tag' => [
            'name' => [
                'type'      => 'text',
                'label'     => 'Name',
                'rules'     => [
                    'required'  => true
                ]
            ],
            'slug' => [
                'type'      => 'text',
                'label'     => 'Slug',
                'attrs'     => [
                    'data-slug' => '#field-name'
                ],
                'rules'     => [
                    'required'  => true,
                    'alnumdash' => true,
                    'unique' => [
                        'model' => 'PostTag\\Model\\PostTag',
                        'field' => 'slug',
                        'self'  => [
                            'uri'   => 'id',
                            'field' => 'id'
                        ]
                    ]
                ]
            ],
            'about' => [
                'type'      => 'textarea',
                'label'     => 'About',
                'rules'     => []
            ],
            'meta_title' => [
                'type'      => 'text',
                'label'     => 'Meta Title',
                'rules'     => []
            ],
            'meta_description' => [
                'type'      => 'textarea',
                'label'     => 'Meta Description',
                'rules'     => []
            ],
            'meta_keywords' => [
                'type'      => 'text',
                'label'     => 'Meta Keywords',
                'rules'     => []
            ]
        ],
        'admin-post-tag-index' => [
            'q' => [
                'type' => 'search',
                'label'=> 'Find tag',
                'nolabel'=> true,
                'rules'=> []
            ]
        ]
    ]
];