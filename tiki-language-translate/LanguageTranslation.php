<?php
/**
 * This file is for display all language which are exist into DB for translate
 * text english to different languages
 * @return null
 * */
const DATABASE_FILE_PATH = 'lib/database.php';
const DEFAULT_LANGUAGE_NAME='English';
require_once(DATABASE_FILE_PATH);

class GeneralContents {

    public function allLanguage() {
        global $databaseConnection;
        $displayAllLanguages=$databaseConnection->prepare('SELECT language_code,language_name FROM languages');
        $displayAllLanguages->execute();
        $fectAllLanguages=$displayAllLanguages->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($fectAllLanguages);
    }
}
$allLanguage=new GeneralContents();
echo $allLanguage->allLanguage();
?>
