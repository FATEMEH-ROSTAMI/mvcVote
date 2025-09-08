<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Poll.php';
require_once __DIR__ . '/../controllers/AuthController.php';

class AdminController {
    private $userModel;
    private $pollModel;
    private $authController;
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        $this->userModel = new User();
        $this->pollModel = new Poll();
        $this->authController = new AuthController();
    }

    private function checkAdmin() {
        if (!$this->authController->isLoggedIn()) {
            return ['success' => false, 'message' => 'لطفاً ابتدا وارد شوید', 'redirect' => 'login'];
        }
        if (!$this->authController->isAdmin()) {
            return ['success' => false, 'message' => 'دسترسی غیرمجاز: فقط ادمین‌ها مجازند', 'redirect' => 'poll'];
        }
        return ['success' => true];
    }

    public function dashboard() {
        $check = $this->checkAdmin();
        if (!$check['success']) {
            return $check;
        }
        return ['success' => true, 'view' => 'admin/dashboard.php', 'message' => 'خوش آمدید به داشبورد ادمین'];
    }

    public function listUsers() {
        $check = $this->checkAdmin();
        if (!$check['success']) {
            return $check;
        }
        $users = $this->userModel->getAll();
        return ['success' => true, 'users' => $users, 'view' => 'admin/users.php'];
    }

    public function showCreatePoll() {
        $check = $this->checkAdmin();
        if (!$check['success']) {
            return $check;
        }
        $csrf_token = $this->authController->generateCSRFToken();
        return ['success' => true, 'csrf_token' => $csrf_token, 'view' => 'admin/create_poll.php'];
    }

    public function createPoll($title, $options, $csrf_token) {
        $check = $this->checkAdmin();
        if (!$check['success']) {
            return $check;
        }

        if (!$this->authController->verifyCSRFToken($csrf_token)) {
            return ['success' => false, 'message' => 'توکن CSRF نامعتبر است', 'view' => 'admin/create_poll.php'];
        }

        if (empty($title) || empty($options) || !is_array($options) || count($options) < 2) {
            return ['success' => false, 'message' => 'عنوان و حداقل دو گزینه برای نظرسنجی لازم است', 'view' => 'admin/create_poll.php'];
        }

        $poll_id = $this->pollModel->create($title, $_SESSION['user_id']);
        if (!$poll_id) {
            return ['success' => false, 'message' => 'خطا در ایجاد نظرسنجی', 'view' => 'admin/create_poll.php'];
        }

        foreach ($options as $option_text) {
            if (!empty($option_text)) {
                $this->pollModel->addOption($poll_id, $option_text);
            }
        }

        return ['success' => true, 'poll_id' => $poll_id, 'message' => 'نظرسنجی با موفقیت ایجاد شد', 'redirect' => 'admin/dashboard'];
    }

    public function deletePoll($poll_id, $csrf_token) {
        $check = $this->checkAdmin();
        if (!$check['success']) {
            return $check;
        }

        if (!$this->authController->verifyCSRFToken($csrf_token)) {
            return ['success' => false, 'message' => 'توکن CSRF نامعتبر است', 'view' => 'admin/dashboard.php'];
        }

        if ($this->pollModel->delete($poll_id)) {
            return ['success' => true, 'message' => 'نظرسنجی با موفقیت حذف شد', 'redirect' => 'admin/dashboard'];
        }
        return ['success' => false, 'message' => 'خطا در حذف نظرسنجی', 'view' => 'admin/dashboard.php'];
    }

    public function viewPollResults($poll_id) {
        $check = $this->checkAdmin();
        if (!$check['success']) {
            return $check;
        }

        $poll = $this->pollModel->getById($poll_id);
        if (!$poll) {
            return ['success' => false, 'message' => 'نظرسنجی پیدا نشد', 'view' => 'admin/dashboard.php'];
        }

        $options = $this->pollModel->getOptions($poll_id);
        $results = $this->pollModel->getResults($poll_id);

        return ['success' => true, 'poll' => $poll, 'options' => $options, 'results' => $results, 'view' => 'admin/poll_results.php'];
    }
}
?>