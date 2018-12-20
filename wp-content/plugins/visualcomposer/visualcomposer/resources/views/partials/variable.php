<?php
if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}
/** @var array $values */
/** @var string $key */
?>
<script id="vcv-<?php echo esc_attr(vchelper('Str')->slugify($key)); ?>">
  // Write-able data
  // @codingStandardsIgnoreLine
  window.<?php echo esc_js($key); ?> = <?php echo json_encode($value); ?>;
</script>