<?php
session_start();
include '../../functions.php';

$errorMessage = null;

class Auth {
    private $conn;

    public function __construct() {
        $this->conn = connectDB(); 
    }

    public function login($email, $password) {
        $email = trim($email);
        $password = trim($password);

        $stmt = $this->conn->prepare("SELECT id, username, password FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) { 
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                return true;
            } else {
                return "Password salah!";
            }
        } else {
            return "Email tidak ditemukan!";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth = new Auth();
    $result = $auth->login($_POST['email'], $_POST['password']);

    if ($result === true) {
        header("Location: ../pages/dashboard.php");
        exit;
    } else {
        $errorMessage = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="shortcut icon" type="image/png" href="../../assets/images/favicon.png">
    <title>Login</title>
</head>
<body>
    <section>
        <div class="login-box">
            <form action="" method="post">
                <h2>LOGIN</h2>
                <?php if ($errorMessage): ?>
                    <div style="color: red; text-align:center; margin-bottom: 10px;">
                        <?= htmlspecialchars($errorMessage) ?>
                    </div>
                <?php endif; ?>

                <div class="input-box">
                    <input type="text" name="email" id="email" required>
                    <label style="color: white;">Email</label> 
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="password" required>
                    <label style="color: white;">Password</label> 
                </div>
                <button>Login</button> 
                <div class="register-link">
                    <p>Don't hava a account?<a href="/view/auth/register.php"> Register</a></p>
                </div>
            </form>
        </div>
    </section>
</body>
</html>