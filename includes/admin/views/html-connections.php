<div class="wrap">
    <div class="botmate">
        <h1>Sites Connections</h1>
        <input type="hidden" class="bm-security" value="<?php esc_attr_e( wp_create_nonce( 'bm-security' ) ) ?>">
        <?php
        /**
         * Fires before sites configuration
         *
         * @since 1.0
         */
        do_action( 'botmate_before_sites_connection' );

        $sites = botmate_get_sites();
        if( $sites ) {
            foreach( $sites as $id => $site ) {
                ?>
                <div class="bm-site-configuration" data-id="<?php esc_attr_e( $id ) ?>">
                    <label>Title <input type="text" value="<?php esc_attr_e( $site['title'] ) ?>" class="bm-title" /></label>
                    <label>Site URL <input type="text" value="<?php esc_attr_e( $site['url'] ) ?>" class="bm-url" /></label>
                    <label>API Key <input type="text" value="<?php esc_attr_e( $site['api_key'] ) ?>" class="bm-api-key" /></label>
                    <button class="button button-secondary bm-test-site-connection">Test Connection <div class="bm-loader"></div></button>
                    <button class="button button-primary bm-remove-site">Remove Site</button>
                    <div class="bm-site-status"></div>
                </div>
                <?php
            }
        }
        else {
            ?>
            <div class="bm-site-configuration" data-id="0">
                <label>Title <input type="text" class="bm-title" /></label>
                <label>Site URL <input type="text" class="bm-url" /></label>
                <label>API Key <input type="text" class="bm-api-key" /></label>
                <button class="button button-secondary bm-test-site-connection">Test Connection</button>
                <button class="button button-primary bm-remove-site">Remove Site</button>
                <div class="bm-site-status"></div>
            </div>
            <?php
        }
        ?>

        <button class="button button-primary bm-add-site">Add Site</button>
        <button class="button button-primary bm-save-sites">Save <div class="bm-loader"></div></button>
    </div>
    <?php
    /**
     * Fires after sites configuration
     *
     * @since 1.0
     */
    do_action( 'botmate_after_sites_connection' )
    ?>
</div>
