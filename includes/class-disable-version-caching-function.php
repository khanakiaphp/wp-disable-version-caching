<?php

class Disable_Version_Caching_Function
{
    /**
     * Single instance of the class.
     *
     * @var Disable_Version_Caching_Function
     */
    protected static $_instance = null;

    /**
     * The version of CSS and JS files.
     *
     * @var string
     */
    public $assets_version = '';

    /**
     * Disable_Version_Caching_Function instance.
     *
     * @static
     * @var array $args
     * @return Disable_Version_Caching_Function - Main instance
     */
    public static function instance( $args = array() )
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();

            $default_args = array(
                'assets_version' => time()
            );
        } else {
            $default_args = array(
                'assets_version' => self::$_instance->assets_version
            );
        }

        self::$_instance->assets_version = isset( $args['assets_version'] ) ? $args['assets_version'] : $default_args['assets_version'];

        return self::$_instance;
    }

    /**
     * Disable_Version_Caching_Function constructor.
     * @param $assets_version
     */
    public function __construct()
    {
        add_filter( 'style_loader_src', array( $this, 'add_query_arg' ), 10000 );
        add_filter( 'script_loader_src', array( $this, 'add_query_arg' ), 10000 );
    }

    /**
     * Adds query parameters to CSS and JS files.
     * @param $src
     * @return string
     */
    public function add_query_arg( $src )
    {
        if ( $this->assets_version ) {
            $src = add_query_arg( 'ver', $this->assets_version, $src );
        } else {
            $src = remove_query_arg( 'ver', $src );
        }

        return $src;
    }

}