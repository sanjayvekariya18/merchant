var tradeOrdersTimer = {
   INTEGER_ZERO: 0,
   INTEGER_TWO: 2,
   INTEGER_TEN: 10,
   COUNT_DOWN_VALUE: 1000,
   WORK_TRANSLATE_TIMER: 60,
   SINGLE_SEPARATORS: '',
   IMAGE_STATUS_UPDATE: "image-status-update",
   COUNT_DOWN_ONE:'countdown-1',
   COUNT_DOUNT_ONE_VALUE:"countDown('countdown-1',",
   BRACKECT_OVER:")",
   COUNT_DOWN:"countDown('",
   COUNT_DOWN_NONE:'none',
   COMMA_SEPARATORS:"',",
   COUNT_DOUNT_TRADER_VALUE:"traderQueueCountDown('countdown-1',",
   TRADER_QUEUE_COUNT_DOWN:"traderQueueCountDown('",
}
function tradeOrdersTimerView() {
   secs = parseInt(document.getElementById(tradeOrdersTimer.COUNT_DOWN_ONE).innerHTML, tradeOrdersTimer.INTEGER_TEN);
   setTimeout(tradeOrdersTimer.COUNT_DOUNT_TRADER_VALUE + secs + tradeOrdersTimer.BRACKECT_OVER, tradeOrdersTimer.COUNT_DOWN_VALUE);
};
function traderQueueCountDown(translateId, reloadTimer){
   reloadTimer--;
   minRemain = Math.floor(reloadTimer / tradeOrdersTimer.WORK_TRANSLATE_TIMER);
   secsRemain = new String(reloadTimer - (minRemain * tradeOrdersTimer.WORK_TRANSLATE_TIMER));
   if (secsRemain.length < tradeOrdersTimer.INTEGER_TWO) {
       secsRemain = tradeOrdersTimer.SINGLE_SEPARATORS + secsRemain;
   }
   timerClock = +secsRemain;
   document.getElementById(translateId).innerHTML = timerClock;
   if (reloadTimer > tradeOrdersTimer.INTEGER_ZERO) {
      if(parseInt(localStorage.getItem('refreshCount')) != 2)
      {
        setTimeout(tradeOrdersTimer.TRADER_QUEUE_COUNT_DOWN + translateId + tradeOrdersTimer.COMMA_SEPARATORS + reloadTimer + tradeOrdersTimer.BRACKECT_OVER, tradeOrdersTimer.COUNT_DOWN_VALUE);
      } else {
        localStorage.setItem('refreshCount', 0);
      }
   } else {
        if(!parseInt(localStorage.getItem('refreshCount')))
        {
            localStorage.setItem('refreshCount', 1);
            location.reload();
        } else if(parseInt(localStorage.getItem('refreshCount')) >0 && parseInt(localStorage.getItem('refreshCount')) < 2) {
            localStorage.setItem('refreshCount', parseInt(localStorage.getItem('refreshCount'))+1);
            location.reload();

        } 
        /*document.getElementById(translateId).style.display = tradeOrdersTimer.COUNT_DOWN_NONE;
        var orderId = jQuery("#assetQueueOrderIdentity").val();
        var token = $('input[name="_token"]').val();
        if(orderId)
        {
            $.ajax({
               type:'POST',
               data:{_token:token,order_id:orderId,status_id:6},
               url : "update_queue_status",
               error:function(xhr,status,error) {
                   console.log(error);
               },
               success:function(assetTypeListResponse,status,xhr) {
                   location.reload();
               }

            });
        } else {
          location.reload();
        }*/
    }
}
 