<?php
require_once 'db_config.php';

class ContactOperations {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // CREATE - Add a new contact
    public function addContact($first_name, $last_name, $email, $phone, $address, $city, $state, $zip_code) {
        // Validate input
        if (empty($first_name) || empty($last_name) || empty($phone)) {
            return array('success' => false, 'message' => 'First name, last name, and phone are required.');
        }
        
        // Check if email already exists (if provided)
        if (!empty($email)) {
            $stmt = $this->conn->prepare("SELECT id FROM contacts WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return array('success' => false, 'message' => 'Email already exists.');
            }
            $stmt->close();
        }
        
        $stmt = $this->conn->prepare("INSERT INTO contacts (first_name, last_name, email, phone, address, city, state, zip_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            return array('success' => false, 'message' => 'Prepare failed: ' . $this->conn->error);
        }
        
        $stmt->bind_param("ssssssss", $first_name, $last_name, $email, $phone, $address, $city, $state, $zip_code);
        
        if ($stmt->execute()) {
            $stmt->close();
            return array('success' => true, 'message' => 'Contact added successfully!');
        } else {
            $error = $stmt->error;
            $stmt->close();
            return array('success' => false, 'message' => 'Error adding contact: ' . $error);
        }
    }
    
    // READ - Get all contacts
    public function getAllContacts() {
        $sql = "SELECT * FROM contacts ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            $contacts = array();
            while ($row = $result->fetch_assoc()) {
                $contacts[] = $row;
            }
            return $contacts;
        }
        return array();
    }
    
    // READ - Get single contact by ID
    public function getContactById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM contacts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $contact = $result->fetch_assoc();
            $stmt->close();
            return $contact;
        }
        $stmt->close();
        return null;
    }
    
    // READ - Search contacts
    public function searchContacts($search_term) {
        $search_term = '%' . $search_term . '%';
        $stmt = $this->conn->prepare("SELECT * FROM contacts WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ? ORDER BY created_at DESC");
        $stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $contacts = array();
            while ($row = $result->fetch_assoc()) {
                $contacts[] = $row;
            }
            $stmt->close();
            return $contacts;
        }
        $stmt->close();
        return array();
    }
    
    // UPDATE - Update a contact
    public function updateContact($id, $first_name, $last_name, $email, $phone, $address, $city, $state, $zip_code) {
        // Validate input
        if (empty($first_name) || empty($last_name) || empty($phone)) {
            return array('success' => false, 'message' => 'First name, last name, and phone are required.');
        }
        
        // Check if email already exists for another contact (if provided)
        if (!empty($email)) {
            $stmt = $this->conn->prepare("SELECT id FROM contacts WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $email, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return array('success' => false, 'message' => 'Email already exists for another contact.');
            }
            $stmt->close();
        }
        
        $stmt = $this->conn->prepare("UPDATE contacts SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, city = ?, state = ?, zip_code = ? WHERE id = ?");
        
        if (!$stmt) {
            return array('success' => false, 'message' => 'Prepare failed: ' . $this->conn->error);
        }
        
        $stmt->bind_param("ssssssssi", $first_name, $last_name, $email, $phone, $address, $city, $state, $zip_code, $id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return array('success' => true, 'message' => 'Contact updated successfully!');
        } else {
            $error = $stmt->error;
            $stmt->close();
            return array('success' => false, 'message' => 'Error updating contact: ' . $error);
        }
    }
    
    // DELETE - Delete a contact
    public function deleteContact($id) {
        $stmt = $this->conn->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return array('success' => true, 'message' => 'Contact deleted successfully!');
        } else {
            $error = $stmt->error;
            $stmt->close();
            return array('success' => false, 'message' => 'Error deleting contact: ' . $error);
        }
    }
}
?>
