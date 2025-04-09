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

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1><?php echo $id ? 'Edit Client' : 'Create New Client'; ?></h1>
        <p class="text-muted"><?php echo $id ? 'Update client information' : 'Add a new client organization to the system'; ?></p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>?page=clients" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Back to Clients
        </a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-building mr-2"></i> Client Information
                </h6>
            </div>
            <div class="card-body">
                <form method="post" class="needs-validation" novalidate>
                    <div class="form-group">
                        <label for="client-name" class="required">Client Name</label>
                        <input type="text" class="form-control" id="client-name" name="name" 
                               value="<?php echo htmlspecialchars($client ? $client->getName() : ''); ?>" required>
                        <div class="invalid-feedback">
                            Please provide a client name.
                        </div>
                        <small class="form-text text-muted">
                            Enter the organization's full legal name.
                        </small>
                    </div>
                    
                    <div class="form-group position-relative">
                        <label for="client-code" class="required">Client Code</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="client-code" name="client_code" 
                                   value="<?php echo htmlspecialchars($client ? $client->getClientCode() : ''); ?>" 
                                   placeholder="XX-123" required pattern="^[A-Z]{2,5}\-[0-9]{3}[A-Z]*$">
                            <div class="input-group-append">
                                <?php if (!$client): ?>
                                <button type="button" id="generate-code" class="btn btn-outline-secondary" <?php echo $client ? 'disabled' : ''; ?>>
                                    <i class="fas fa-magic mr-1"></i> Generate
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="client-code-loader d-none position-absolute" style="right: 60px; top: 38px;">
                            <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                        </div>
                        <div class="invalid-feedback">
                            Please provide a valid client code in the format XX-123.
                        </div>
                        <small class="form-text text-muted">
                            Format: 2-5 uppercase letters, hyphen, 3 numbers (e.g., ABC-123)
                        </small>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-<?php echo $id ? 'save' : 'plus'; ?> mr-1"></i>
                            <?php echo $id ? 'Update Client' : 'Create Client'; ?>
                        </button>
                        <a href="<?php echo BASE_URL; ?>?page=clients" class="btn btn-outline-secondary ml-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="m-0 font-weight-bold text-muted">
                    <i class="fas fa-info-circle mr-2"></i> Guide
                </h6>
            </div>
            <div class="card-body">
                <h6 class="font-weight-bold">Client Name</h6>
                <p class="small text-muted mb-3">
                    The name should be the organization's official name. This will be displayed throughout the system.
                </p>
                
                <h6 class="font-weight-bold">Client Code</h6>
                <p class="small text-muted mb-3">
                    A unique identifier for the client. The system can generate this automatically based on the name, or you can create your own following the required format.
                </p>
                
                <div class="alert alert-info mt-4 small">
                    <i class="fas fa-lightbulb mr-2"></i>
                    <strong>Tip:</strong> After creating a client, you can add contacts and link them to this client.
                </div>
            </div>
        </div>
        
        <?php if ($id): ?>
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="m-0 font-weight-bold text-muted">
                    <i class="fas fa-history mr-2"></i> Client Details
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted d-block">Client ID:</small>
                    <strong><?php echo $client->getId(); ?></strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Created On:</small>
                    <strong><?php 
                    if (method_exists($client, 'getCreatedAt')) {
                        echo date('F j, Y', strtotime($client->getCreatedAt() ?? 'now'));
                    } else {
                        echo 'Not available';
                    }
                    ?></strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Last Updated:</small>
                    <strong><?php 
                    if (method_exists($client, 'getUpdatedAt')) {
                        echo date('F j, Y', strtotime($client->getUpdatedAt() ?? 'now'));
                    } else {
                        echo 'Not available';
                    }
                    ?></strong>
                </div>
                <div class="mb-0">
                    <small class="text-muted d-block">Linked Contacts:</small>
                    <strong><?php echo $client->getContactCount(); ?></strong>
                </div>
            </div>
            <div class="card-footer bg-light">
                <a href="<?php echo BASE_URL; ?>?page=client_view&id=<?php echo $client->getId(); ?>" class="btn btn-sm btn-info btn-block">
                    <i class="fas fa-eye mr-1"></i> View Complete Details
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Auto-generate client code when name is entered
        $('#client-name').on('blur', function() {
            const name = $(this).val();
            if (name && $('#client-code').val() === '') {
                generateClientCode(name);
            }
        });
        
        // Generate button click
        $('#generate-code').on('click', function() {
            const name = $('#client-name').val();
            if (name) {
                generateClientCode(name);
            } else {
                alert('Please enter a client name first.');
                $('#client-name').focus();
            }
        });
        
        function generateClientCode(name) {
            $('.client-code-loader').removeClass('d-none');
            $.ajax({
                url: '<?php echo BASE_URL; ?>?ajax=generate_client_code',
                method: 'POST',
                data: { name: name },
                dataType: 'json',
                success: function(response) {
                    $('#client-code').val(response.client_code);
                    $('.client-code-loader').addClass('d-none');
                },
                error: function() {
                    $('.client-code-loader').addClass('d-none');
                    alert('Failed to generate client code. Please try again or enter manually.');
                }
            });
        }
    });
</script>