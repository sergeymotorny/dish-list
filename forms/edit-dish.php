<?php 
    include(__DIR__ . '/../auth/check-auth.php');
    if (!checkRight('dish', 'edit')) {
        die('Ви не маєте права на виконання цієї операції!');
    }

    if ($_POST) {
        $f = fopen("../data/" . $_GET['dish'] . "/dish.txt", "w");
        $grArr = array(
                $_POST['nameOfTheDish'], 
                $_POST['type'], 
                $_POST['portionWeight'],);
        $grStr = implode(";", $grArr);
        fwrite($f, $grStr);
        fclose($f);
        header('Location: ../index.php?dish=' . $_GET['dish']);
    }
    $dishFolder = $_GET['dish'];
    require('../data/declare-dish.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/edit-dish-style.css">
    <title>Редагування інформації</title>
</head>
<body>
    <form name='edit-dish' method="post">
        <div>
            <label for="nameOfTheDish">Назва страви: </label>
            <input type="text" name="nameOfTheDish" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" value="<?php echo $data['dish']['nameOfTheDish']; ?>" required>
        </div>
        <div>
            <label for="type">Тип: </label>
            <input type="text" name="type" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" value="<?php echo $data['dish']['type']; ?>" required>
        </div>
        <div>
            <label for="portionWeight">Вага порції: </label>
            <input type="text" name="portionWeight" placeholder="Example 850" pattern="[0-9]{1,4}" value="<?php echo $data['dish']['portionWeight']; ?>" required>
        </div>
        <br>
        <div class="btn-group">
            <input class="button" type="submit" name="ok" value="Змінити"> 
            <input class="button" type="submit" formaction="../index.php" name="ok" value="На головну"> 
        </div>
    </form>
</body>
</html>
