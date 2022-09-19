<?php

namespace ES\ToDos;

class Markup {

    public function init() : string
    {
        $mode = get_option( 'todos_display_mode' );
        $dpm = $mode['display_mode'];

        $todos =  get_option( 'es_todos' );
        $data = json_decode($todos);

        $admin = is_admin() ? 'admin' : 'live';


        $html = '<script>let todo = new Todo();</script>';

        $html .= "<div class='es-{$admin}'>";
        $html .= "<ul id='es-todos' class='es-todos {$dpm}'>";
        $html .= '<div class="es-new-task-wrapper"><input type="text" class="es-new-task" id="newTask" onkeyup="todo.addPost(event)" placeholder="Add new task" /><button class="es-new-task-button"  onclick="todo.addItem()">&nbsp;</button></div>';
        $html .= '<h2 class="es-heading"> Things to do</h2>';
        foreach($data as $key => $value) {
            $checked =  $value->complete ? 'checked' : '';
            $id = $value->id;
            $text = $value->text;

            $html .= "<li class='es-task-item' id='line-item-{$id}'>";
            $html .= "<div class='flex flex-center py-20'><input type='checkbox' onchange='todo.updateItem( event, this )' data-id='{$id}' id='checkbox-{$id}' $checked  />";
            $html .= "<label contenteditable='true' data-checked='{$checked}' data-id='{$id}' id='todo-text-{$id}' onkeydown='todo.preventDefault(event)' onkeyup='todo.updateItem( event, this ), todo.createSubTask(event, this)'>{$text}</label></div>";

            foreach( $value as $item ) {
                if( is_object($item) ) {

                    $checked =  $item->complete ? 'checked' : '';
                    $id = $item->id;
                    $text = $item->text;

                    $html .= '<ul>';
                    $html .= "<li class='es-task-item' id='line-item-{$id}'>";
                    $html .= "<div class='flex flex-center py-20'><input type='checkbox' onchange='todo.updateItem( event, this )' data-id='{$id}' id='checkbox-{$id}' $checked  />";
                    $html .= "<label contenteditable='true' data-checked='{$checked}' data-id='{$id}' id='todo-text-{$id}' onkeydown='todo.preventDefault(event)' onkeyup='todo.updateItem( event, this ), todo.createSubTask(event, this)'>{$text}</label></div>";
                    $html .= "</li>";
                    $html .= '</ul>';
                }
            }

            $html .= "</li>";
        }
        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }

}