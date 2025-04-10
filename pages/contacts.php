<?php
// pages/contacts.php

// Get search parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'full_name';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Get all contacts with search and sorting
$contacts = Contact::getAll($search, $sort, $order);

// Determine next sort order
function getNextOrder($currentSort, $requestedSort, $currentOrder) {
    if ($currentSort === $requestedSort) {
        return ($currentOrder === 'ASC') ? 'DESC' : 'ASC';
    }
    return 'ASC'; // Default to ASC when changing sort column
}
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1>Contacts</h1>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?php echo BASE_URL; ?>?page=contact_form" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add New Contact
        </a>
    </div>
</div>

<!-- Search Box -->
<div class="card mb-4">
    <div class="card-body">
        <form method="get" class="form-inline">
            <input type="hidden" name="page" value="contacts">
            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
            <input type="hidden" name="order" value="<?php echo htmlspecialchars($order); ?>">
            
            <div class="form-group mr-2 flex-grow-1">
                <div class="input-group w-100">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Search by name, surname, email or phone" 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($search)): ?>
                <a href="<?php echo BASE_URL; ?>?page=contacts" class="btn btn-outline-secondary">
                    <i class="fa fa-times"></i> Clear
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($contacts)): ?>
            <p class="text-center my-5">No contact(s) found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>
                                <a href="<?php echo BASE_URL; ?>?page=contacts&search=<?php echo urlencode($search); ?>&sort=full_name&order=<?php echo getNextOrder($sort, 'full_name', $order); ?>" class="text-dark">
                                    Full Name
                                    <?php if ($sort === 'full_name'): ?>
                                        <i class="fa fa-sort-<?php echo strtolower($order) === 'asc' ? 'up' : 'down'; ?> sort-icon"></i>
                                    <?php else: ?>
                                        <i class="fa fa-sort sort-icon text-muted"></i>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>
                                <a href="<?php echo BASE_URL; ?>?page=contacts&search=<?php echo urlencode($search); ?>&sort=surname&order=<?php echo getNextOrder($sort, 'surname', $order); ?>" class="text-dark">
                                    Surname
                                    <?php if ($sort === 'surname'): ?>
                                        <i class="fa fa-sort-<?php echo strtolower($order) === 'asc' ? 'up' : 'down'; ?> sort-icon"></i>
                                    <?php else: ?>
                                        <i class="fa fa-sort sort-icon text-muted"></i>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>
                                <a href="<?php echo BASE_URL; ?>?page=contacts&search=<?php echo urlencode($search); ?>&sort=email&order=<?php echo getNextOrder($sort, 'email', $order); ?>" class="text-dark">
                                    Email
                                    <?php if ($sort === 'email'): ?>
                                        <i class="fa fa-sort-<?php echo strtolower($order) === 'asc' ? 'up' : 'down'; ?> sort-icon"></i>
                                    <?php else: ?>
                                        <i class="fa fa-sort sort-icon text-muted"></i>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>
                                <a href="<?php echo BASE_URL; ?>?page=contacts&search=<?php echo urlencode($search); ?>&sort=phone&order=<?php echo getNextOrder($sort, 'phone', $order); ?>" class="text-dark">
                                    Phone
                                    <?php if ($sort === 'phone'): ?>
                                        <i class="fa fa-sort-<?php echo strtolower($order) === 'asc' ? 'up' : 'down'; ?> sort-icon"></i>
                                    <?php else: ?>
                                        <i class="fa fa-sort sort-icon text-muted"></i>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th class="text-center">No. of linked clients</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($contact->getFullName()); ?></td>
                                <td><?php echo htmlspecialchars($contact->getSurname()); ?></td>
                                <td><?php echo htmlspecialchars($contact->getEmail()); ?></td>
                                <td><?php echo htmlspecialchars($contact->getPhone()); ?></td>
                                <td class="text-center"><?php echo $contact->getClientCount(); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>?page=contact_view&id=<?php echo $contact->getId(); ?>" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                    <a style="background-color: darkgray; border-color: darkgray;" href="<?php echo BASE_URL; ?>?page=contact_form&id=<?php echo $contact->getId(); ?>" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-contact" data-id="<?php echo $contact->getId(); ?>" data-name="<?php echo htmlspecialchars($contact->getFullName() . ' ' . $contact->getSurname()); ?>" onclick="deleteContact(<?php echo $contact->getId(); ?>, '<?php echo htmlspecialchars(addslashes($contact->getFullName() . ' ' . $contact->getSurname())); ?>')">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                Displaying <?php echo count($contacts); ?> contact(s)
                <?php if (!empty($search)): ?>
                    matching "<?php echo htmlspecialchars($search); ?>"
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteContactModal" tabindex="-1" role="dialog" aria-labelledby="deleteContactModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteContactModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete contact <strong id="contact-name-to-delete"></strong>?
                <p class="text-danger mt-2">This action cannot be undone and will remove all links to clients.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="delete-contact-form" method="post" action="<?php echo BASE_URL; ?>?page=contact_form&action=delete">
                    <input type="hidden" id="contact-id-to-delete" name="id">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>