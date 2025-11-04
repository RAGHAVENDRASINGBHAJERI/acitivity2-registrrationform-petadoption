<?php
require_once 'config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch($action) {
    case 'create':
        createUser();
        break;
    case 'read':
        if(isset($_GET['id'])) {
            getUserById($_GET['id']);
        } else {
            getAllUsers();
        }
        break;
    case 'update':
        updateUser();
        break;
    case 'delete':
        deleteUser();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function createUser() {
    global $pdo;
    
    try {
        $profileImage = '';
        if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $uploadDir = 'uploads/';
            if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $profileImage = $uploadDir . time() . '_' . $_FILES['profile_image']['name'];
            move_uploaded_file($_FILES['profile_image']['tmp_name'], $profileImage);
        }
        
        $sql = "INSERT INTO users (name, email, password, phone, birth_date, birth_time, birth_month, birth_week, birth_datetime, website_url, gender, profile_color, salary_range, bio, profile_image, newsletter) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['name'],
            $_POST['email'],
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            $_POST['phone'] ?? null,
            $_POST['birth_date'] ?? null,
            $_POST['birth_time'] ?? null,
            $_POST['birth_month'] ?? null,
            $_POST['birth_week'] ?? null,
            $_POST['birth_datetime'] ?? null,
            $_POST['website_url'] ?? null,
            $_POST['gender'],
            $_POST['profile_color'] ?? '#000000',
            $_POST['salary_range'] ?? 50000,
            $_POST['bio'] ?? null,
            $profileImage,
            isset($_POST['newsletter']) ? 1 : 0
        ]);
        
        echo json_encode(['success' => true, 'message' => 'User created successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
function getAllUsers() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'users' => $users]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function getUserById($id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function updateUser() {
    global $pdo;
    
    try {
        $profileImage = '';
        if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $uploadDir = 'uploads/';
            if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $profileImage = $uploadDir . time() . '_' . $_FILES['profile_image']['name'];
            move_uploaded_file($_FILES['profile_image']['tmp_name'], $profileImage);
            
            $sql = "UPDATE users SET name=?, email=?, phone=?, birth_date=?, birth_time=?, birth_month=?, birth_week=?, birth_datetime=?, website_url=?, gender=?, profile_color=?, salary_range=?, bio=?, profile_image=?, newsletter=? WHERE id=?";
            $params = [
                $_POST['name'], $_POST['email'], $_POST['phone'] ?? null, $_POST['birth_date'] ?? null,
                $_POST['birth_time'] ?? null, $_POST['birth_month'] ?? null, $_POST['birth_week'] ?? null,
                $_POST['birth_datetime'] ?? null, $_POST['website_url'] ?? null, $_POST['gender'],
                $_POST['profile_color'] ?? '#000000', $_POST['salary_range'] ?? 50000,
                $_POST['bio'] ?? null, $profileImage, isset($_POST['newsletter']) ? 1 : 0, $_POST['userId']
            ];
        } else {
            $sql = "UPDATE users SET name=?, email=?, phone=?, birth_date=?, birth_time=?, birth_month=?, birth_week=?, birth_datetime=?, website_url=?, gender=?, profile_color=?, salary_range=?, bio=?, newsletter=? WHERE id=?";
            $params = [
                $_POST['name'], $_POST['email'], $_POST['phone'] ?? null, $_POST['birth_date'] ?? null,
                $_POST['birth_time'] ?? null, $_POST['birth_month'] ?? null, $_POST['birth_week'] ?? null,
                $_POST['birth_datetime'] ?? null, $_POST['website_url'] ?? null, $_POST['gender'],
                $_POST['profile_color'] ?? '#000000', $_POST['salary_range'] ?? 50000,
                $_POST['bio'] ?? null, isset($_POST['newsletter']) ? 1 : 0, $_POST['userId']
            ];
        }
        
        if(!empty($_POST['password'])) {
            $sql = str_replace('name=?', 'name=?, password=?', $sql);
            array_splice($params, 1, 0, [password_hash($_POST['password'], PASSWORD_DEFAULT)]);
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function deleteUser() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>