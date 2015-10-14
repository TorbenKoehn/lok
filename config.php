<?php

error_reporting( E_ALL | E_STRICT );

return array(
    'db.dsn' => 'mysql:host=127.0.0.1;dbname=test',
    'db.user' => 'root',
    'db.pass' => '',
    //'db.check_model_integrity' => false,
    'db.check_model_integrity' => array(
        'account',
        'script_variable',
        'trigger',
        'map',
        'level',
        'item',

        'knowledge_base_category',
        'knowledge_base_article',
        
        'job_class',
      
        'inventory',
        'build',
        'inventory_item',
        'equipped_item',
        'item_bonus',
        'hotkey',
        'character'
       
    ),
    
    'page.layout' => 'default_layout'
);