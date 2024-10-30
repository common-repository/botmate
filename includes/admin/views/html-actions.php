<?php

$actions = botmate_get_actions_classes();
$action_id = get_the_ID();

?>
    <div class="botmate">
        <div class="bm-action-config">
            <?php
            /**
             * Fires before Action Configuration form
             *
             * @since 1.0
             */
            do_action( 'botmate_before_action_config_form' );
            ?>
            <input type="hidden" class="bm-security" value="<?php esc_attr_e( wp_create_nonce( 'bm-generate-api-key' ) ) ?>">
            <table cellpadding="10">
                <tr>
                    <td>API Key:</td>
                    <td><input type="text" class="bm-api-key" value="<?php echo esc_attr( botmate_get_api_key( $action_id ) ); ?>" name="bm_api_key" /><button class="button button-primary bm-generate-api-key">Generate Key <div class="bm-loader"></div></button></td>
                </tr>
                <tr>
                    <td>Allowed Actions: </td>
                    <td>
                        <select class="bm-actions-select" style="width: 40%;" multiple="multiple" name="bm_actions[]">
                            <?php
                            $saved_actions = botmate_get_saved_actions( $action_id );

                            foreach ( $actions as $action ) {

                                $class = new $action;
                                $selected = ( is_array( $saved_actions ) && in_array( $class->id, $saved_actions ) ) ? 'selected' : '';
                                ?>
                                <option value="<?php echo esc_attr( $class->id ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $class->title ); ?></option>
                                <?php

                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Site URL:</td>
                    <td><?php echo esc_attr( get_site_url() ) . '/'; ?></td>
                </tr>
            </table>
            <?php
            /**
             * Fires after Action Configuration form
             *
             * @since 1.0
             */
            do_action( 'botmate_after_action_config_form' );
            ?>
        </div>
    </div>
<?php
