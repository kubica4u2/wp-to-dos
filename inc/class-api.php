<?php

namespace ES\ToDos;

class Api extends \WP_REST_Controller {

    /**
     * Root REST API url.
     * @var string
     */
    protected $root = "todos";

    /**
     * Plugin REST API version.
     * @var string
     */
    protected $version = "v2";

    public function __construct() {
        add_action('init',[ $this,'add_cors_http_header' ]);
        add_action( 'rest_api_init', [ $this, 'register_new_routes' ] );
    }

    public function add_cors_http_header(){
        header("Access-Control-Allow-Origin: *");
    }

    public function register_new_routes() : void
    {
        register_rest_route( $this->root . "/" . $this->version, '/update_todos', array(
            array(
                'methods'  => 'POST',
                'callback' => array( $this, 'update_todos' ),
            ),
        ) );

        register_rest_route( $this->root . "/" . $this->version, '/create_todos', array(
            array(
                'methods'  => 'POST',
                'callback' => array( $this, 'create_todos' ),
            ),
        ) );

        register_rest_route( $this->root . "/" . $this->version, '/delete_todos', array(
            array(
                'methods'  => 'POST',
                'callback' => array( $this, 'delete_todos' ),
            ),
        ) );

    }


    /**
     * @param object $request REST API request object.
     */
    public function update_todos( $request ) : void
    {
        if(  ! empty( $request->get_body() ) ) {
            $todos =  json_decode( get_option( 'es_todos' ) );
            $request_data = json_decode( $request->get_body() );


            foreach ($todos as $key => $value) {

                 if( $value->id == $request_data->id ) {
                     $value->text = $request_data->text;
                     $value->complete = $request_data->checked;
                 }
            }

            update_option('es_todos', json_encode($todos) );

        }
    }

    public function create_todos( $request ) : void
    {
        if(  ! empty( $request->get_body() ) ) {
            $todos =  json_decode( get_option( 'es_todos' ), true );
            $request_data = json_decode( $request->get_body() );

            $create_record = true;

            foreach ($todos as $key => $value) {
                if( $value->id == $request_data->id ) {
                    $create_record = false;
                }
            }

            if( $create_record ) {
                $new_item = [
                    'id' => $request_data->id,
                    'complete' => $request_data->checked,
                    'text' => $request_data->text
                ];

                $todos[] = $new_item;

                update_option('es_todos', json_encode($todos) );

            }
        }
    }

    public function delete_todos( $request ) : void
    {
        if(  ! empty( $request->get_body() ) ) {
            $todos = json_decode(get_option('es_todos'), true);
            $request_data = json_decode($request->get_body());

            foreach ($todos as $key => $value) {
                if( $value['id'] == $request_data->id ) {
                    unset( $todos[$key] );
                }
            }

            update_option('es_todos', json_encode($todos) );

        }
    }

}