<?php
require_once 'init.php';
// Simple router
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Handle AJAX requests
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    
    if ($_GET['ajax'] == 'generate_client_code' && isset($_POST['name'])) {
        $name = $_POST['name'];
        $clientCode = Client::generateClientCode($name);
        echo json_encode(['client_code' => $clientCode]);
        exit;
    }
    
    exit;
}

// Page header
include 'templates/header.php';

// Page content
switch ($page) {
    case 'clients':
        include 'pages/clients.php';
        break;
    case 'client_form':
        include 'pages/client_form.php';
        break;
    case 'client_view':
        include 'pages/client_view.php';
        break;
    case 'contacts':
        include 'pages/contacts.php';
        break;
    case 'contact_form':
        include 'pages/contact_form.php';
        break;
    case 'contact_view':
        include 'pages/contact_view.php';
        break;
    default:
        include 'pages/home.php';
}

// Page footer
include 'templates/footer.php';
