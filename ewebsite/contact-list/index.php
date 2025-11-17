<?php
require_once 'contact_operations.php';
$contacts_ops = new ContactOperations($conn);

// Handle search
$search_term = '';
$contacts = array();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    $search_term = htmlspecialchars($_GET['search']);
    $contacts = $contacts_ops->searchContacts($search_term);
} else {
    $contacts = $contacts_ops->getAllContacts();
}

// Handle delete
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $result = $contacts_ops->deleteContact($_POST['delete_id']);
    $message = $result['message'];
    $contacts = $contacts_ops->getAllContacts();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List - Phonebook</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📇 Contact List - Phonebook</h1>
            <p>Manage your contacts efficiently</p>
        </header>

        <?php if (!empty($message)): ?>
            <div class="message success">
                ✓ <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="add_contact.php" class="btn btn-primary">+ Add New Contact</a>
        </div>

        <div class="search-box">
            <form method="GET" action="index.php">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search by name, email, or phone..." 
                    value="<?php echo $search_term; ?>"
                    class="search-input"
                >
                <button type="submit" class="btn btn-search">Search</button>
                <?php if (!empty($search_term)): ?>
                    <a href="index.php" class="btn btn-reset">Clear Search</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (count($contacts) > 0): ?>
            <div class="contacts-section">
                <h2>Total Contacts: <?php echo count($contacts); ?></h2>
                
                <div class="table-responsive">
                    <table class="contacts-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contacts as $contact): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($contact['first_name']) . ' ' . htmlspecialchars($contact['last_name']); ?></strong>
                                    </td>
                                    <td><?php echo !empty($contact['email']) ? htmlspecialchars($contact['email']) : '<em>N/A</em>'; ?></td>
                                    <td><?php echo htmlspecialchars($contact['phone']); ?></td>
                                    <td><?php echo !empty($contact['city']) ? htmlspecialchars($contact['city']) : '<em>N/A</em>'; ?></td>
                                    <td class="actions-cell">
                                        <a href="view_contact.php?id=<?php echo $contact['id']; ?>" class="btn btn-small btn-info" title="View">👁 View</a>
                                        <a href="edit_contact.php?id=<?php echo $contact['id']; ?>" class="btn btn-small btn-warning" title="Edit">✎ Edit</a>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this contact?');">
                                            <input type="hidden" name="delete_id" value="<?php echo $contact['id']; ?>">
                                            <button type="submit" class="btn btn-small btn-danger" title="Delete">🗑 Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="message empty">
                <p><?php echo !empty($search_term) ? 'No contacts found matching your search.' : 'No contacts yet. <a href="add_contact.php">Add your first contact</a>'; ?></p>
            </div>
        <?php endif; ?>

        <footer>
            <p>&copy; 2025 Contact List - Phonebook. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
