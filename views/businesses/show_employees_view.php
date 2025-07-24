<a href="/add_employee/<?= urlencode($business_name); ?>" class="add-employee-link"><button class="add-employee-button">Ajouter un membre</button></a>

<h1>Managers</h1>
<?php foreach ($employees["manager"] as $manager): ?>
    <div class="employee-item">
        <span><?= htmlspecialchars($manager['first_name']." ".$manager["last_name"], ENT_QUOTES, 'UTF-8') ?></span>
    </div>
<?php endforeach; ?>            
<h1>Employé(e)s</h1>
<?php foreach ($employees["employee"] as $employee): ?>
    <div class="employee-item">
        <span><?= htmlspecialchars($employee['first_name']." ".$employee["last_name"], ENT_QUOTES, 'UTF-8') ?></span>
    </div>
<?php endforeach; ?>
