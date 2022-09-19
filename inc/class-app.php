<?php

namespace ES\ToDos;

use ES\ToDos\Markup;

class App {
    public function __construct()
    {
        add_shortcode('todos', [$this,'markup'] );
    }

    public function markup() : string
    {
        return ( new Markup() )->init();
    }


}