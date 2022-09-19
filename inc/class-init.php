<?php

namespace ES\ToDos;

class Init {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [$this, 'todo_scripts'] );
        add_action( 'wp_enqueue_scripts', [$this, 'todo_styles'] );
        add_action( 'admin_enqueue_scripts', [$this, 'todo_styles'] );
        add_action( 'admin_enqueue_scripts', [$this, 'todo_scripts'] );

        if( !get_option('es_todos') ) {
            add_option('es_todos', $this->createOptionsData());
        }

    }

    public function todo_styles() : void
    {
        wp_enqueue_style( 'google-fonts',    'https://fonts.googleapis.com/css2?family=Rubik:wght@400;500&display=swap');
        wp_enqueue_style( 'style',    '/wp-content/plugins/to-do-list/src/css/todo.css');
    }

    public function todo_scripts() : void
    {
        wp_enqueue_script('axios', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js');
        wp_enqueue_script('app', '/wp-content/plugins/to-do-list/src/js/Todo.js');
    }

    public function createOptionsData() : string
    {
        return '[{"id":1,"text":"to-do item","complete":true},{"id":2,"text":"to-do item","complete":true},{"id":3,"text":"to-do item","complete":false},{"id":4,"text":"to-do item","complete":true}]';
    }



}