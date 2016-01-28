<?php

function redirect_to($url) {
    header("Location: " . $url);
    exit;
}

function terminate_script($error_message) {
    if (!is_on_production()) {
        echo $error_message;
        debug_print_backtrace();
    } else {
        echo "An Error Occurred";
    }
    die();
}

function __autoload($class_name) {
    $class_name = strtolower($class_name);
    $path = PRIVATE_PATH . "/{$class_name}.class.php";
    if (file_exists($path)) {
        require_once($path);
    }
    else {
        terminate_script("The file {$class_name}.class.php could not be found.");
    }
}

// Validation

function has_presence($value) {
    $trimmed_value = trim($value);
    return isset($trimmed_value) && $trimmed_value !== "";
}

function has_length($value, $options=[]) {
    if(isset($options['max']) && (strlen($value) > (int)$options['max'])) {
        return false;
    }
    if(isset($options['min']) && (strlen($value) < (int)$options['min'])) {
        return false;
    }
    if(isset($options['exact']) && (strlen($value) != (int)$options['exact'])) {
        return false;
    }
    return true;
}

function has_format_matching($value, $regex='//') {
    return preg_match($regex, $value);
}

function has_number($value, $options=[]) {
    if(!is_numeric($value)) {
        return false;
    }
    if(isset($options['max']) && ($value > (int)$options['max'])) {
        return false;
    }
    if(isset($options['min']) && ($value < (int)$options['min'])) {
        return false;
    }
    return true;
}

function has_inclusion_in($value, $set=[]) {
  return in_array($value, $set);
}

function has_exclusion_from($value, $set=[]) {
  return !in_array($value, $set);
}

function escape_adwords_customer_id($adwords_id) {
    return str_replace('-', '', $adwords_id);
}

function display_adwords_account_id($adwords_id) {
    if ((is_numeric($adwords_id)) && (strlen($adwords_id) === CHARS_IN_ADWORDS_ID))
        return substr($adwords_id, 0, 3) . "-" . substr($adwords_id, 3, 3) . "-" . substr($adwords_id, 6);
    return $adwords_id;
}

function form_errors_push($errors, $type = "success") {
    $output = "";
    if (!empty($errors)) {
        $output = "<div class=\"alert alert-{$type} alert-dismissible\" role=\"alert\">";
        $output .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
        $output .= "<strong>Notice!</strong>";
        foreach ($errors as $error) {
            $output .= "<br />{$error}";
        }
        $output .= "</div>";
    }

    return $output;
}

// Defend against XSS - sanitize any dynamic data

// Sanitize for HTML output
function h($string) {
    return htmlspecialchars($string);
}

// Sanitize for JavaScript output
function j($string) {
    return json_encode($string);
}

// Sanitize for use in a URL
function u($string) {
    return urlencode($string);
}

function ud($string) {
    return urldecode($string);
}

function mysql_now() {
    return date("Y-m-d H:i:s");
}

function mysql_time($time) {
    return date("Y-m-d H:i:s", $time);
}

function display_datetime($time) {
    return date("F j, Y, g:i a", strtotime($time));
}

function execute_in_background_process($script, $output = '\dev\null', $errors_output = 'error_log.txt') {
    exec("php " . $script . " >> " . LOG_PATH . '/' . $output . " 2> " . LOG_PATH . '/' . $errors_output . " &");
}
