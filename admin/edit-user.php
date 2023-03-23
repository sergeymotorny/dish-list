<?php 
    include(__DIR__ . '/../auth/check-auth.php');
    if (!checkRight('user', 'admin')) {
        die('Ви не маєте права на виконання цієї операції!');
    }

    if ($_POST) {
        // пошук користувача.
        require '../data/declare-users.php';
        foreach($data['users'] as $key => $user) {
            if($user['name'] == $_POST['user_name']) {
                break;
            }
        }

        //формування рядок прав доступу
        $rights = "";
        for($i = 0; $i < 9; $i++) {
            if($_POST['right' . $i]) {
                $rights .= "1";
            } else {
                $rights .= "0";
            }
        }

        //заміна в масиві на відповідного користувача
        $data['users'][$key] = array(
            'name' => $_POST['user_name'],
            'pwd' => $_POST['user_pwd'],
            'rights' => $rights . "\r\n",
        );

        //записуємо масив користувачів у файл
        $f = fopen("../data/users.txt", "w");
        foreach($data['users'] as $user) {
            $grArr = array( $user['name'], $user['pwd'], $user['rights'] );
            $grStr = implode(";", $grArr);
            fwrite($f, $grStr);
        }
        fclose($f);
        header('Location: index.php');
    }

    //читання користувачів та вибір необхідного нам
    require '../data/declare-users.php';
    foreach($data['users'] as $user) {
        if($user['name'] == $_GET['username']) {
            break;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin.css">
    <title>Редагування користувача</title>
</head>
<body>
    <header>
        <a href="index.php">До списку користувачів</a>
        <form name="edit-user" method="POST">
            <div class="tbl">
                <div>
                    <label for="user_name">Username: </label>
                    <input readonly type="text" name="user_name" value="<?php echo $user['name']; ?>">
                </div>
                <div>
                <label for="user_pwd">Password: </label>
                    <input type="text" name="user_pwd" value="<?php echo $user['pwd']; ?>">
                </div>
            </div>

            <div><p>Блюдо:</p>
                <input type="checkbox" <?php echo ("1" == $user['rights'][0])?"checked":""; ?> name="right0" 
                value="1"><span>перегляд</span>
                <input type="checkbox" <?php echo ("1" == $user['rights'][1])?"checked":""; ?> name="right1" 
                value="1"><span>створення</span>
                <input type="checkbox" <?php echo ("1" == $user['rights'][2])?"checked":""; ?> name="right2" 
                value="1"><span>редагування</span>
                <input type="checkbox" <?php echo ("1" == $user['rights'][3])?"checked":""; ?> name="right3" 
                value="1"><span>видалення</span>
            </div>

            <div><p>Продукти:</p>
                <input type="checkbox" <?php echo ("1" == $user['rights'][4])?"checked":""; ?> name="right4" 
                value="1"><span>перегляд</span>
                <input type="checkbox" <?php echo ("1" == $user['rights'][5])?"checked":""; ?> name="right5" 
                value="1"><span>створення</span>
                <input type="checkbox" <?php echo ("1" == $user['rights'][6])?"checked":""; ?> name="right6" 
                value="1"><span>редагування</span>
                <input type="checkbox" <?php echo ("1" == $user['rights'][7])?"checked":""; ?> name="right7" 
                value="1"><span>видалення</span>
            </div>

            <div><p>Користувачі:</p>
                <input type="checkbox" <?php echo ("1" == $user['rights'][8])?"checked":""; ?> name="right8" 
                value="1"><span>адміністрування</span>
            </div>
            <div><input type="submit" name="ok" value="змінити"></div>

        </form>
</body>
</html>
