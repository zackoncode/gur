<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
</head>

<body>

    <?php

    use App\Core\TokenCsfr;

    if (isset($_SESSION['login_error'])): ?>
        <div class="error">
            <?php echo $_SESSION['login_error']; ?>
            <?php unset($_SESSION['login_error']); ?>
        </div>
    <?php endif; ?>

    <form action="/hospital/login/auth" method="POST">
        <?php echo $csrf_field  ?>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Senha:</label>
            <input type="password" name="password" required>
        </div>




        <button>Entrar</button>
    </form>
    </div>
</body>

</html>

<?php
