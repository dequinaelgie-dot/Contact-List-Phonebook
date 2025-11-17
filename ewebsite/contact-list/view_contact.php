<?php
require_once 'contact_operations.php';
$contacts_ops = new ContactOperations($conn);

$contact = null;

// Get contact ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Get contact details
$contact = $contacts_ops->getContactById($id);

if (!$contact) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contact - Phonebook</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📇 Contact Details</h1>
            <p><a href="index.php" class="back-link">← Back to Contact List</a></p>
        </header>

        <div class="contact-details">
            <div class="detail-card">
                <div class="detail-header">
                    <h2><?php echo htmlspecialchars($contact['first_name']) . ' ' . htmlspecialchars($contact['last_name']); ?></h2>
                </div>

                <div class="detail-body">
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">
                            <?php 
                            if (!empty($contact['email'])) {
                                echo '<a href="mailto:' . htmlspecialchars($contact['email']) . '">' . htmlspecialchars($contact['email']) . '</a>';
                            } else {
                                echo '<em>Not provided</em>';
                            }
                            ?>
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value">
                            <a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>">
                                <?php echo htmlspecialchars($contact['phone']); ?>
                            </a>
                        </span>
                    </div>

                    <?php if (!empty($contact['address'])): ?>
                        <div class="detail-row">
                            <span class="detail-label">Address:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($contact['address']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($contact['city']) || !empty($contact['state']) || !empty($contact['zip_code'])): ?>
                        <div class="detail-row">
                            <span class="detail-label">Location:</span>
                            <span class="detail-value">
                                <?php 
                                $location_parts = [];
                                if (!empty($contact['city'])) $location_parts[] = htmlspecialchars($contact['city']);
                                if (!empty($contact['state'])) $location_parts[] = htmlspecialchars($contact['state']);
                                if (!empty($contact['zip_code'])) $location_parts[] = htmlspecialchars($contact['zip_code']);
                                echo implode(', ', $location_parts);
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="detail-row">
                        <span class="detail-label">Added:</span>
                        <span class="detail-value"><?php echo date('M d, Y - H:i', strtotime($contact['created_at'])); ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Last Updated:</span>
                        <span class="detail-value"><?php echo date('M d, Y - H:i', strtotime($contact['updated_at'])); ?></span>
                    </div>
                </div>

                <div class="detail-actions">
                    <a href="edit_contact.php?id=<?php echo $contact['id']; ?>" class="btn btn-warning">✎ Edit Contact</a>
                    <a href="index.php" class="btn btn-secondary">← Back to List</a>
                </div>
            </div>
        </div>

        <footer>
            <p>&copy; 2025 Contact List - Phonebook. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
