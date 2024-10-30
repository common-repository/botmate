<?php

$triggers = botmate_get_triggers_classes();
$trigger_id = get_the_ID();
$sites = botmate_get_sites();
$trigger_config = botmate_get_trigger_config( $trigger_id );
$selected_trigger = ( $trigger_config && isset( $trigger_config['trigger'] ) ) ? $trigger_config['trigger'] : '';
$selected_site = ( $trigger_config && isset( $trigger_config['site'] ) ) ? $trigger_config['site'] : '';
$selected_action = ( $trigger_config && isset( $trigger_config['action'] ) ) ? $trigger_config['action'] : '';
$fetched_trigger_options = ( $trigger_config && isset( $trigger_config['fetched_trigger_options'] ) ) ? json_encode( $trigger_config['fetched_trigger_options'] ) : '';
$fetched_action_fields = ( $trigger_config && isset( $trigger_config['fetched_action_fields'] ) ) ? json_encode( $trigger_config['fetched_action_fields'] ) : '';

?>
    <div class="botmate">
        <div class="bm-trigger-config">
            <?php
            /**
             * Fires before trigger Configuration form
             *
             * @since 1.0
             */
            do_action( 'botmate_before_trigger_config_form' );
            ?>
            <input type="hidden" class="bm-security" value="<?php esc_attr_e( wp_create_nonce( 'bm-generate-api-key' ) ) ?>">
            <input type="hidden" name="fetched_trigger_options" class="fetched-trigger-options" value="<?php echo esc_attr( $fetched_trigger_options ); ?>">
            <input type="hidden" name="fetched_action_fields" class="fetched-action-fields" value="<?php echo esc_attr( $fetched_action_fields ); ?>">
            <table cellpadding="10">
                <tr class="bm-trigger-row">
                    <td>
                        <div><label for="">Select Trigger</label></div>
                        <select class="bm-triggers-select" name="bm_trigger">
                            <option value="">Select Trigger</option>
                            <?php

                            foreach ( $triggers as $trigger ) {

                                $class = new $trigger;
                                $selected = $class->id == $selected_trigger ? 'selected' : '';
                                ?>
                                <option value="<?php echo esc_attr( $class->id ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $class->title ); ?></option>
                                <?php

                            }
                            ?>
                        </select>
                        <div><sup>When trigger happen on current site.</sup></div>
                    </td>
                    <td>
                       <div><label for="">Select Site</label></div>
                        <select class="bm-triggers-site-select" name="bm_triggers_site">
                            <option value="">Select Site</option>
                            <?php
                            if( $sites ) {

                                foreach ( $sites as $site ) {
                                    ?>
                                    <option value="<?php echo esc_attr( $site['api_key'] ) ?>" data-site="<?php echo esc_url( $site['url'] ) ?>"><?php echo esc_html( $site['title'] ) ?></option>
                                    <?php

                                }

                            }
                            ?>
                        </select>
                        <div><sup>Where Action should happen.</sup></div>
                    </td>
                    <td>
                        <div><label for="">Select Action</label></div>
                        <select class="bm-triggers-action-select" name="bm_triggers_action"></select>
                        <div><sup>What should happen to the selected site.</sup></div>
                    </td>
                </tr>
                <tr class="bm-trigger-row">
                    <td>
                        <h1 class="bm-trigger-found"><?php echo $trigger_config ? 'When Trigger happen, Insert field to Action Input' : ''; ?></h1>
                    </td>
                </tr>
                <tbody class="bm-saved-automation-rows">
                <?php
                if( $trigger_config ) {

                    if( isset( $trigger_config['fetched_action_fields'] ) ) {

                        $row_count = 0;

                        foreach ( $trigger_config['fetched_action_fields'] as $key => $value ) {

                            //Start Table row
                            if( $row_count == 0 ) {

                                ?>
                                <tr class="bm-trigger-row">
                                <?php

                            }

                            $label = str_replace( '_', ' ', $key );

                            //Check Selected
                            if( isset( $trigger_config['selected_trigger_action'] ) ) {

                                $check_selected = str_replace( '$', '', $key );
                                $is_selected = array_key_exists( $check_selected, $trigger_config['selected_trigger_action'] );
                                $selected_value = '';

                                if( $is_selected ) {

                                    $selected_value = $trigger_config['selected_trigger_action'][$check_selected];

                                }

                            }
                            ?>
                                <td>
                                    <div><label for=""><?php echo esc_html( $label ); ?></label></div>
                                    <select class="bm-action-field <?php echo esc_attr( $key ); ?>" style="width: 100%;" name="bm_trigger_action[<?php echo esc_attr( $key ); ?>]">
                                        <option value="">Select Trigger Field</option>
                                        <?php
                                        if ( isset( $trigger_config['fetched_trigger_options'] ) ) {

                                            foreach ( $trigger_config['fetched_trigger_options'] as $tr_key => $tr_value ) {

                                                $selected = ( $selected_value == $tr_key ) ? 'selected' : '';

                                                ?>
                                                <option value="<?php echo esc_attr( $tr_key ); ?>" <?php echo esc_attr( $selected ); ?>>
                                                    <?php echo '{' . esc_attr( $tr_key ) . '} - ' . esc_attr( $tr_value ); ?>
                                                </option>
                                                <?php

                                            }

                                        }
                                        ?>
                                    </select>
                                    <div><sup><?php echo esc_html( $value->description ); ?></sup></div>
                                </td>
                            <?php

                            $row_count++;

                            //End Table Row
                            if( $row_count == 3 ) {

                                ?>
                                </tr>
                                <?php
                                $row_count = 0;

                            }

                        }

                    }


                    ?>
                    </tbody>
                    <?php

                }
                ?>
            </table>
            <?php
            /**
             * Fires after trigger Configuration form
             *
             * @since 1.0
             */
            do_action( 'botmate_after_trigger_config_form' );
            ?>
        </div>
    </div>
<?php
