<?php
$token = $data['token'] ?? '';

?>

<form id="redirectForm" method="POST" action=<?= htmlspecialchars($data['url']) ?>>
  <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
</form>

<script>
  document.getElementById('redirectForm').submit();
</script>
