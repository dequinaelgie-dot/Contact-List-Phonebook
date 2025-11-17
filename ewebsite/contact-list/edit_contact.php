<?php
require_once 'contact_operations.php';
$contacts_ops = new ContactOperations($conn);

$contact = null;
$message = '';
$message_type = '';

// Get contact ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Get contact details
$contact = $contacts_ops->getContactById($id);

if (!$contact) {
    $message = "Contact not found!";
    $message_type = "error";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $contact) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $zip_code = trim($_POST['zip_code'] ?? '');

    $result = $contacts_ops->updateContact($id, $first_name, $last_name, $email, $phone, $address, $city, $state, $zip_code);
    
    if ($result['success']) {
        $message = $result['message'];
        $message_type = 'success';
        // Reload contact data
        $contact = $contacts_ops->getContactById($id);
    } else {
        $message = $result['message'];
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contact - Phonebook</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📇 Edit Contact</h1>
            <p><a href="index.php" class="back-link">← Back to Contact List</a></p>
        </header>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo ($message_type === 'success') ? '✓' : '✗'; ?> <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($contact): ?>
            <form method="POST" action="edit_contact.php?id=<?php echo $id; ?>" class="contact-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input 
                            type="text" 
                            id="first_name" 
                            name="first_name" 
                            value="<?php echo htmlspecialchars($contact['first_name']); ?>"
                            required
                            placeholder="Enter first name"
                        >
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input 
                            type="text" 
                            id="last_name" 
                            name="last_name" 
                            value="<?php echo htmlspecialchars($contact['last_name']); ?>"
                            required
                            placeholder="Enter last name"
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?php echo htmlspecialchars($contact['email']); ?>"
                            placeholder="Enter email address"
                        >
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone *</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="<?php echo htmlspecialchars($contact['phone']); ?>"
                            required
                            placeholder="Enter phone number"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address" 
                        value="<?php echo htmlspecialchars($contact['address']); ?>"
                        placeholder="Enter street address"
                    >
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input 
                            type="text" 
                            id="city" 
                            name="city" 
                            value="<?php echo htmlspecialchars($contact['city']); ?>"
                            placeholder="Enter city"
                        >
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input 
                            type="text" 
                            id="state" 
                            name="state" 
                            value="<?php echo htmlspecialchars($contact['state']); ?>"
                            placeholder="Enter state"
                        >
                    </div>
                    <div class="form-group">
                        <label for="zip_code">Zip Code</label>
                        <input 
                            type="text" 
                            id="zip_code" 
                            name="zip_code" 
                            value="<?php echo htmlspecialchars($contact['zip_code']); ?>"
                            placeholder="Enter zip code"
                        >
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Contact</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <div class="message error">
                ✗ Could not load contact for editing.
            </div>
        <?php endif; ?>

        <footer>
            <p>&copy; 2025 Contact List - Phonebook. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
