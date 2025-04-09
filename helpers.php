<?php
// helpers.php

// Start session if not already started
function session_start_if_needed() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Set flash message
function set_message($message, $type = 'info') {
    session_start_if_needed();
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

// Redirect to URL
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// Get current page URL
function current_url() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    return $protocol . $domainName . $uri;
}

// Format date
function format_date($dateString, $format = 'M j, Y') {
    $date = new DateTime($dateString);
    return $date->format($format);
}

// Sanitize output for HTML display
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
