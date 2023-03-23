<?php 
    require '../auth/check-auth.php';
    if (!checkRight('user', 'admin')) {
        die('Ви не маєте права на виконання цієї операції!');
    }
    require('../data/declare-users.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Адміністрування</title>
</head>
<body>
    <header>
        <a href="../index.php">На головну</a>
        <h1>Адміністрування користувачів</h1>
        <link rel="stylesheet" type="text/css" href="../css/main-style.css">
    </header>
    <section>
        <table>
            <thead>
                <tr>
                    <th>Користувач</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['users'] as $user):?>
                    <?php if($user['name'] !=$_SESSION['user'] && $user['name'] != 'admin' && trim($user['name']) != ''): ?>
                    <tr>
                        <td><a href="edit-user.php?username=<?php echo $user['name']; ?>"><?php echo $user['name']; ?></a></td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

</body>
</html>
