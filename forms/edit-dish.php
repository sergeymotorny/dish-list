<?php 
    include(__DIR__ . '/../auth/check-auth.php');
    
    require_once '../model/autorun.php';
    $myModel = Model\Data::makeModel(Model\Data::FILE);
    $myModel -> setCurrentUser($_SESSION['user']);

    if ($_POST) {
        if(!$myModel -> writeDish((new \Model\Dish())
            -> setId($_GET['dish'])
            -> setNameOfTheDish($_POST['nameOfTheDish'])
            -> setType($_POST['type'])
            -> setPortionWeight($_POST['portionWeight'])
        )) {
            die($myModel -> getError());
        } else {
            header('Location: ../index.php?dish=' . $_GET['dish']);
        } 
        
    }
    if(!$data['dish'] = $myModel -> readDish($_GET['dish']) ) {
        die($myModel -> getError());
    }
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
    <a href="../index.php?dish=<?php echo $_GET['dish']; ?>"> На головну</a>
    <form name='edit-dish' method="post">
        <div>
            <label for="nameOfTheDish">Назва страви: </label>
            <input type="text" name="nameOfTheDish" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" 
                    value="<?php echo $data['dish'] -> getNameOfTheDish(); ?>" required>
        </div>
        <div>
            <label for="type">Тип: </label>
            <input type="text" name="type" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" 
                    value="<?php echo $data['dish'] -> getType(); ?>" required>
        </div>
        <div>
            <label for="portionWeight">Вага порції: </label>
            <input type="text" name="portionWeight" placeholder="Example 850" pattern="[0-9]{1,4}" 
                    value="<?php echo $data['dish'] -> getPortionWeight(); ?>" required>
        </div>
        <br>
        <div class="btn-group">
            <input class="button" type="submit" name="ok" value="Змінити"> 
            <input class="button" type="submit" formaction="../index.php" name="ok" value="На головну"> 
        </div>
    </form>
</body>
</html>
