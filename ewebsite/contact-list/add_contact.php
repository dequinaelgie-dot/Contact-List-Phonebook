<?php
require_once 'contact_operations.php';
$contacts_ops = new ContactOperations($conn);

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $zip_code = trim($_POST['zip_code'] ?? '');

    $result = $contacts_ops->addContact($first_name, $last_name, $email, $phone, $address, $city, $state, $zip_code);
    
    if ($result['success']) {
        $message = $result['message'];
        $message_type = 'success';
        // Clear form
        $first_name = $last_name = $email = $phone = $address = $city = $state = $zip_code = '';
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
    <title>Add Contact - Phonebook</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📇 Add New Contact</h1>
            <p><a href="index.php" class="back-link">← Back to Contact List</a></p>
        </header>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo ($message_type === 'success') ? '✓' : '✗'; ?> <?php echo $message; ?>
                <?php if ($message_type === 'success'): ?>
                    <br><a href="index.php">Go back to contact list</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="add_contact.php" class="contact-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input 
                        type="text" 
                        id="first_name" 
                        name="first_name" 
                        value="<?php echo htmlspecialchars($first_name ?? ''); ?>"
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
                        value="<?php echo htmlspecialchars($last_name ?? ''); ?>"
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
                        value="<?php echo htmlspecialchars($email ?? ''); ?>"
                        placeholder="Enter email address"
                    >
                </div>
                <div class="form-group">
                    <label for="phone">Phone *</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        value="<?php echo htmlspecialchars($phone ?? ''); ?>"
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
                    value="<?php echo htmlspecialchars($address ?? ''); ?>"
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
                        value="<?php echo htmlspecialchars($city ?? ''); ?>"
                        placeholder="Enter city"
                    >
                </div>
                <div class="form-group">
                    <label for="province">Province</label>
                    <input 
                        type="text" 
                        id="province" 
                        name="province" 
                        value="<?php echo htmlspecialchars($state ?? ''); ?>"
                        placeholder="Enter Province"
                    >
                </div>
                <div class="form-group">
                    <label for="zip_code">Zip Code</label>
                    <input 
                        type="text" 
                        id="zip_code" 
                        name="zip_code" 
                        value="<?php echo htmlspecialchars($zip_code ?? ''); ?>"
                        placeholder="Enter zip code"
                    >
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Contact</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <footer>
            <p>&copy; 2025 Contact List - Phonebook. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
