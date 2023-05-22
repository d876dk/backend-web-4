<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['sex'] = !empty($_COOKIE['sex_error']);
  $errors['arms'] = !empty($_COOKIE['arms_error']);
  $errors['abilities'] = !empty($_COOKIE['ability_error']);
  $errors['biography'] = !empty($_COOKIE['biography_error']);
  $errors['mycheck'] = !empty($_COOKIE['check_error']);


  // Выдаем сообщения об ошибках.
  if ($errors['fio']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('fio_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните имя.</div>';
  }
  if ($errors['email']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('email_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните e-mail.</div>';
  }
  if ($errors['year']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('year_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Неправильный год.</div>';
  }
  if ($errors['sex']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('sex_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Неправильный sex.</div>';
  }
  if ($errors['arms']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('arms_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Неправильное количество конечностей.</div>';
  }
  if ($errors['abilities']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('ability_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Выберите способность.</div>';
  }
  if ($errors['biography']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('biography_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните биографию.</div>';
  }
  if ($errors['mycheck']) {
    setcookie('mycheck_error', '', 100000);
    $messages[] = '<div class="error">Вы должны быть согласны дать свои данные.</div>';
  }
  // TODO: тут выдать сообщения об ошибках в других полях.

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['year'] = empty($_COOKIE['year_value']) ? 0 : $_COOKIE['year_value'];
  $values['sex'] = empty($_COOKIE['sex_value']) ? '' : $_COOKIE['sex_value'];
  $values['arms'] = empty($_COOKIE['arms_value']) ? '' : $_COOKIE['arms_value'];
  $values['1'] = empty($_COOKIE['1_value']) ? 0 : $_COOKIE['1_value'];
  $values['2'] = empty($_COOKIE['2_value']) ? 0 : $_COOKIE['2_value'];
  $values['3'] = empty($_COOKIE['3_value']) ? 0 : $_COOKIE['3_value'];
  $values['4'] = empty($_COOKIE['4_value']) ? 0 : $_COOKIE['4_value'];
  $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
  $values['mycheck'] = empty($_COOKIE['mycheck_value']) ? 0 : $_COOKIE['mycheck_value'];

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
  // Проверяем ошибки.
  $bioregex = "/^\s*\w+[\w\s\.,-]*$/";
  $nameregex = "/^\w+[\w\s-]*$/";
  $mailregex = "/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/";

  $errors = FALSE;
  if (empty($_POST['fio']) || (!preg_match($nameregex, $_POST['fio']))) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('fio_error', '1', time() + 24 * 60 * 60);
    setcookie('fio_value', '', 100000);
    $errors = TRUE;
  } else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('fio_value', $_POST['fio'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('fio_error', '', 100000);
  }

  if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !preg_match($mailregex, $_POST['email'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    setcookie('email_value', '', 100000);
    $errors = TRUE;
  } else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('email_value', $_POST['email'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('email_error', '', 100000);
  }

  if ($_POST['year'] == 'Не выбран') {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('year_error', '1', time() + 24 * 60 * 60);
    setcookie('year_value', '', 100000);
    $errors = TRUE;
  } else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('year_value', $_POST['year'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('year_error', '', 100000);
  }

  if (!isset($_POST['sex'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('sex_error', '1', time() + 24 * 60 * 60);
    setcookie('sex_value', '', 100000);
    $errors = TRUE;
  } else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('sex_value', $_POST['sex'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('sex_error', '', 100000);
  }

  if (!isset($_POST['arms'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('arms_error', '1', time() + 24 * 60 * 60);
    setcookie('arms_value', '', 100000);
    $errors = TRUE;
  } else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('arms_value', $_POST['limbs'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('arms_error', '', 100000);
  }

  if (!isset($_POST['abilities'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('ability_error', '1', time() + 24 * 60 * 60);
    setcookie('1_value', '', 100000);
    setcookie('2_value', '', 100000);
    setcookie('3_value', '', 100000);
    setcookie('4_value', '', 100000);
    $errors = TRUE;
  } else {
    $ability = $_POST['abilities'];
    $abil = array(
      "1_value" => 0,
      "2_value" => 0,
      "3_value" => 0,
      "4_value" => 0,
    );
    foreach ($ability as $ab) {
      if ($ab == '1') {
        setcookie('1_value', 1, time() + 12 * 30 * 24 * 60 * 60);
        $abil['1_value'] = 1;
      }
      if ($ab == '2') {
        setcookie('2_value', 1, time() + 12 * 30 * 24 * 60 * 60);
        $abil['2_value'] = 1;
      }
      if ($ab == '3') {
        setcookie('3_value', 1, time() + 12 * 30 * 24 * 60 * 60);
        $abil['3_value'] = 1;
      }
      if ($ab == '4') {
        setcookie('4_value', 1, time() + 12 * 30 * 24 * 60 * 60);
        $abil['4_value'] = 1;
      }
    }
    foreach ($abil as $cons => $val) {
      if ($val == 0) {
        setcookie($cons, '', 100000);
      }
    }
    // Сохраняем ранее введенное в форму значение на месяц.
  }

  if (empty($_POST['biography']) || !preg_match($bioregex, $_POST['biography'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('biography_error', '1', time() + 24 * 60 * 60);
    setcookie('biography_value', '', 100000);
    $errors = TRUE;
  } else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('biography_value', $_POST['biography'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('biography_error', '', 100000);
  }
  if (!isset($_POST['mycheck'])) {
    setcookie('check_error', '1', time() +  24 * 60 * 60);
    setcookie('check_value', '', 100000);
    $errors = TRUE;
  } else {
    setcookie('check_value', TRUE, time() + 12 * 30 * 24 * 60 * 60);
    setcookie('check_error', '', 100000);
  }
  // *************
  // TODO: тут необходимо проверить правильность заполнения всех остальных полей.
  // Сохранить в Cookie признаки ошибок и значения полей.
  // *************

  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  } else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('fio_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('sex_error', '', 100000);
    setcookie('arms_error', '', 100000);
    setcookie('ability_error', '', 100000);
    setcookie('biography_error', '', 100000);
    setcookie('check_error', '', 100000);
    // TODO: тут необходимо удалить остальные Cookies.
  }

  // Сохранение в БД.
  $fio = $_POST['fio'];
  $email = $_POST['email'];
  $year = $_POST['year'];
  $sex = $_POST['sex'];
  $limbs = intval($_POST['arms']);
  $ability = $_POST['abilities'];
  $biography = $_POST['biography'];

  $user = 'u52995';
  $pass = '1306430';
  $db = new PDO('mysql:host=localhost;dbname=u52995', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

  // Подготовленный запрос. Не именованные метки.
  try {
    $stmt = $db->prepare("INSERT INTO application SET name = ?, email = ?, year = ?, sex = ?, arms = ?, biography = ?");
    $stmt->execute([$_POST['fio'], $_POST['email'], $_POST['year'], $_POST['sex'], $_POST['arms'], $_POST['biography']]);
    $application_id = $db->lastInsertId();

    $application_ability = $db->prepare("INSERT INTO application_ability SET aplication_id = ?, ability_id = ?");
    foreach ($_POST["abilities"] as $ability) {
      $application_ability->execute([$application_id, $ability]);
      print($ability);
    }
  } catch (PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
  }


  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}
