var workTranslateTimer = {
    INTEGER_ZERO: 0,
    INTEGER_TWO: 2,
    INTEGER_TEN: 10,
    COUNT_DOWN_VALUE: 1000,
    WORK_TRANSLATE_TIMER: 60,
    SINGLE_SEPARATORS: '',
    WORK_TRANSLATE_URL: "image-view-randomly",
    IMAGE_STATUS_UPDATE: "image-status-update",
    COUNT_DOWN_ONE:'countdown-1',
    COUNT_DOUNT_ONE_VALUE:"countDown('countdown-1',",
    BRACKECT_OVER:")",
    COUNT_DOWN:"countDown('",
    COUNT_DOWN_NONE:'none',
    COMMA_SEPARATORS:"',",
    COUNT_DOUNT_IMAGE_WORD_VALUE:"imageWordQueueCountDown('countdown-1',",
    WORD_IMAGE_QUEUE_TRANSLATE_URL: "/public/hase_translation_queue_randomly",
    IMAGE_WORD_COUNT_DOWN:"imageWordQueueCountDown('",
    IMAGE_WORD_STATUS_UPDATE: "/public/image-word-status-update",
    RANDOM_MAXIMUM_VALUE:2,
    USER_KNOWN_LANGUAGE_DROP_DOWN_ID:'#userKnownLanguage',
}
/**
 * Function is for display timer on image work page.
 */
    function translateTimer() {
        secs = parseInt(document.getElementById(workTranslateTimer.COUNT_DOWN_ONE).innerHTML, workTranslateTimer.INTEGER_TEN);
        setTimeout(workTranslateTimer.COUNT_DOUNT_ONE_VALUE + secs + workTranslateTimer.BRACKECT_OVER, workTranslateTimer.COUNT_DOWN_VALUE);
    };
/**
 * Function is for display count down on image work view.
 * @param integer translateId
 * @param integer reloadTimer
 */
function countDown(translateId, reloadTimer) {
    reloadTimer--;
    minRemain = Math.floor(reloadTimer / workTranslateTimer.WORK_TRANSLATE_TIMER);
    secsRemain = new String(reloadTimer - (minRemain * workTranslateTimer.WORK_TRANSLATE_TIMER));
    if (secsRemain.length < workTranslateTimer.INTEGER_TWO) {
        secsRemain = workTranslateTimer.SINGLE_SEPARATORS + secsRemain;
    }
    timerClock = +secsRemain;
    document.getElementById(translateId).innerHTML = timerClock;
    if (reloadTimer > workTranslateTimer.INTEGER_ZERO) {
        setTimeout(workTranslateTimer.COUNT_DOWN + translateId + workTranslateTimer.COMMA_SEPARATORS + reloadTimer + workTranslateTimer.BRACKECT_OVER, workTranslateTimer.COUNT_DOWN_VALUE);
    } else {
        document.getElementById(translateId).style.display = workTranslateTimer.COUNT_DOWN_NONE;
        jQuery.ajax({
            type: "GET",
            url: workTranslateTimer.IMAGE_STATUS_UPDATE,
            async: false,
            success: function (workTranslateData) {
                location.href = "hase_image_word_queue"
            }
        });
    }
    window.onbeforeunload = function (workTranslateElement) {
        jQuery.ajax({
            type: "GET",
            url: workTranslateTimer.IMAGE_STATUS_UPDATE,
            async: false,
            success: function (workTranslateData) {
                location.href = "hase_image_word_queue"
            }
        });
    }
}
/**
 * @todo create code due to create logic to display image/text in queue.
 * After working properly set this code in one function.
 */
function wordImageTranslateTimer() {
	/*$(document).ready(function () {
	    $(workTranslateTimer.USER_KNOWN_LANGUAGE_DROP_DOWN_ID).kendoDropDownList({});
	});*/
    secs = parseInt(document.getElementById(workTranslateTimer.COUNT_DOWN_ONE).innerHTML, workTranslateTimer.INTEGER_TEN);
    setTimeout(workTranslateTimer.COUNT_DOUNT_IMAGE_WORD_VALUE + secs + workTranslateTimer.BRACKECT_OVER, workTranslateTimer.COUNT_DOWN_VALUE);
};
function imageWordQueueCountDown(translateId, reloadTimer){
    reloadTimer--;
    minRemain = Math.floor(reloadTimer / workTranslateTimer.WORK_TRANSLATE_TIMER);
    secsRemain = new String(reloadTimer - (minRemain * workTranslateTimer.WORK_TRANSLATE_TIMER));
    if (secsRemain.length < workTranslateTimer.INTEGER_TWO) {
        secsRemain = workTranslateTimer.SINGLE_SEPARATORS + secsRemain;
    }
    timerClock = +secsRemain;
    var queueRandomValue = Math.floor(Math.random() * randomValueDynamic) + 1 ; 
    document.getElementById(translateId).innerHTML = timerClock;
    if (reloadTimer > workTranslateTimer.INTEGER_ZERO) {
        setTimeout(workTranslateTimer.IMAGE_WORD_COUNT_DOWN + translateId + workTranslateTimer.COMMA_SEPARATORS + reloadTimer + workTranslateTimer.BRACKECT_OVER, workTranslateTimer.COUNT_DOWN_VALUE);
    } else {
        document.getElementById(translateId).style.display = workTranslateTimer.COUNT_DOWN_NONE;
        jQuery.ajax({
            type: "GET",
            url: baseUrl + workTranslateTimer.IMAGE_WORD_STATUS_UPDATE,
            async: false,
            success: function (workTranslateData) {
                location.href = baseUrl + workTranslateTimer.WORD_IMAGE_QUEUE_TRANSLATE_URL + "/" + queueRandomValue
            }
        });
    }
    window.onbeforeunload = function (workTranslateElement) {
        jQuery.ajax({
            type: "GET",
            url: baseUrl + workTranslateTimer.IMAGE_WORD_STATUS_UPDATE,
            async: false,
            success: function (workTranslateData) {
                location.href = baseUrl + workTranslateTimer.WORD_IMAGE_QUEUE_TRANSLATE_URL + "/" + queueRandomValue
            }
        });
    }
}