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
        $profileImage = null;
        $fileName = null;
        $fileType = null;
        if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $profileImage = file_get_contents($_FILES['profile_image']['tmp_name']);
            $fileName = $_FILES['profile_image']['name'];
            $fileType = $_FILES['profile_image']['type'];
        }

        $sql = "INSERT INTO users (name, email, password, phone, birth_date, birth_time, birth_month, birth_week, birth_datetime, website_url, gender, profile_color, salary_range, bio, profile_image, file_name, file_type, newsletter) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
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
            $fileName,
            $fileType,
            isset($_POST['newsletter']) ? 1 : 0
        ]);

        $message = 'User created successfully';
        if($profileImage) {
            $message .= ' with file';
        }

        echo json_encode(['success' => true, 'message' => $message]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
function getAllUsers() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($users as &$user) {
            if($user['profile_image']) {
                $user['profile_image'] = base64_encode($user['profile_image']);
            }
        }
        
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
            if($user['profile_image']) {
                $user['profile_image'] = base64_encode($user['profile_image']);
            }
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
        $hasFile = isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0;
        $hasPassword = !empty($_POST['password']);
        
        $sql = "UPDATE users SET name=?, email=?";
        $params = [$_POST['name'], $_POST['email']];
        
        if($hasPassword) {
            $sql .= ", password=?";
            $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        
        $sql .= ", phone=?, birth_date=?, birth_time=?, birth_month=?, birth_week=?, birth_datetime=?, website_url=?, gender=?, profile_color=?, salary_range=?, bio=?";
        $params = array_merge($params, [
            $_POST['phone'] ?? null, $_POST['birth_date'] ?? null, $_POST['birth_time'] ?? null,
            $_POST['birth_month'] ?? null, $_POST['birth_week'] ?? null, $_POST['birth_datetime'] ?? null,
            $_POST['website_url'] ?? null, $_POST['gender'], $_POST['profile_color'] ?? '#000000',
            $_POST['salary_range'] ?? 50000, $_POST['bio'] ?? null
        ]);
        
        if($hasFile) {
            $sql .= ", profile_image=?, file_name=?, file_type=?";
            $params[] = file_get_contents($_FILES['profile_image']['tmp_name']);
            $params[] = $_FILES['profile_image']['name'];
            $params[] = $_FILES['profile_image']['type'];
        }
        
        $sql .= ", newsletter=? WHERE id=?";
        $params[] = isset($_POST['newsletter']) ? 1 : 0;
        $params[] = $_POST['userId'];

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