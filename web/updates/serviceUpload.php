<?php

// TODO: Создать админские методы для загрузки обновлений на сервер

/*use Eviger\Database;

require_once "../../filesEviger/vendor/autoload.php";

if (isset($_FILES) && $_FILES['inputfile']['error'] == 0) {
    
    $destination_directory = dirname(__FILE__) .'/'.$_FILES['inputfile']['name'];
    move_uploaded_file($_FILES['inputfile']['tmp_name'], $destination_directory);
    Database::getInstance()->query("INSERT INTO eviger_updates (version, dl, changelog) VALUES ('?s', '?s', '?s')", (string)$_POST['version'], $destination_directory, $_POST['changelog']);
    echo 'Успех';
    
} else {
    
    echo 'Неудача';
    
}*/
