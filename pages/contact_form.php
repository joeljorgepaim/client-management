<?php
// pages/contact_form.php

// Initialize variables
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : '';
$errors = [];
$contact = null;

// Check if it's an edit form
if ($id) {
    $contact = Contact::getById($id);
    if (!$contact) {
        $_SESSION['message'] = 'Contact not found.';
        $_SESSION['message_type'] = 'danger';
        header('Location: ' . BASE_URL . '?page=contacts');
        exit;
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle contact deletion
    if ($action === 'delete' && isset($_POST['id'])) {
        $contact = Contact::getById((int)$_POST['id']);
        if ($contact && $contact->delete()) {
            $_SESSION['message'] = 'Contact has been deleted successfully.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Failed to delete contact.';
            $_SESSION['message_type'] = 'danger';
        }
        header('Location: ' . BASE_URL . '?page=contacts');
        exit;
    }
    
    // Handle contact creation/update
    $fullName = trim($_POST['full_name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    if ($id) {
        // Update existing contact
        $contact->setFullName($fullName);
        $contact->setSurname($surname);
        $contact->setEmail($email);
        $contact->setPhone($phone);
    } else {
        // Create new contact
        $contact = new Contact(null, $fullName, $surname, $email, $phone);
    }
    
    // Validate and save
    $result = $contact->save();
    
    if ($result === true) {
        $_SESSION['message'] = 'Contact has been ' . ($id ? 'updated' : 'created') . ' successfully.';
        $_SESSION['message_type'] = 'success';
        header('Location: ' . BASE_URL . '?page=contacts');
        exit;
    } else {
        $errors = $result;
    }
}
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1><?php echo $id ? 'Edit' : 'Add New'; ?> Contact</h1>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?php echo BASE_URL; ?>?page=contacts" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back to Contacts
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
                <label for="full-name" class="required">Full Name</label>
                <input type="text" class="form-control" id="full-name" name="full_name" 
                       value="<?php echo htmlspecialchars($contact ? $contact->getFullName() : ''); ?>" required>
                <div class="invalid-feedback">
                    Please provide a full name.
                </div>
            </div>
            
            <div class="form-group">
                <label for="surname" class="required">Surname</label>
                <input type="text" class="form-control" id="surname" name="surname" 
                       value="<?php echo htmlspecialchars($contact ? $contact->getSurname() : ''); ?>" required>
                <div class="invalid-feedback">
                    Please provide a surname.
                </div>
            </div>
            
            <div class="form-group">
                <label for="email" class="required">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($contact ? $contact->getEmail() : ''); ?>" required>
                <div class="invalid-feedback">
                    Please provide a valid email address.
                </div>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($contact ? $contact->getPhone() : ''); ?>">
                <small class="form-text text-muted">
                    Optional. Format: Country code and number (e.g., +264 81-111-7890)
                </small>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <?php echo $id ? 'Update' : 'Create'; ?> Contact
                </button>
                <a href="<?php echo BASE_URL; ?>?page=contacts" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Email validation with JavaScript
        $('#email').on('blur', function() {
            const emailInput = $(this);
            const emailValue = emailInput.val().trim();
            
            if (emailValue && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                emailInput.addClass('is-invalid');
                emailInput.removeClass('is-valid');
            } else if (emailValue) {
                emailInput.addClass('is-valid');
                emailInput.removeClass('is-invalid');
            }
        });
    });
</script>