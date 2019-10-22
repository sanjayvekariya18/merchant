"use strict";
$(document).ready(function() {
    $("#grid").kendoGrid({
        toolbar: kendo.template(jQuery("#templates").html()),
    });
    $('#proxyNodeSourceForm').bootstrapValidator({})
    var requestUrl = $("#requestUrl").val();
    $("input[name='type_status']").bootstrapSwitch();
    $("input[name='type_status']").on('switchChange.bootstrapSwitch', function(event, state) {
        var proxy_status_id = $(this).attr('proxy_id');
        var proxy_status_name = $(this).attr('proxy_name');
        if (state) {
            $("#type_status_all").bootstrapSwitch('state', false);
            var numberNotChecked = $('input:checkbox:checked').length;
            var type_id = ($('input[name=type_ids]').val());
            $.ajax({
                type: 'GET',
                url: requestUrl + "/lastStatusChartDetails" + "/" + proxy_status_id,
                success: function(donutLast) {
                    $.plot($("#status" + proxy_status_name), JSON.parse(donutLast), {
                        series: {
                            pie: {
                                show: true,
                                innerRadius: 0.5,
                                label: {
                                    show: true,
                                    formatter: function(label, series) {
                                        var element = '<div style="font-size:9pt;text-align:center;padding:2px;color:' + series.color + ';">' + label + '<br/>' + series.data[0][1] + '</div>';
                                        return element;
                                    }
                                }
                            }
                        },
                        legend: {
                            show: false
                        },
                        grid: {
                            hoverable: true
                        },
                        tooltip: true
                    });
                }
            });
            $.ajax({
                type: 'GET',
                url: requestUrl + "/prevSpeedChartDetails" + "/" + proxy_status_id,
                success: function(prevData) {
                    $.plot($("#speed" + proxy_status_name), JSON.parse(prevData), {
                        series: {
                            pie: {
                                show: true,
                                innerRadius: 0.5,
                                label: {
                                    show: true,
                                    formatter: function(label, series) {
                                        var element = '<div style="font-size:9pt;text-align:center;padding:2px;color:' + series.color + ';">' + label + '<br/>' + series.data[0][1] + '</div>';
                                        return element;
                                    }
                                }
                            }
                        },
                        legend: {
                            show: false
                        },
                        grid: {
                            hoverable: true
                        },
                        tooltip: true
                    });
                }
            });
            $.ajax({
                type: 'GET',
                url: requestUrl + "/initInitialChartDetails" + "/" + proxy_status_id,
                success: function(initData) {
                    $.plot($("#initial" + proxy_status_name), JSON.parse(initData), {
                        series: {
                            pie: {
                                show: true,
                                innerRadius: 0.5,
                                label: {
                                    show: true,
                                    formatter: function(label, series) {
                                        var element = '<div style="font-size:9pt;text-align:center;padding:2px;color:' + series.color + ';">' + label + '<br/>' + series.data[0][1] + '</div>';
                                        return element;
                                    }
                                }
                            }
                        },
                        legend: {
                            show: false
                        },
                        grid: {
                            hoverable: true
                        },
                        tooltip: true
                    });
                }
            });
            $("#type_status").attr('checked', true);
            proxyDonutsDetails();

            function proxyDonutsDetails() {
                var x = document.getElementById(proxy_status_id);
                x.style.display = "block";
            }
        } else {
            $("#type_status").attr('checked', false);
            var x = document.getElementById(proxy_status_id);
            x.style.display = "none";
        }
        event.preventDefault();
    });
    $("input[name='types_status']").bootstrapSwitch();
    var defaultType = $('input[name=types_status]').attr('checked');
    if (defaultType == 'checked') {
        $("#type_status_all").bootstrapSwitch('state', false);
        proxyDefaultDonutsDetails();
    }

    function proxyDefaultDonutsDetails() {
        var proxy_status_id = $('input#proxy_status_id').val();
        var proxy_status_name = $('input#proxy_status_name').val();;
        $.ajax({
            type: 'GET',
            url: requestUrl + "/lastStatusChartDetails" + "/" + proxy_status_id,
            success: function(donutLast) {
                $.plot($("#status" + proxy_status_name), JSON.parse(donutLast), {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 0.5,
                            label: {
                                show: true,
                                formatter: function(label, series) {
                                    var element = '<div style="font-size:9pt;text-align:center;padding:2px;color:' + series.color + ';">' + label + '<br/>' + series.data[0][1] + '</div>';
                                    return element;
                                }
                            }
                        }
                    },
                    legend: {
                        show: false
                    },
                    grid: {
                        hoverable: true
                    },
                    tooltip: true
                });
            }
        });
        $.ajax({
            type: 'GET',
            url: requestUrl + "/initInitialChartDetails" + "/" + proxy_status_id,
            success: function(initData) {
                $.plot($("#initial" + proxy_status_name), JSON.parse(initData), {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 0.5,
                            label: {
                                show: true,
                                formatter: function(label, series) {
                                    var element = '<div style="font-size:9pt;text-align:center;padding:2px;color:' + series.color + ';">' + label + '<br/>' + series.data[0][1] + '</div>';
                                    return element;
                                }
                            }
                        }
                    },
                    legend: {
                        show: false
                    },
                    grid: {
                        hoverable: true
                    },
                    tooltip: true
                });
            }
        });
        var x = document.getElementById('default');
        x.style.display = "block";
    }
    $("input[name='types_status']").on('switchChange.bootstrapSwitch', function(event, state) {
        if (!state) {
            var x = document.getElementById('default');
            x.style.display = "none";
        } else {
            $("#type_status_all").bootstrapSwitch('state', false);
            proxyDefaultDonutsDetails();
        }
    });
    $("input[name='type_status_all']").bootstrapSwitch();
    $("input[name='type_status_all']").on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
             $("#type_status").bootstrapSwitch('state', false);
             $("#types_status").bootstrapSwitch('state', false);
            $.ajax({
                type: 'GET',
                url: requestUrl + "/proxyAllStatus",
                success: function(statusList) {
                    $.each(statusList, function(key, value) {
                        document.getElementById("default").style.display = "none";
                        $('#types_status').prop('checked', false);
                        var statusType = document.getElementById(value.type_id);
                        if(statusType){
                            var x = document.getElementById(value.type_id);
                            x.style.display = "none";
                        }
                        var proxy_status_id = value.type_id;
                        var proxy_status_name = value.type_name;
                        if (value.status_speed_count) {
                            $.ajax({
                                type: 'GET',
                                url: requestUrl + "/lastStatusChartDetails" + "/" + proxy_status_id,
                                success: function(donutLast) {
                                    $.plot($("#statusAll" + proxy_status_name), JSON.parse(donutLast), {
                                        series: {
                                            pie: {
                                                show: true,
                                                innerRadius: 0.5,
                                                label: {
                                                    show: true,
                                                    formatter: function(label, series) {
                                                        var element = '<div style="font-size:9pt;text-align:center;padding:2px;color:' + series.color + ';">' + label + '<br/>' + series.data[0][1] + '</div>';
                                                        return element;
                                                    }
                                                }
                                            }
                                        },
                                        legend: {
                                            show: false
                                        },
                                        grid: {
                                            hoverable: true
                                        },
                                        tooltip: true
                                    });
                                }
                            });
                        }
                        if (value.status_state_count) {
                            $.ajax({
                                type: 'GET',
                                url: requestUrl + "/initInitialChartDetails" + "/" + proxy_status_id,
                                success: function(initData) {
                                    $.plot($("#initialAll" + proxy_status_name), JSON.parse(initData), {
                                        series: {
                                            pie: {
                                                show: true,
                                                innerRadius: 0.5,
                                                label: {
                                                    show: true,
                                                    formatter: function(label, series) {
                                                        var element = '<div style="font-size:9pt;text-align:center;padding:2px;color:' + series.color + ';">' + label + '<br/>' + series.data[0][1] + '</div>';
                                                        return element;
                                                    }
                                                }
                                            }
                                        },
                                        legend: {
                                            show: false
                                        },
                                        grid: {
                                            hoverable: true
                                        },
                                        tooltip: true
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#type_status_all").attr('checked', true);
            proxyDonutsDetails();

            function proxyDonutsDetails() {
                var x = document.getElementById("allStatusTypeDetails");
                x.style.display = "block";
            }
        } else {
            $("#type_status_all").attr('checked', false);
            var x = document.getElementById("allStatusTypeDetails");
            x.style.display = "none";
        }
        event.preventDefault();
    });
    $("input[name='all_type_status']").bootstrapSwitch();
    $("input[name='all_type_status']").on('switchChange.bootstrapSwitch', function(event, status) {
        if (status) {
            $.ajax({
                type: 'GET',
                url: requestUrl + "/lastAllChartDetails",
                success: function(donutLast) {
                    $.plot($("#donutAllLast"), JSON.parse(donutLast), {
                        series: {
                            pie: {
                                show: true,
                                innerRadius: 0.5,
                                label: {
                                    show: true,
                                    formatter: function(label, series) {
                                        var element = '<div style="font-size:9pt;text-align:center;padding:2px;color:' + series.color + ';">' + label + '<br/>' + series.data[0][1] + '</div>';
                                        return element;
                                    }
                                }
                            }
                        },
                        legend: {
                            show: false
                        },
                        grid: {
                            hoverable: true
                        },
                        tooltip: true
                    });
                }
            });
            $.ajax({
                type: 'GET',
                url: requestUrl + "/prevAllChartDetails",
                success: function(prevData) {
                    $.plot($("#donutAllPrev"), JSON.parse(prevData), {
                        series: {
                            pie: {
                                show: true,
                                innerRadius: 0.5,
                                label: {
                                    show: true,
                                    formatter: function(label, series) {
                                        var element = '<div style="font-size:9pt;text-align:center;padding:2px;color:' + series.color + ';">' + label + '<br/>' + series.data[0][1] + '</div>';
                                        return element;
                                    }
                                }
                            }
                        },
                        legend: {
                            show: false
                        },
                        grid: {
                            hoverable: true
                        },
                        tooltip: true
                    });
                }
            });
            $.ajax({
                type: 'GET',
                url: requestUrl + "/initAllChartDetails",
                success: function(initData) {
                    $.plot($("#donutAllInit"), JSON.parse(initData), {
                        series: {
                            pie: {
                                show: true,
                                innerRadius: 0.5,
                                label: {
                                    show: true,
                                    formatter: function(label, series) {
                                        var element = '<div style="font-size:9pt;text-align:center;padding:2px;color:' + series.color + ';">' + label + '<br/>' + series.data[0][1] + '</div>';
                                        return element;
                                    }
                                }
                            }
                        },
                        legend: {
                            show: false
                        },
                        grid: {
                            hoverable: true
                        },
                        tooltip: true
                    });
                }
            });
            $("#all_type_status").attr('checked', true);
            proxyAllDonutsDetails();

            function proxyAllDonutsDetails() {
                var x = document.getElementById("allProxyStatusType");
                x.style.display = "block";
            }
        } else {
            $("#all_type_status").attr('checked', false);
            var x = document.getElementById("allProxyStatusType");
            x.style.display = "none";
        }
        event.preventDefault();
    });
});