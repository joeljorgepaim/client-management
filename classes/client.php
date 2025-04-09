<?php
// classes/Client.php
require_once 'Database.php';

class Client {
    private $id;
    private $name;
    private $clientCode;
    private $db;
    
    public function __construct($id = null, $name = '', $clientCode = '') {
        $this->db = Database::getInstance();
        $this->id = $id;
        $this->name = $name;
        $this->clientCode = $clientCode;
    }
    
    // Getters and setters
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    public function getClientCode() {
        return $this->clientCode;
    }
    
    public function setClientCode($clientCode) {
        $this->clientCode = $clientCode;
        return $this;
    }
    
    // Save client to database
    public function save() {
        $conn = $this->db->getConnection();
        
        if ($this->id) {
            // Update existing client
            $stmt = $conn->prepare("UPDATE clients SET name = ?, client_code = ? WHERE id = ?");
            $stmt->bind_param("ssi", $this->name, $this->clientCode, $this->id);
        } else {
            // Create new client
            $stmt = $conn->prepare("INSERT INTO clients (name, client_code) VALUES (?, ?)");
            $stmt->bind_param("ss", $this->name, $this->clientCode);
        }
        
        if ($stmt->execute()) {
            if (!$this->id) {
                $this->id = $conn->insert_id;
            }
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
    
    // Delete client
    public function delete() {
        if (!$this->id) return false;
        
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
    
    // Get all clients with optional search and sorting
    public static function getAll($search = '', $sort = '', $order = 'ASC') {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        // Build query with search and sort
        $query = "SELECT * FROM clients";
        
        // Add search condition if provided
        if (!empty($search)) {
            $search = $conn->real_escape_string($search);
            $query .= " WHERE name LIKE '%{$search}%' OR client_code LIKE '%{$search}%'";
        }
        
        // Add sorting if provided
        if (!empty($sort)) {
            $allowedSortColumns = ['name', 'client_code', 'created_at'];
            if (in_array($sort, $allowedSortColumns)) {
                $order = ($order === 'DESC') ? 'DESC' : 'ASC';
                $query .= " ORDER BY {$sort} {$order}";
            } else {
                $query .= " ORDER BY name ASC"; // Default sort
            }
        } else {
            $query .= " ORDER BY name ASC"; // Default sort
        }
        
        $result = $conn->query($query);
        
        $clients = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $client = new Client($row['id'], $row['name'], $row['client_code']);
                $clients[] = $client;
            }
        }
        
        return $clients;
    }
    
    // Get client by ID
    public static function getById($id) {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $client = new Client($row['id'], $row['name'], $row['client_code']);
            $stmt->close();
            return $client;
        } else {
            $stmt->close();
            return null;
        }
    }
    
    // Generate a client code based on the client name
    public static function generateClientCode($name) {
        // Convert to uppercase
        $name = strtoupper($name);
        
        // Remove special characters and spaces, keep only alphanumeric
        $nameForCode = preg_replace('/[^A-Z0-9]/', '', $name);
        
        // Take first 5 alpha characters (or all if less than 5)
        $alphaPrefix = '';
        $matches = [];
        if (preg_match_all('/[A-Z]/', $nameForCode, $matches)) {
            $alphaPrefix = implode('', array_slice($matches[0], 0, 5));
        }
        
        // Ensure alpha prefix is at least 2 characters
        if (strlen($alphaPrefix) < 2) {
            // If not enough alpha chars, pad with 'X'
            $alphaPrefix = str_pad($alphaPrefix, 2, 'X');
        }
        
        // Add 3 digit numeric sequence
        $numericPart = '';
        $matches = [];
        if (preg_match_all('/[0-9]/', $nameForCode, $matches)) {
            $numericPart = implode('', array_slice($matches[0], 0, 3));
        }
        
        // If no numbers in name, generate random 3 digits
        if (strlen($numericPart) < 3) {
            $numericPart = str_pad($numericPart, 3, rand(0, 9));
        }
        
        // Combine alpha and numeric parts with a "-"
        $clientCode = $alphaPrefix . '-' . $numericPart;
        
        // Check if code exists and add suffix if needed
        $db = Database::getInstance();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM clients WHERE client_code = ?");
        $stmt->bind_param("s", $clientCode);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            // Add suffix A, B, C, etc. until unique
            $suffix = 'A';
            while (true) {
                $uniqueCode = $clientCode . $suffix;
                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM clients WHERE client_code = ?");
                $stmt->bind_param("s", $uniqueCode);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                
                if ($row['count'] == 0) {
                    $clientCode = $uniqueCode;
                    break;
                }
                
                // Increment suffix (A -> B -> C...)
                $suffix = chr(ord($suffix) + 1);
            }
        }
        
        $stmt->close();
        return $clientCode;
    }
    
    // Get count of linked contacts
    public function getContactCount() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM client_contact WHERE client_id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['count'];
    }
    
    // Get linked contacts
    public function getContacts() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("
            SELECT c.* 
            FROM contacts c
            JOIN client_contact cc ON c.id = cc.contact_id
            WHERE cc.client_id = ?
            ORDER BY c.full_name ASC
        ");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $contacts = [];
        while ($row = $result->fetch_assoc()) {
            $contact = new Contact($row['id'], $row['full_name'], $row['surname'], $row['email'], $row['phone']);
            $contacts[] = $contact;
        }
        
        $stmt->close();
        return $contacts;
    }
    
    // Link a contact to this client
    public function linkContact($contactId) {
        $conn = $this->db->getConnection();
        
        // Check if link already exists
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM client_contact WHERE client_id = ? AND contact_id = ?");
        $stmt->bind_param("ii", $this->id, $contactId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            $stmt->close();
            return true; // Already linked
        }
        
        // Create link
        $stmt = $conn->prepare("INSERT INTO client_contact (client_id, contact_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $this->id, $contactId);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }
    
    // Unlink a contact from this client
    public function unlinkContact($contactId) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM client_contact WHERE client_id = ? AND contact_id = ?");
        $stmt->bind_param("ii", $this->id, $contactId);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }
}