<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: /');
}
require_once 'aunt/connect.php';

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rekhlitskiy</title>
    <link rel="stylesheet" href="set/css.css">
</head>
<body>
    <form>
        <img src="<?= $_SESSION['user']['avatar'] ?>" width="200" alt="">
        <h2 style="margin: 10px 0;"><?= $_SESSION['user']['full_name'] ?></h2>
        <a href="#"><?= $_SESSION['user']['email'] ?></a>
        <a href="aunt/logout.php" class="logout">Выход</a>
    </form>
<?php

function link_bar($page, $pages_count)
{
    for ($j = 1; $j <= $pages_count; $j++)
    {
// Вывод ссылки
        if ($j == $page) {
            echo ' <a ><b>'.$j.'</b></a> ';
        } else {
            echo ' <a href='.$_SERVER['php_self'].'?page='.$j.'>'.$j.'</a> ';
        }
// Выводим разделитель после ссылки, кроме последней
// например, вставить "|" между ссылками
        if ($j != $pages_count) echo ' ';
    }
    return true;
} // Конец функции


// Подготовка к постраничному выводу
$perpage = 5; // Количество отображаемых данных из БД

if (empty(@$_GET['page']) || ($_GET['page'] <= 0)) {
    $page = 1;
} else {
    $page = (int) $_GET['page']; // Считывание текущей страницы
}

$coun = $connect->prepare('SELECT * FROM users');
$coun->execute();
$count = $coun->rowCount();
$pages_count = ceil($count / $perpage); // Количество страниц

// Если номер страницы оказался больше количества страниц
if ($page > $pages_count) $page = $pages_count;
$start_pos = ($page - 1) * $perpage; // Начальная позиция, для запроса к БД

// Вызов функции, для вывода ссылок на экран
link_bar($page, $pages_count);

// Вывод информации из базы данных
echo '<p><b>Постраничный вывод информации</b></p>';
$result= $connect->prepare('SELECT * FROM users limit' .$perpage. $start_pos);
$result->execute();

while ($row = $result->fetchAll(PDO::FETCH_ASSOC)) {
    foreach ($row as $rows) {
        print("ФИО: " . $rows['full_name'] . "; Login: " . $rows['login'] .  "; Email: ". $rows['email'] . "<br>");
    }
}

?>

</body>
</html>