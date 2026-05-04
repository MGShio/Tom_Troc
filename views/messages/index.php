<section class="section">
    <h2>Mes Messages</h2>
    <?php if (empty($correspondants)): ?>
        <p>Vous n'avez pas de messages.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($correspondants as $correspondant): ?>
                <li><a href="?controller=message&action=conversation&id=<?= $correspondant->getId() ?>"><?= htmlspecialchars($correspondant->getNom()) ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>