<?php

class Disable_Version_Caching_Admin_Settings
{

    /**
     * Disable_Version_Caching_Admin_Settings Constructor.
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        add_action( 'wp_ajax_pbc_update_clear_cache_time', array( $this, 'update_clear_cache_time' ) );
    }

    /**
     * Add options page.
     */
    public function add_plugin_page()
    {
        add_options_page(
            __( 'Disable Version Caching', 'disable-version-caching' ),
            __( 'Disable Version Caching', 'disable-version-caching' ),
            'manage_options',
            'disable-version-caching',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback.
     */
    public function create_admin_page()
    {
        ?>
        <div class="wrap">
            <h1>Disable Version Caching</h1>
            <?php if ( class_exists( 'Disable_Version_Caching' ) ): ?>
                <div id="pbc_notices">
                    <div class="updated settings-error notice pbc-notice pbc-notice-update-caching-time" style="display: none">
                        <p><strong><?php _e( 'CSS and JS files have been updated successfully.', 'disable-version-caching' ); ?></strong></p>
                        <button type="button" class="notice-dismiss" onclick="pbc_close_notice(this)"><span class="screen-reader-text"><?php _e( 'Dismiss this notice.' ); ?></span></button>
                    </div>
                </div>

                <form method="post" action="<?php echo admin_url( 'options.php '); ?>">
                    <?php

                    settings_fields( 'disable_version_caching_options_group' );
                    do_settings_sections( 'disable-version-caching' );
                    submit_button();

                    ?>
                </form>
            <?php endif; ?>
            <?php if ( class_exists( 'Disable_Version_Caching_Function' ) ): ?>
                <?php

                $assets_version = Disable_Version_Caching_Function::instance()->assets_version;

                ?>
                <p>NOTE: The assets version of CSS and JS files will be always <strong><?php echo $assets_version; ?></strong>. It is set by this code:</p>
                <code style="display: block;">
                    disable_version_caching( array(<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;'assets_version' => '<?php echo $assets_version; ?>'<br>
                    ) );
                </code>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Register and add settings.
     */
    public function page_init()
    {
        register_setting(
            'disable_version_caching_options_group', // Option group
            'disable_version_caching_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'disable_version_caching_settings', // ID
            null, // Title
            null, // Callback
            'disable-version-caching' // Page
        );

        add_settings_field(
            'always_clear_cache',
            __( 'Automatically updating CSS and JS files for a site visitor', 'disable-version-caching' ),
            array( $this, 'clear_cache_automatically_callback' ),
            'disable-version-caching',
            'disable_version_caching_settings'
        );
        add_settings_field(
            'update_css_js_files',
            __( 'Manually update CSS and JS files for all site visitors', 'disable-version-caching' ),
            array( $this, 'clear_cache_manually_callback' ),
            'disable-version-caching',
            'disable_version_caching_settings'
        );
    }

    /**
     * Sanitize each setting field as needed.
     *
     * @param $input
     * @return mixed
     */
    public function sanitize( $input )
    {
        return Disable_Version_Caching::instance()->filter_options( $input );
    }

    /**
     * Displays options to clear cache automatically.
     */
    public function clear_cache_automatically_callback()
    {
        $options = Disable_Version_Caching::instance()->get_options();
        $clear_cache_automatically = $options['clear_cache_automatically'];
        $clear_cache_automatically_minutes = $options['clear_cache_automatically_minutes'];
        ?>

        <label>
            <input type="radio" name="disable_version_caching_options[clear_cache_automatically]" value="every_time"<?php echo $clear_cache_automatically == 'every_time' ? ' checked' : ''; ?> />
            <?php _e( 'Every time a user loads a page', 'disable-version-caching' ); ?>
        </label><br>
        <label>
            <input type="radio" name="disable_version_caching_options[clear_cache_automatically]" value="every_period"<?php echo $clear_cache_automatically == 'every_period' ? ' checked' : ''; ?> />
            <?php _e( 'Every', 'disable-version-caching' ); ?> <input type="number" name="disable_version_caching_options[clear_cache_automatically_minutes]" value="<?php echo $clear_cache_automatically_minutes ?>" step="1" min="1" max="99999" style="width: 65px"> <?php _e( 'minutes', 'disable-version-caching' ); ?>
        </label><br>
        <label>
            <input type="radio" name="disable_version_caching_options[clear_cache_automatically]" value="never"<?php echo $clear_cache_automatically == 'never' ? ' checked' : ''; ?> />
            <?php _e( 'Do not update automatically', 'disable-version-caching' ); ?>
        </label><br>

        <?php
    }

    /**
     * Displays options to clear cache manually.
     */
    public function clear_cache_manually_callback()
    {
        $options = Disable_Version_Caching::instance()->get_options();
        $show_on_toolbar = $options['show_on_toolbar'];
        ?>

        <label>
            <input type="checkbox" name="disable_version_caching_options[show_on_toolbar]" value="1"<?php echo $show_on_toolbar ? ' checked' : ''; ?> />
            <?php _e( 'Show "Update CSS/JS" button on the toolbar', 'disable-version-caching' ); ?>
        </label><br><br>

        <button class="button" onclick="pbc_update_clear_cache_time(this)"><?php _e( 'Update CSS and JS files now', 'disable-version-caching' ); ?></button>

        <script>
            function pbc_close_notice(element) {
                jQuery(element).parents('.pbc-notice').fadeOut('fast');
            }

            function pbc_update_clear_cache_time( element ) {
                var update_button = jQuery( element );

                var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';

                var data = {
                    action: 'pbc_update_clear_cache_time',
                    nonce: '<?php echo wp_create_nonce( 'pbc_update_clear_cache_time' ) ?>'
                };

                update_button.attr('disabled', true);
                jQuery.post(ajax_url, data, function() {
                    update_button.attr('disabled', false );
                    jQuery('.pbc-notice-update-caching-time').hide().addClass('is-dismissible').fadeIn('fast');
                });
            }
        </script>

        <?php
    }

    /**
     * Ajax actions to clear cache manually.
     */
    public function update_clear_cache_time()
    {
        check_ajax_referer( 'pbc_update_clear_cache_time', 'nonce' );

        update_option( 'disable_version_caching_clear_cache_time', Disable_Version_Caching::instance()->get_time_code() );

        exit;
    }

}

new Disable_Version_Caching_Admin_Settings();