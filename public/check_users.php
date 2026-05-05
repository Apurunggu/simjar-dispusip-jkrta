<?php
$conn = @new mysqli('127.0.0.1', 'root', '', 'simjar_db');

echo '<h2>Checking Users & Passwords</h2>';
echo '<table border="1" style="border-collapse: collapse; width: 100%; margin: 20px 0;">';
echo '<tr style="background: #f2f2f2;"><th>ID</th><th>Name</th><th>Email</th><th>Password Hash</th><th>Cabang</th></tr>';

$result = $conn->query('SELECT id, name, email, password, cabang_id FROM users ORDER BY id');
while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td style="padding: 8px;">' . $row['id'] . '</td>';
    echo '<td style="padding: 8px;">' . $row['name'] . '</td>';
    echo '<td style="padding: 8px;">' . $row['email'] . '</td>';
    echo '<td style="padding: 8px; font-family: monospace; font-size: 11px; word-break: break-all;">' . $row['password'] . '</td>';
    echo '<td style="padding: 8px; text-align: center;">' . $row['cabang_id'] . '</td>';
    echo '</tr>';
}
echo '</table>';

echo '<h3>Trying to verify password</h3>';
// Try to verify password with password_verify
$testPassword = 'password';
$result = $conn->query('SELECT password FROM users WHERE email = "admin@simjar.test" LIMIT 1');
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hash = $row['password'];
    $isValid = password_verify($testPassword, $hash);
    echo '<p>Email: admin@simjar.test<br/>';
    echo 'Password entered: ' . $testPassword . '<br/>';
    echo 'Hash in DB: ' . $hash . '<br/>';
    echo 'Match: ' . ($isValid ? '✅ YES' : '❌ NO') . '</p>';
} else {
    echo 'User not found';
}

$conn->close();
?>
