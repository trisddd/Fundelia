<h1 class="title">Notifications</h1>
<?php
$grouped_notifications = [];

foreach ($notifications as $notif) {
    $dateKey = date("Y-m-d", strtotime($notif['date']));
    if (!isset($grouped_notifications[$dateKey])) {
        $grouped_notifications[$dateKey] = [];
    }
    $grouped_notifications[$dateKey][] = $notif;
}
?>


<?php foreach ($grouped_notifications as $date => $day_notifications): ?>
    <div class="notification-group">
        <div class="notification-date"><?= date("d M Y", strtotime($date)) ?></div>

        <?php foreach ($day_notifications as $notif): ?>
            <div class="notification-content">
                <div class="notification-avatar">
                    <?php if (!$notif['status']): ?>
                                            <div class="notification-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="12" r="10" />
                        </svg>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="notification-bubbles">
                    <div class="bubble notification <?= $notif['status'] ? 'read light' : 'unread blue' ?>"
                        data-title="<?= htmlspecialchars($notif['title']) ?>"
                        data-description="<?= htmlspecialchars($notif['message']) ?>" data-id="<?= $notif['id'] ?>">
                        <?= htmlspecialchars($notif['message']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
<?php if (empty($grouped_notifications)): ?>
    <p class="no-notifications">Aucune notification pour l'instant.</p>
<?php endif; ?>
<div id="modal" class="modal hidden">
    <div class="modal-content">
        <span class="modal-close">&times;</span> <!-- ← nécessaire -->
        <h2 id="modal-title">Titre</h2> <!-- ← nécessaire -->
        <p id="modal-description">Description</p>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("modal");
        const modalTitle = document.getElementById("modal-title");
        const modalDescription = document.getElementById("modal-description");
        const closeBtn = document.querySelector(".modal-close");

        document.querySelectorAll(".notification").forEach(el => {
            el.addEventListener("click", () => {
                const title = el.dataset.title;
                const description = el.dataset.description;
                const notifId = el.dataset.id;

                modalTitle.textContent = title;
                modalDescription.textContent = description;
                modal.classList.remove("hidden");

                if (el.classList.contains("unread")) {
                    el.classList.remove("unread", "blue");
                    el.classList.add("read", "light");
                    //el.classList.add("read");

                    // Changement visuel de l’avatar
                    const avatar = el.closest(".notification-content").querySelector(".notification-avatar");
                    //avatar.classList.add("read");
                    avatar.innerHTML = ""; // Supprimer l’icône

                    // Requête vers le contrôleur
                    fetch('/change_notification_state', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        credentials: 'same-origin',
                        body: 'notif_id=' + encodeURIComponent(notifId)
                    });
                }
            });
        });

        closeBtn.addEventListener("click", () => modal.classList.add("hidden"));
        window.addEventListener("click", (e) => {
            if (e.target === modal) modal.classList.add("hidden");
        });
    });

</script>