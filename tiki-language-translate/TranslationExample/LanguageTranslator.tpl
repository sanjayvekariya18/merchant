<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" " http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd ">
<html xmlns=" http://www.w3.org/1999/xhtml ">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="{$kendoAccessFilepath}/styles/kendo.common.min.css" type="text/css" rel="stylesheet" />
        <link href="{$kendoAccessFilepath}/styles/kendo.default.min.css" type="text/css" rel="stylesheet" />
        <script src="{$kendoAccessFilepath}/js/jquery.min.js" type="text/javascript"></script>
        <script src="{$kendoAccessFilepath}/js/kendo.all.min.js" type="text/javascript"></script>
        <script src="../js/LanguageTranslateConfiguration.js" charset="utf-8" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="../styles/JqueryVirtualKeyboard.css">
        <script src="../js/JqueryVirtualKeyboard.js" type="text/javascript"></script>
        <script src="../js/LanguageTranslator.js" type="text/javascript"></script>
    </head>
    <body>
        <input id="multiLanguageDropdown" />
        <div id="languageTranslation">
            Loading data from a file is way faster than loading from a database. You just shave many layers of abstraction between your data and your application. If you profile your application for performance, you will see that database access is usually one of the slowest operation.
            If you don't want to load all your localization string every time you display something, you always have the options to put them in different files. For example, a "global" file for string displayed everywhere, and a localization file specific to the page/section your on.
            That said, as with anything concerning performance, don't take my word for it, but measure it yourself. Maybe in your particular context, with your particular application, a database will do just fine.
        </div>
        <div>
            <div id="searchTitle" class="sectionTitle">Property Search</div>
            <div id="searchForm">
                <table>
                    <tr>
                        <td><span>Location:</span></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="location" value="Loading data values"/></td>
                    </tr>
                    <tr>
                        <td><span>Budget</span></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="budgetFrom" placeholder="Budget" /></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="budgetTo" placeholder="Budget" /></td>
                    </tr>
                    <tr>
                        <td><span>Min Beds:</span></td>
                    </tr>
                    <tr>
                        <td>
                            <select name="minBeds">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6+">6+</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><span>Property Type:</span></td>
                    </tr>
                    <tr>
                        <td>
                            <select name="type">
                                <option value="all">Show All</option>
                                <option value="house">House</option>
                                <option value="townhouse">Town House</option>
                                <option value="apartment">Apartment</option>
                                <option value="commercial">Commercial</option>
                                <option value="office">Office</option>
                                <option value="storage">Storage</option>
                                <option value="land">Land</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><span>Added In:</span></td>
                    </tr>
                    <tr>
                        <td>
                            <select name="added">
                                <option value="anytime">Anytime</option>
                                <option value="24hours">Last 24 Hours</option>
                                <option value="3days">Last 3 Days</option>
                                <option value="week">Last Week</option>
                                <option value="month">Last Month</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <textarea placeholder="Translation Example"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="button" value="Search..." />
                            <input type="button" value="Anytime" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type="button" />Search...</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="languageTranslation">Loading data values</div>
    </body>
</html>