<?php
session_start();
include '../includes/config.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $username;
            header("location: dashboard.php");
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    } else {
        $error = "Username atau password salah.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        /* ====== BACKGROUND IMAGE FULL SCREEN ====== */
        body {
            background-image: url('../images/pattern.png'); /* GANTI jika gambar beda */
            background-size: cover;        /* Menutupi seluruh layar */
            background-repeat: no-repeat;  /* Tidak mengulang */
            background-position: center;   /* Gambar ditengah */
            background-attachment: fixed;  /* Tidak ikut scroll */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* overlay biar teks & card tetap jelas (opsional) */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: -1;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .bg-custom-red {
            background-color: #dc3545;
        }

        .btn-custom-red {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }
        .btn-custom-red:hover {
            background-color: #b02a37;
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-custom-red text-white text-center">
                <h4 class="mb-0"><i class="fas fa-user-shield"></i> Admin Panel</h4>
            </div>
            <div class="card-body">
                <h5 class="card-title text-center text-muted mb-4">Login ke PIZZA SHOP 🍕</h5>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-custom-red">Login</button>
                        <a href="../index.php" class="btn btn-outline-light">Kembali ke Home</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
