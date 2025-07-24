<!-- business/views/redirect_to_bank.php -->
<?php
$token = $data['token'] ?? '';
$order_amount = $order_amount ?? '';
$order_id = $order_id ?? '';
$redirect_url = $redirect_url_business_confirm.'token='.$token.'&order_id='.$order_id.'&order_status=1' ?? '';
// $token = $token ?? '';
$h1 = $h1 ?? '';
?>
<form id="redirectForm" method="POST" action="https://10.13.0.45/payement">
  <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
  <input type="hidden" name="h1" value="<?= htmlspecialchars($h1) ?>">
  <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>">
  <input type="hidden" name="redirect_url" value="<?= htmlspecialchars($redirect_url) ?>">
  <input type="hidden" name="order_amount" value="<?= htmlspecialchars($order_amount) ?>">
</form>

<script>
  document.getElementById('redirectForm').submit();
</script>
