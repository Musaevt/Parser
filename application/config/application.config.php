<?php

return array(
    'modules' => array(
        'index'=>'Index',
        'api'=>'Api'
      
    ),
    'module_options' => array(
        'module_path' => '/module'
    ),
    'vendor_options' => array(
        'vendor_path' => '/vendor',
        'projects' => array(
            'lib' => array(
                'base_path' => '/lib/libraryframework',
                'library_path' => '/lib/libraryframework/library'
            )
        )
    ),
    'views_options' => array(
        'views_path' => '/vendor/lib/libraryframework/resources/layout',
        'common_includes' => array(
            'title' => 'Анализ групп в Вконтакте',
            'meta' => array(
                'charset' => '<meta http-equiv="content-type" content="text/html; charset=utf-8">',
                'ie' => '<meta http-equiv="X-UA-Compatible" content="IE=edge">'
            ),
            'script' => array(
                'jquery',
                
            ),
            'css' => array('styles'),
            'icon' => array(),
            'views' => array(
                'head' => array(
                    'path' => 'common/head/head'
                ),
                'footer' => array(
                 'path' => 'common/footer/footer'
                )
            )
        )
    ),
    'resource_options' => array(
        'path' => '/application/resources/global.php'
    ),
    'db_options' => require Q_PATH.'/application/config/db.config.php',
    
    'tables'=>array(
       "table_Users"          =>"vk_Users_2",
       "table_Groups"         =>"vk_Groups_2",
       "table_Users_In_Groups"=>"Users_In_Groups_2",
    )
);

/*
'views' => array(
    'head' => array(
        'authorized_mode' => true,
        'allocated_paths' => array(
            array(
                'range' => 'default',
                'value' => 'common/head/head'
            ),
            array(
                'range' => array(1, 2),
                'value' => 'common/footer/footer'
            )
        )
    ),
    'footer' => array(
        'authorized_mode' => false,
        'allocated_paths' => array(
            array(
                'range' => 'default',
                'value' => 'common/footer/footer'
            )
        )
    )
)
*/