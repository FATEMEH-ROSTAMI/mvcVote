<?php
require_once __DIR__ . "/../../controllers/PollController.php";
$pollController = new PollController();
$polls = $pollController->index();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>پنل مدیریت نظرسنجی</title>
    <link rel="stylesheet" href="../../public/dashboard.css">
</head>

<body>
    <button><a href="http://localhost/voting%20system/mvcVote/root/logout">خروج</a></button>

    <h1>پنل مدیریت نظرسنجی‌ها</h1>

    <!-- فرم ساخت نظرسنجی جدید -->
    <div class="create-form">
        <h2>ایجاد نظرسنجی جدید</h2>
        <form method="POST" action="/poll/create">
            <div class="form-group">
                <label>عنوان نظرسنجی:</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>گزینه‌ها:</label>
                <input type="text" name="options[]" placeholder="گزینه ۱" required>
                <input type="text" name="options[]" placeholder="گزینه ۲" required>
                <div id="extra-options"></div>
                <button type="button" class="btn-add" onclick="addOption()">➕ افزودن گزینه</button>
            </div>

            <button type="submit" class="btn-submit">ایجاد نظرسنجی</button>
        </form>
    </div>

    <!-- لیست نظرسنجی‌ها -->
    <div class="dashboard">
        <?php foreach ($polls as $poll): ?>
            <div class="card">
                <h3><?= htmlspecialchars($poll['title']); ?></h3>
                <?php
                $totalVotes = array_sum(array_column($poll['options'], 'votes'));
                if ($totalVotes == 0) $totalVotes = 1;
                ?>
                <?php foreach ($poll['options'] as $option):
                    $percent = ($option['votes'] > 0) ? round(($option['votes'] / $totalVotes) * 100) : 0;
                ?>
                    <div class="option">
                        <span><?= htmlspecialchars($option['option_text']); ?> (<?= $option['votes']; ?> رأی)</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?= $percent; ?>%;">
                                <?= $percent; ?>%
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function addOption() {
            const container = document.getElementById("extra-options");
            const input = document.createElement("input");
            input.type = "text";
            input.name = "options[]";
            input.placeholder = "گزینه جدید";
            input.required = true;
            container.appendChild(input);
        }
    </script>
</body>

</html>