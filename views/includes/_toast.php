<?php if (!empty($_SESSION['toast'])): ?>
    <?php
        $toastMessage = htmlspecialchars($_SESSION['toast']['message']);
        $toastType = $_SESSION['toast']['type'] === 'error' ? 'toast-error' : 'toast-success';
        if ( $_SESSION['toast']['type'] === '') $toastType='';

    ?>
    <div id="myToast" class="toast <?= $toastType ?>"><?= $toastMessage ?></div>
    <script>
        const toast = document.getElementById('myToast');
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 5000);
    </script>
    <?php unset($_SESSION['toast']); ?>
<?php endif; ?>

