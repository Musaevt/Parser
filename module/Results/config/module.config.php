<?php

return array(
    'actions' => array(
        'index' => 'index',
        'show'=>'Show' 
    ),
    'module_includes' => array(
        'merge' => array(
            'meta' => array(
                'keywords' => '<meta name="keywords" content="ВК,Анализ,Группы,ВК Анализ">',
                'description' => '<meta name="description" content="Анализ групп в контакте">'
            ),
            'script' => array(
                   'chart',
                   'results',
            ),
            'css' => array(
                         
            ),
            'module_views' => array(
                'content' => array(
                'path' => 'index/main'
              ),
             'show'=>array(
                'path'=>'index/show'
                )
            )
        ),
        'replace' => array(
            'title' => 'Анализ групп в ВКонтаке'
        )
    )
);