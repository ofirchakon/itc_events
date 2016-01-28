<?php

class Session {

    private $logged_in = false;
    private $admin_logged_in = false;
    private $registering = false;
    public $user_id;
    public $admin_id;
    public $current_page;
    public $access_token;

    public function __construct() {
        session_start();
        $this->check_current_page();
        $this->check_login();
        $this->check_access_token();
        $this->check_admin_login();
        $this->check_registration();
    }

    public function is_logged_in() {
        return $this->logged_in;
    }

    public function is_admin_logged_in() {
        return $this->admin_logged_in;
    }

    public function is_registering() {
        return $this->registering;
    }

    public function login(UserDB $user) {
        session_regenerate_id();

        if ($user) {
            $this->user_id = $_SESSION['user_id'] = $user->id;
            $_SESSION['logged_in'] = true;
            $this->logged_in = true;

            $user->update_last_login();
        }
    }

    public function admin_login(Admin $admin) {
        session_regenerate_id();

        if ($admin) {
            $this->admin_id = $_SESSION['admin_id'] = $admin->id;
            $_SESSION['admin_logged_in'] = true;
            $this->admin_logged_in = true;

            $admin->update_last_login();
        }
    }

    public function register($user) {
        session_regenerate_id();

        if ($user) {
            $this->user_id = $_SESSION['user_id'] = $user->id;
            $_SESSION['registering'] = true;
            $this->registering = true;
        }
    }

    public function set_access_token($access_token) {
        session_regenerate_id();

        if ($access_token) {
            $_SESSION['access_token'] = $access_token;
            $this->access_token = $access_token;
        }
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['logged_in']);
        unset($_SESSION['access_token']);
        unset($this->user_id);
        unset($this->access_token);
        $this->logged_in = false;
    }

    public function admin_logout() {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_logged_in']);
        unset($this->admin_id);
        $this->admin_logged_in = false;
    }

    public function unregister() {
        unset($_SESSION['user_id']);
        unset($_SESSION['registering']);
        unset($_SESSION['access_token']);
        unset($this->user_id);
        unset($this->access_token);
        $this->registering = false;
    }

    public function current_page($page = 0) {
        if ($page != 0) {
            $_SESSION['current_page'] = $page;
        }
        else {
            return $this->current_page;
        }
        return null;
    }

    private function check_registration() {
        if (isset($_SESSION['registering'])) {
            $this->user_id = $_SESSION['user_id'];
            $this->registering = true;
        }
        else {
//				unset($this->user_id);
            $this->registering = false;
        }
    }

    private function check_login() {
        if (isset($_SESSION['logged_in'])) {
            $this->user_id = $_SESSION['user_id'];
            $this->logged_in = true;
        }
        else {
            unset($this->user_id);
            $this->logged_in = false;
        }
    }

    private function check_admin_login() {
        if (isset($_SESSION['admin_logged_in'])) {
            $this->admin_id = $_SESSION['admin_id'];
            $this->admin_logged_in = true;
        }
        else {
            unset($this->admin_id);
            $this->admin_logged_in = false;
        }
    }

    private function check_current_page() {
        if (isset($_SESSION['current_page'])) {
            $this->current_page = $_SESSION['current_page'];
        }
        else {
            $this->current_page = 1;
        }
    }

    private function check_access_token() {
        if (isset($_SESSION['access_token'])) {
            $this->access_token = $_SESSION['access_token'];
        }
    }
}

$session = new Session();
$current_page = $session->current_page;