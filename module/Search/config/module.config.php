<?php

return array(
    'actions' => array(
        'index' => 'index',
        'start'=>  'start'
        
    ),
    'module_includes' => array(
        'merge' => array(
            'meta' => array(
                'keywords' => '<meta name="keywords" content="ВК,Анализ,Группы,ВК Анализ">',
                'description' => '<meta name="description" content="Анализ групп в контакте">'
            ),
            'script' => array(
                'search'               
               
            ),
            'css' => array(
                         
            ),
            'module_views' => array(
                'content' => array(
                'path' => 'index/main'
              ),
               
            )
        ),
        'replace' => array(
            'title' => 'Анализ групп'
        )
    )
);