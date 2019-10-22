var localOffsetMinute = new Date().getTimezoneOffset();
var localOffsetMSeconds = localOffsetMinute * 60 * 1000;
function Unix_timestamp(eventDate, EventTime, allDayEvent=0) {
    var targetDateTime = new Date(''+eventDate+' '+EventTime+'');
    var dateTimeObject = new Date(targetDateTime.getTime() - localOffsetMSeconds);
    var eventYear = dateTimeObject.getFullYear();
    var eventMonth = dateTimeObject.getMonth()+1;
    var eventDay = dateTimeObject.getDate();
    var eventHours = dateTimeObject.getHours();
    var eventMinutes = "0" + dateTimeObject.getMinutes();
    var eventSeconds = "0" + dateTimeObject.getSeconds();

    eventMonth = (eventMonth < 10) ? '0' + eventMonth : eventMonth;
    eventDay = (eventDay < 10) ? '0' + eventDay : eventDay;
    eventHours = (eventHours < 10) ? '0' + eventHours : eventHours;
    eventMinutes = (eventMinutes < 10) ? '0' + eventMinutes : eventMinutes;
    eventSeconds = (eventSeconds < 10) ? '0' + eventSeconds: eventSeconds;
    if(!allDayEvent || allDayEvent.length === 0)
    {
        return convdataTime = eventYear + '-' + eventMonth + '-' + eventDay + ' ' + eventHours + ':' + eventMinutes.substr(-2) + ':' + eventSeconds.substr(-2);
    } else {
        return convdataTime = eventYear + '-' + eventMonth + '-' + eventDay;
    }
}