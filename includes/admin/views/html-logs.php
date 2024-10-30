<?php

$logs = new \BotMate\MenuLogs();
$logs->prepare_items();
$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
?>
<div class="botmate wrap">
    <h1>Logs</h1>
    <form id="bm-logs" method="get">
        <input type="hidden" name="page" value="<?php echo esc_attr( $page ) ?>" />
        <?php $logs->display() ?>
    </form>
</div>

