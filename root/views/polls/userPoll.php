<?php
require_once __DIR__ . "/../../controllers/PollController.php";

$pollController = new PollController();
$polls = $pollController->index(); // همه نظرسنجی‌ها

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Polls</title>
    <link rel="stylesheet" href="style.css">
</head>

<body style="direction:rtl;font-family:sans-serif;">
    <div class="container">
        <button><a href="http://localhost/voting%20system/mvcVote/root/logout">خروج</a></button>
        <h1>همه کارت های نظر سنجی</h1>

        <?php if (!empty($polls)) : ?>
            <?php foreach ($polls as $poll) : ?>
                <div class="poll-card">
                    <h2><?= ($poll['title']) ?></h2>
                    <p><?= ($poll['description']) ?></p>

                    <?php
                    $options = $pollController->showPoll($poll['id']);
                    if ($options['success']) :
                    ?>
                        <form method="POST" action="vote">
                            <?php foreach ($options['option'] as $option) : ?>
                                <div class="option">
                                    <label>
                                        <input type="radio" name="option_id" value="<?= $option['id'] ?>" required>
                                        <?= htmlspecialchars($option['title']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <input type="hidden" name="poll_id" value="<?= $poll['id'] ?>">
                            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? 1 ?>">
                            <button type="submit" class="vote-btn">Vote</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No polls found.</p>
        <?php endif; ?>
    </div>
</body>

</html>