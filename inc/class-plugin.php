<?php

namespace ES\ToDos;

class Plugin {
	
	public function __construct() {}
	
	public function activate() {}
	
	public function deactivate() {}
	
	private function loadDependencies() : void
    {
        require_once( ESINC . 'class-api.php' );
        require_once( ESINC . 'class-init.php' );
        require_once( ESINC . 'class-markup.php' );
        require_once( ESINC . 'class-settings.php' );
        require_once( ESINC . 'class-app.php' );

        new Init();
        new Api();
        new Markup();
        new Settings();
        new App();
	}
	
	public function run() : void
    {
		$this->loadDependencies();
	}
}