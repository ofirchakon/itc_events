<?php

class Hash {

    public $value;
    public $hashed_value;

    public static function verify($value_to_check, $existing_value) {
        return password_verify($value_to_check, $existing_value);
    }

    public static function encrypt($value) {
        return password_hash($value, PASSWORD_BCRYPT);
    }

    public function is_equal_to($hashed_value_to_check) {
        return password_verify($hashed_value_to_check, $this->hashed_value);
    }

    public function encrypt_value() {
        $this->hashed_value = password_hash($this->value, PASSWORD_BCRYPT);
    }
}
