$(document).ready(function () {

    $("tbody").sortable();
    $("#DynamicSecurityQuestionRow").hide();

    $('#delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-danger').attr('href', $(e.relatedTarget).attr('data-link'));
    });

});

var questionCount = $("#question_count").val();
function DynamicSecurityQuestionRow()
{   
    questionCount++;
    $("#table").find('tbody').append($("#DynamicSecurityQuestionRow").find('tbody').html());
    $("#table").find('tbody').find('tr:last').attr("id","table-row"+questionCount+"");
    var trObjectFound = $("#table").find('tbody').find("#table-row"+questionCount+"");
    trObjectFound.find("#text").attr("name","questions["+questionCount+"][text]").attr("required","required");
    trObjectFound.find("#question_id").attr("name","questions["+questionCount+"][question_id]");
    var ValueOptionData = trObjectFound.find("[name='questions["+questionCount+"][text]']");
    $("#questionCount").val(questionCount);
}

