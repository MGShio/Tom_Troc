<section class="section">
    <h2>Conversation avec <?= htmlspecialchars($correspondant->getNom()) ?></h2>    <?php if (isset($_SESSION['message_error'])): ?>
        <p style="color: red;"><?= $_SESSION['message_error'] ?></p>
        <?php unset($_SESSION['message_error']); ?>
    <?php endif; ?>    <div class="messages">
        <?php foreach ($messages as $message): ?>
            <div class="message <?= $message->getExpediteurId() == $_SESSION['user_id'] ? 'sent' : 'received' ?>">
                <p><?= htmlspecialchars($message->getContenu()) ?></p>
                <small><?= htmlspecialchars($message->getDateEnvoi()) ?></small>
            </div>
        <?php endforeach; ?>
    </div>
    <form method="post" action="?action=send">
        <input type="hidden" name="destinataire_id" value="<?= $correspondant->getId() ?>">
        <textarea name="contenu" required></textarea>
        <button type="submit">Envoyer</button>
    </form>
</section>