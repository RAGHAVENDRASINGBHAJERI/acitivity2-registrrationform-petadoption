<?php
require_once 'config.php';

if ($_POST) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $pet_type = htmlspecialchars($_POST['pet_type']);
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='result' style='background: #ffebee; border-left-color: #f44336;'><h3>❌ Invalid Email Format!</h3></div>";
        exit;
    }
    
    // Validate phone (10 digits)
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        echo "<div class='result' style='background: #ffebee; border-left-color: #f44336;'><h3>❌ Phone must be 10 digits!</h3></div>";
        exit;
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM registrations WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo "<div class='result' style='background: #ffebee; border-left-color: #f44336;'><h3>❌ Email already registered!</h3></div>";
        exit;
    }
    
    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO registrations (name, email, phone, pet_type) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $pet_type]);
    
    echo "<div class='result'>
            <h3>🐾 Pet Adoption Application Submitted!</h3>
            <p><strong>Adopter Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>Pet Type:</strong> $pet_type</p>
            <p><em>Application submitted on " . date('Y-m-d H:i:s') . "</em></p>
          </div>";
}
?>