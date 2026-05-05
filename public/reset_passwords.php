<?php
$conn = @new mysqli('127.0.0.1', 'root', '', 'simjar_db');

// Hash password 'password' dengan bcrypt
$hashedPassword = password_hash('password', PASSWORD_BCRYPT);

echo '<h2>Resetting User Passwords</h2>';
echo '<p>New password hash for "password": <code>' . $hashedPassword . '</code></p>';

// Update semua user password ke 'password'
$query = "UPDATE users SET password = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $hashedPassword);
if ($stmt->execute()) {
    echo '<p style="color: green;">✅ Successfully updated ' . $stmt->affected_rows . ' users</p>';
} else {
    echo '<p style="color: red;">❌ Error: ' . $conn->error . '</p>';
}

echo '<h3>Now all users can login with password: <strong>password</strong></h3>';
echo '<p>Try logging in with any of these accounts:</p>';
echo '<ul>';
echo '<li><strong>admin@simjar.test</strong> (Super Admin) - Cabang 1</li>';
echo '<li><strong>admin2@simjar.test</strong> (Admin Cabang 2) - Cabang 2</li>';
echo '<li><strong>admin3@simjar.test</strong> (Admin Cabang 3) - Cabang 3</li>';
echo '<li><strong>staff@simjar.test</strong> (Staff Pusat) - Cabang 1</li>';
echo '</ul>';

$stmt->close();
$conn->close();
?>
