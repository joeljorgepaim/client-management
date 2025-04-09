<?php
// pages/client_form.php

// Initialize variables
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : '';
$errors = [];
$client = null;

// Check if it's an edit form
if ($id) {
    $client = Client::getById($id);
    if (!$client) {
        $_SESSION['message'] = 'Client not found.';
        $_SESSION['message_type'] = 'danger';
        header('Location: ' . BASE_URL . '?page=clients');
        exit;
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle client deletion
    if ($action === 'delete' && isset($_POST['id'])) {
        $client = Client::getById((int)$_POST['id']);
        if ($client && $client->delete()) {
            $_SESSION['message'] = 'Client has been deleted successfully.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Failed to delete client.';
            $_SESSION['message_type'] = 'danger';
        }
        header('Location: ' . BASE_URL . '?page=clients');
        exit;
    }
    
    // Handle client creation/update
    $name = trim($_POST['name'] ?? '');
    $clientCode = trim($_POST['client_code'] ?? '');
    
    // Validate input
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    
    if (empty($clientCode)) {
        $errors[] = 'Client code is required.';
    } elseif (!preg_match('/^[A-Z]{2,5}\-[0-9]{3}[A-Z]*$/', $clientCode)) {
        $errors[] = 'Client code must be in the format XX-123 (2-5 alpha characters followed by hyphen and 3 numbers).';
    }
    
    if (empty($errors)) {
        if ($id) {
            // Update existing client
            $client->setName($name);
            $client->setClientCode($clientCode);
        } else {
            // Create new client
            $client = new Client(null, $name, $clientCode);
        }
        
        if ($client->save()) {
            $_SESSION['message'] = 'Client has been ' . ($id ? 'updated' : 'created') . ' successfully.';
            $_SESSION['message_type'] = 'success';
            header('Location: ' . BASE_URL . '?page=clients');
            exit;
        } else {
            $errors[] = 'Failed to save client.';
        }
    }
}
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1><?php echo $id ? 'Edit' : 'Add New'; ?> Client</h1>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?php echo BASE_URL; ?>?page=clients" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back to Clients
        </a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="post" class="needs-validation" novalidate>
            <div class="form-group">
                <label for="client-name" class="required">Name</label>
                <input type="text" class="form-control" id="client-name" name="name" 
                       value="<?php echo htmlspecialchars($client ? $client->getName() : ''); ?>" required>
                <div class="invalid-feedback">
                    Please provide a client name.
                </div>
            </div>
            
            <div class="form-group">
                <label for="client-code" class="required">Client Code</label>
                <input type="text" class="form-control" id="client-code" name="client_code" 
                       value="<?php echo htmlspecialchars($client ? $client->getClientCode() : ''); ?>" required>
                <small class="form-text text-muted">
                    Format: 2-5 uppercase letters, hyphen, 3 numbers (e.g., ABC-123)
                </small>
                <div class="invalid-feedback">
                    Please provide a valid client code.
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <?php echo $id ? 'Update' : 'Create'; ?> Client
                </button>
                <a href="<?php echo BASE_URL; ?>?page=clients" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>