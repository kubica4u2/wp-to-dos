<?php

namespace ES\ToDos;

use ES\ToDos\Markup;

class Settings {

    public function __construct() {
        add_action( 'admin_menu', [ $this,'todos_add_settings_page' ] );
        add_action( 'admin_init', [ $this,'todos_register_settings'] );
    }

    public function todos_add_settings_page() : void
    {
        add_options_page(
            'To Dos',
            'To Dos',
            'manage_options',
            'todos',
            [$this,'todos_plugin_settings_page']
        );
    }

    public function todos_plugin_settings_page() : void
    {
        ?>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'todos_display_mode' );
            do_settings_sections( 'todos' ); ?>
            <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
        </form>

        <?php

        echo ( new Markup() )->init();;

    }

    public function todos_register_settings() : void
    {
        register_setting( 'todos_display_mode', 'todos_display_mode' );
        add_settings_section( 'display_mode', 'To Dos Settings', [$this,'todos_section_text'], 'todos' );
        add_settings_field( 'todos_display_mode', 'Display Mode', [$this,'todos_display_mode'], 'todos', 'display_mode' );
    }

    public function todos_section_text() : void
    {
        echo '<p>Change the color of your todo list</p>';
    }

    public function todos_display_mode() : void
    {
        $options = get_option( 'todos_display_mode' );
        ?>
         <select name='todos_display_mode[display_mode]'>
             <option value='light' <?php selected( $options['display_mode'], 'light' ); ?>>Light</option>
             <option value='dark' <?php selected( $options['display_mode'], 'dark' ); ?>>Dark</option>
         </select>
        <?php
    }

}
