<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_csrf();

$firstname = trim($_POST['firstname'] ?? '');
$middlename = trim($_POST['middlename'] ?? '');
$lastname = trim($_POST['lastname'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$batch = (int)($_POST['batch'] ?? 0);
$courseId = (int)($_POST['course_id'] ?? 0);
$email = trim($_POST['email'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$address = trim($_POST['address'] ?? '');
$occupation = trim($_POST['occupation'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');
$passwordConfirm = (string)($_POST['password_confirm'] ?? '');

if ($firstname && $lastname && $gender && $batch && $courseId && $email && $contact && $address && $username && $password && $passwordConfirm && $password === $passwordConfirm) {
    $pdo = get_pdo();
    
    // Check for duplicate username or email
    $stmtCheck = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ? OR (SELECT COUNT(*) FROM alumnus_bio WHERE email = ?) > 0');
    $stmtCheck->execute([$username, $email]);
    $duplicateCount = $stmtCheck->fetchColumn();
    
    if ($duplicateCount > 0) {
        header('Location: /scratch/?register=error&message=' . urlencode('Username or email already exists. Please choose different credentials.'));
        exit;
    }
    
    // Create minimal alumnus record first
    $pdo->beginTransaction();
    try {
        $avatarFile = null;
        if (!empty($_FILES['avatar']['name']) && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $safeName = 'avatar_' . bin2hex(random_bytes(6)) . ($ext?'.'.$ext:'');
            $destDir = __DIR__ . '/uploads';
            if (!is_dir($destDir)) { @mkdir($destDir, 0775, true); }
            $dest = $destDir . '/' . $safeName;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                $avatarFile = $safeName;
            }
        }

        $stmtA = $pdo->prepare('INSERT INTO alumnus_bio (firstname, middlename, lastname, gender, batch, course_id, email, contact, address, occupation, connected_to, avatar, status, date_created) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())');
        $stmtA->execute([$firstname, $middlename, $lastname, $gender, $batch, $courseId, $email, $contact, $address, $occupation, '', $avatarFile, 0]);
        $alumnusId = (int)$pdo->lastInsertId();

        // Process document uploads
        $documents = $_POST['documents'] ?? [];
        $uploadDir = __DIR__ . '/uploads/documents';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }
        
        $documentTypes = ['tor', 'diploma', 'id_card', 'other'];
        foreach ($documentTypes as $type) {
            if (!empty($_FILES['documents']['name'][$type]) && is_uploaded_file($_FILES['documents']['tmp_name'][$type])) {
                $file = $_FILES['documents'];
                $ext = pathinfo($file['name'][$type], PATHINFO_EXTENSION);
                $safeName = 'doc_' . $type . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
                $dest = $uploadDir . '/' . $safeName;
                
                if (move_uploaded_file($file['tmp_name'][$type], $dest)) {
                    $stmtDoc = $pdo->prepare('INSERT INTO alumni_documents (alumnus_id, document_type, document_name, file_path, file_size, upload_date) VALUES (?,?,?,?,?,NOW())');
                    $stmtDoc->execute([
                        $alumnusId,
                        $type,
                        $file['name'][$type],
                        'documents/' . $safeName,
                        $file['size'][$type]
                    ]);
                }
            }
        }

        $fullName = trim($firstname . ' ' . $middlename . ' ' . $lastname);
        $stmtU = $pdo->prepare('INSERT INTO users (name, username, password, type, auto_generated_pass, alumnus_id) VALUES (?,?,?,?,?,?)');
        $stmtU->execute([$fullName, $username, md5($password), 3, '', $alumnusId]);

        $pdo->commit();
        header('Location: /scratch/?register=success');
        exit;
    } catch (Throwable $e) {
        $pdo->rollBack();
        error_log("Registration error: " . $e->getMessage());
        header('Location: /scratch/?register=error&message=' . urlencode($e->getMessage()));
        exit;
    }
}

// If we reach here, validation failed
$errors = [];
if (!$firstname) $errors[] = 'First name is required';
if (!$lastname) $errors[] = 'Last name is required';
if (!$gender) $errors[] = 'Gender is required';
if (!$batch) $errors[] = 'Batch is required';
if (!$courseId) $errors[] = 'Course is required';
if (!$email) $errors[] = 'Email is required';
if (!$contact) $errors[] = 'Contact is required';
if (!$address) $errors[] = 'Address is required';
if (!$username) $errors[] = 'Username is required';
if (!$password) $errors[] = 'Password is required';
if ($password !== $passwordConfirm) $errors[] = 'Passwords do not match';

// Additional validation
if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}
if ($password && strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters long';
}
if ($username && strlen($username) < 3) {
    $errors[] = 'Username must be at least 3 characters long';
}

$errorMessage = implode(', ', $errors);
header('Location: /scratch/?register=error&message=' . urlencode($errorMessage));
exit;


