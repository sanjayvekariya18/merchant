<?php
/**
 * This file is for display language translation with filter particular language
 * at admin side and admin can approve that translation.
 * */
define('TRANSLATION_APPROVAL_KEY','translationApprovalFunction');
header('Content-Type: text/html; charset=utf-8');
require_once('lib/database.php');
/**
 * This file is for display language translation with filter particular language
 * at admin side and admin can approve that translation.
 * */
class AddUpdateTranslationApproval {
    const TRANSLATION_APPROVAL_FLAG=2;
    const TRANSLATION_APPROVED_MESSAGE='Approved';
    const DEFAULT_LANGUAGE_NAME = 'languageName';
    const USER_REFERENCE_KEY = 'userReference';
    const TRANSLATION_FLAG_KEY = 'translationFlag';
    const TRANSLATION_ID_KEY = 'translationId';
    const LANGUAGE_CODE_KEY = 'languageCode';

    /**
     * This file is for display language translation with filter particular language
     * at admin side.
     * @return null
     * */
    function displayTranslation() {
        $languageName=$_POST[self::DEFAULT_LANGUAGE_NAME];
        global $databaseConnection;
        $requestTableStage='hase_word_translation_stage';
        $originalIdQuery =$databaseConnection->prepare('SELECT * FROM hase_word_translation_stage WHERE language_code=:languageCode');
        $originalIdQuery->bindParam(':languageCode', $languageName);
        $originalIdQuery->execute();
        $fetchOriginalSelectedValues=$originalIdQuery->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($fetchOriginalSelectedValues);
    }
    /**
     * This file is for approve translation filter particular language at admin side.
     * @return null
     * */
    function approveTranslation() {
        global $databaseConnection;
        $userReference             =$_POST[self::USER_REFERENCE_KEY];
        $translationFlag           =$_POST[self::TRANSLATION_FLAG_KEY];
        $translationId             =$_POST[self::TRANSLATION_ID_KEY];
        $languageCode              =$_POST[self::LANGUAGE_CODE_KEY];
        $updateOriginalContentTable=$databaseConnection->prepare("UPDATE hase_word_translation_stage SET user_reference=:userReference WHERE or_id=:mainLanguageReference");
        $updateOriginalContentTable->bindParam(':userReference', $userReference);
        $updateOriginalContentTable->bindParam(':mainLanguageReference', $translationId);
        $updateOriginalContentTable->execute();
        return json_encode(self::TRANSLATION_APPROVED_MESSAGE);
    }
}
$addUpdateTranslationApproval=new AddUpdateTranslationApproval;
echo $addUpdateTranslationApproval->{$_REQUEST[TRANSLATION_APPROVAL_KEY]}();
?>
