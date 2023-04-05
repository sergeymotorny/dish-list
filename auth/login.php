<?php 
    if($_POST) {
        require('../model/autorun.php');
        $myModel = Model\Data::makeModel(Model\Data::FILE);
        if($user = $myModel -> readUser($_POST['username'])) {
            if($user -> checkPassWord($_POST['password'])) {
                session_start();
                $_SESSION['user'] = $user -> getUserName();
                header('Location: ../index.php');
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login-style.css">
    <title>Authentication</title>
</head>
<body>
    <form method="post">
        <p>
            <input type="text" name="username" placeholder="username">
        </p>
        <p>
            <input type="password" name="password" placeholder="password">
        </p>
        <p>
            <input type="submit" value="login">
        </p>
    </form>
</body>
</html>