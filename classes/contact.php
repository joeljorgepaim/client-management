<?php
require_once 'Database.php';

class Contact {
    private $id;
    private $fullName;
    private $surname;
    private $email;
    private $phone;
    private $db;
    
    public function __construct($id = null, $fullName = '', $surname = '', $email = '', $phone = '') {
        $this->db = Database::getInstance();
        $this->id = $id;
        $this->fullName = $fullName;
        $this->surname = $surname;
        $this->email = $email;
        $this->phone = $phone;
    }
    
    // Getters and setters
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function getFullName() {
        return $this->fullName;
    }
    
    public function setFullName($fullName) {
        $this->fullName = $fullName;
        return $this;
    }
    
    public function getSurname() {
        return $this->surname;
    }
    
    public function setSurname($surname) {
        $this->surname = $surname;
        return $this;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }
    
    public function getPhone() {
        return $this->phone;
    }
    
    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }
    
    // Validate contact data
    public function validate() {
        $errors = [];
        
        if (empty($this->fullName)) {
            $errors[] = "Full name is required";
        }
        
        if (empty($this->surname)) {
            $errors[] = "Surname is required";
        }
        
        if (empty($this->email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email is not valid";
        }
        
        return $errors;
    }
    
    // Save contact to database
    public function save() {
        $errors = $this->validate();
        if (!empty($errors)) {
            return $errors;
        }
        
        $conn = $this->db->getConnection();
        
        if ($this->id) {
            // Update existing contact
            $stmt = $conn->prepare("UPDATE contacts SET full_name = ?, surname = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $this->fullName, $this->surname, $this->email, $this->phone, $this->id);
        } else {
            // Create new contact
            $stmt = $conn->prepare("INSERT INTO contacts (full_name, surname, email, phone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $this->fullName, $this->surname, $this->email, $this->phone);
        }
        
        if ($stmt->execute()) {
            if (!$this->id) {
                $this->id = $conn->insert_id;
            }
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return ["Database error: " . $conn->error];
        }
    }
    
    // Delete contact
    public function delete() {
        if (!$this->id) return false;
        
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
    
    // Get all contacts with optional search and sorting
    public static function getAll($search = '', $sort = '', $order = 'ASC') {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        // Build query with search and sort
        $query = "SELECT * FROM contacts";
        
        // Add search condition if provided
        if (!empty($search)) {
            $search = $conn->real_escape_string($search);
            $query .= " WHERE full_name LIKE '%{$search}%' OR surname LIKE '%{$search}%' OR email LIKE '%{$search}%' OR phone LIKE '%{$search}%'";
        }
        
        // Add sorting if provided
        if (!empty($sort)) {
            $allowedSortColumns = ['full_name', 'surname', 'email', 'phone', 'created_at'];
            if (in_array($sort, $allowedSortColumns)) {
                $order = ($order === 'DESC') ? 'DESC' : 'ASC';
                $query .= " ORDER BY {$sort} {$order}";
            } else {
                $query .= " ORDER BY full_name ASC"; // Default sort
            }
        } else {
            $query .= " ORDER BY full_name ASC"; // Default sort
        }
        
        $result = $conn->query($query);
        
        $contacts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $contact = new Contact($row['id'], $row['full_name'], $row['surname'], $row['email'], $row['phone']);
                $contacts[] = $contact;
            }
        }
        
        return $contacts;
    }
    
    // Get contact by ID
    public static function getById($id) {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM contacts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $contact = new Contact($row['id'], $row['full_name'], $row['surname'], $row['email'], $row['phone']);
            $stmt->close();
            return $contact;
        } else {
            $stmt->close();
            return null;
        }
    }
    
    // Get clients linked to this contact
    public function getClients() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("
            SELECT c.* 
            FROM clients c
            JOIN client_contact cc ON c.id = cc.client_id
            WHERE cc.contact_id = ?
            ORDER BY c.name ASC
        ");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $clients = [];
        while ($row = $result->fetch_assoc()) {
            $client = new Client($row['id'], $row['name'], $row['client_code']);
            $clients[] = $client;
        }
        
        $stmt->close();
        return $clients;
    }
    
    // Get count of linked clients
    public function getClientCount() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM client_contact WHERE contact_id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['count'];
    }
}