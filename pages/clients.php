<?php
// pages/clients.php

// Get search parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Get all clients with search and sorting
$clients = Client::getAll($search, $sort, $order);

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
        <h1>Clients</h1>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?php echo BASE_URL; ?>?page=client_form" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add New Client
        </a>
    </div>
</div>

<!-- Search Box -->
<div class="card mb-4">
    <div class="card-body">
        <form method="get" class="form-inline">
            <input type="hidden" name="page" value="clients">
            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
            <input type="hidden" name="order" value="<?php echo htmlspecialchars($order); ?>">
            
            <div class="form-group mr-2 flex-grow-1">
                <div class="input-group w-100">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Search by name or client code" 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($search)): ?>
                <a href="<?php echo BASE_URL; ?>?page=clients" class="btn btn-outline-secondary">
                    <i class="fa fa-times"></i> Clear
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($clients)): ?>
            <p class="text-center my-5">No client(s) found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>
                                <a href="<?php echo BASE_URL; ?>?page=clients&search=<?php echo urlencode($search); ?>&sort=name&order=<?php echo getNextOrder($sort, 'name', $order); ?>" class="text-dark">
                                    Name
                                    <?php if ($sort === 'name'): ?>
                                        <i class="fa fa-sort-<?php echo strtolower($order) === 'asc' ? 'up' : 'down'; ?> sort-icon"></i>
                                    <?php else: ?>
                                        <i class="fa fa-sort sort-icon text-muted"></i>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>
                                <a href="<?php echo BASE_URL; ?>?page=clients&search=<?php echo urlencode($search); ?>&sort=client_code&order=<?php echo getNextOrder($sort, 'client_code', $order); ?>" class="text-dark">
                                    Client Code
                                    <?php if ($sort === 'client_code'): ?>
                                        <i class="fa fa-sort-<?php echo strtolower($order) === 'asc' ? 'up' : 'down'; ?> sort-icon"></i>
                                    <?php else: ?>
                                        <i class="fa fa-sort sort-icon text-muted"></i>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th class="text-center">No. of linked contacts</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($client->getName()); ?></td>
                                <td><?php echo htmlspecialchars($client->getClientCode()); ?></td>
                                <td class="text-center"><?php echo $client->getContactCount(); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>?page=client_view&id=<?php echo $client->getId(); ?>" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>?page=client_form&id=<?php echo $client->getId(); ?>" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-client" data-id="<?php echo $client->getId(); ?>" data-name="<?php echo htmlspecialchars($client->getName()); ?>" onclick="deleteClient(<?php echo $client->getId(); ?>, '<?php echo htmlspecialchars(addslashes($client->getName())); ?>')">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                Displaying <?php echo count($clients); ?> client(s)
                <?php if (!empty($search)): ?>
                    matching "<?php echo htmlspecialchars($search); ?>"
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteClientModal" tabindex="-1" role="dialog" aria-labelledby="deleteClientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteClientModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete client <strong id="client-name-to-delete"></strong>?
                <p class="text-danger mt-2">This action cannot be undone and will remove all links to contacts.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="delete-client-form" method="post" action="<?php echo BASE_URL; ?>?page=client_form&action=delete">
                    <input type="hidden" id="client-id-to-delete" name="id">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>