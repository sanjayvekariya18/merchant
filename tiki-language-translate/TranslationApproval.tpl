{*This file is for admin side add/update and approval translation after correction*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" " http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd ">
<html xmlns=" http://www.w3.org/1999/xhtml ">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="{$kendoAccessFilepath}/styles/kendo.common.min.css" type="text/css" rel="stylesheet" />
        <link href="{$kendoAccessFilepath}/styles/kendo.default.min.css" type="text/css" rel="stylesheet" />
        <script src="{$kendoAccessFilepath}/js/jquery.min.js" type="text/javascript"></script>
        <script src="{$kendoAccessFilepath}/js/kendo.all.min.js" type="text/javascript"></script>
        <script src="js/TranslationApproval.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="approveLanguageDropdown" style='float:right;'>Filter Translation:<input id="multiLanguageDropdown" /></div>
        <div class="onOffBingTranslation"><input id='onOffBingTranslation' class='k-button' name='onOffBingTranslation' onclick='onOffBingTranslateSentence(this.value);' type='button' value=''></div>
        <div class="approveTranslateGrid" id="translationApprovalGrid"></div>
    </body>
</html>