<html>

<head>
  <style>
    /* Сообщения об ошибках и поля с ошибками выводим с красным бордюром. */
    .error {
      border: 1px solid red;
      background-color: red;
    }
  </style>
</head>

<body>
  <?php
  if (!empty($messages)) {
    print('<div id="messages">');
    // Выводим все сообщения.
    foreach ($messages as $message) {
      print($message);
    }
    print('</div>');
  }
  ?>

  <div class="body">
    <form action="index.php" method="POST">

      <label>Фио:</label>
      <input name="fio" <?php if ($errors['fio']) {
                          print 'class="error"';
                        } ?> value="<?php print strip_tags($values['fio']); ?>" />
      <div></div>
      <label>email:</label>
      <input name="email" <?php if ($errors['email']) {
                            print 'class="error"';
                          } ?> value="<?php print strip_tags($values['email']); ?>" />
      <div></div>

      <label for="">Выберите год рождения:</label>
      <select name="year" <?php if ($errors['year']) {
                            print 'class="error"';
                          } ?> value="<?php print strip_tags($values['year']); ?>">
        <?php
        for ($i = 1920; $i <= 2023; $i++) {
          if ($values['year'] == $i) {
            printf("<option value=%d selected>%d </option>", $i, $i);
          } else {
            printf("<option value=%d>%d </option>", $i, $i);
          }
        }
        ?>
      </select>
      <div></div>

      <label <?php if ($errors['sex']) {
                print 'class="error"';
              } ?> for="">Пол:</label>
      <span><input type="radio" checked="checked" name="sex" value="0" <?php if ($values['sex'] == "male") {
                                                                          print 'checked';
                                                                        } ?> />Мужской</span>
      <span><input type="radio" name="sex" value="1" <?php if ($values['sex'] == "female") {
                                                        print 'checked';
                                                      } ?> />Женский</span>
      <div></div>

      <label <?php if ($errors['arms']) {
                print 'class="error"';
              } ?> for="">Конечности:</label>
      <span><input type="radio" checked="checked" name="arms" value="1" <?php if ($values['arms'] == "1") {
                                                                          print 'checked';
                                                                        } ?> />1</span>
      <span><input type="radio" checked="checked" name="arms" value="1" <?php if ($values['arms'] == "1") {
                                                                          print 'checked';
                                                                        } ?> />2</span>
      <span><input type="radio" checked="checked" name="arms" value="1" <?php if ($values['arms'] == "1") {
                                                                          print 'checked';
                                                                        } ?> />3</span>
      <div></div>

      <select name="abilities[]" multiple="multiple" <?php if ($errors['abilities']) {
                                                        print 'class="error"';
                                                      } ?>>
        <option value="1" <?php if ($values['1'] == 1) {
                            print 'selected';
                          } ?>>невидимость</option>
        <option value="2" <?php if ($values['2'] == 1) {
                            print 'selected';
                          } ?>>телепортация</option>
        <option value="3" <?php if ($values['3'] == 1) {
                            print 'selected';
                          } ?>>полет</option>
        <option value="4" <?php if ($values['4'] == 1) {
                            print 'selected';
                          } ?>>лингвист</option>
      </select>
      <div></div>

      <label> Ваша биография:</label>
      <textarea name="biography" <?php if ($errors['biography']) {
                                    print 'class="error"';
                                  } ?>><?php print $values['biography']; ?></textarea>
      <div></div>

      <input <?php if ($errors['mycheck']) {
                print 'error';
              } ?> type="checkbox" name="mycheck" <?php if ($values['mycheck'] == TRUE) {
                                                    print 'checked';
                                                  } ?> /> С контрактом ознакомлен(а)
      <div></div>

      <input type="submit" value="отправить" />

    </form>
  </div>
</body>

</html>