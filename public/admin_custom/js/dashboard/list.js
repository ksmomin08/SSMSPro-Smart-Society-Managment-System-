$(document).ready((function() {
    if($("#chart").length){
        loadMemberGraph();
    }
}));

// Member Daily Income Graph Get Data : Date : 25-02-2022
function loadMemberGraph(){
	var formData = new FormData();
    var url = $("#base_url").val()+'/get-graph-data';
	ajaxRequest('#resultData',formData,url,'ajaxMemberGraphResponse');
}

function ajaxMemberGraphResponse(_this,response){
    if (response.status == "success") {

        localStorage.setItem('admin_id', response.admin_id);
        localStorage.setItem('user_type', response.admin_id);

        // Staff Clocking System:
        if(response.data.staffAttendance.punch_type == 'punch_in'){
            $('.punchOut').removeClass('d-none');
            startCountdown(response.data.staffAttendance.punch_in);
            $('.punchInTime').removeClass('d-none');
            $('#punchInTime').html(response.data.staffAttendance.punch_in_time);
        }else{
            $('.punchIn').removeClass('d-none');
            $('#lastPunchIntime').addClass('d-none');
        }

        // Append Data:
        $('#allMemberCount').html(response.data.allMemberCount);
        $('#approvedMemberCount').html(response.data.approvedMemberCount);
        $('#unapprovedMemberCount').html(response.data.unapprovedMemberCount);
        $('#suspendedMemberCount').html(response.data.suspendedMemberCount);
        $('#maleMemberCount').html(response.data.maleMemberCount);
        $('#femaleMemberCount').html(response.data.femaleMemberCount);
        $('#paidMemberCount').html(response.data.paidMemberCount);
        $('#expiredMemberCount').html(response.data.expiredMemberCount);
        $('#notPaidMemberCount').html(response.data.notPaidMemberCount);

        // Member Status:
        var approvedMemberCount = response.data.approvedMemberCount;
        var unapprovedMemberCount = response.data.unapprovedMemberCount;
        var suspendedMemberCount = response.data.suspendedMemberCount;
        var options = {
            series: [approvedMemberCount, unapprovedMemberCount, suspendedMemberCount],
                chart: {
                width: 380,
                type: 'donut',
            },
            dataLabels: { enabled: false },
            labels: ['Approved Members ('+approvedMemberCount+')', 'Unapproved Members ('+unapprovedMemberCount+')','Suspended Members ('+suspendedMemberCount  +')'],
            colors:['#71dd37', '#233446', '#E91E63'],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        show: false
                    }
                }
            }],
            legend: {
                position: 'right',
                offsetY: 0,
                height: 230,
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // Member Plan Status:
        var paidMemberCount = response.data.paidMemberCount;
        var notPaidMemberCount = response.data.notPaidMemberCount;
        var expiredMemberCount = response.data.expiredMemberCount;
        var options = {
            series: [paidMemberCount, notPaidMemberCount, expiredMemberCount],
                chart: {
                width: 380,
                type: 'donut',
            },
            dataLabels: { enabled: false },
            colors:['#71dd37', '#03c3ec', '#ff3e1d'],
            labels: ['Paid Members ('+paidMemberCount+')', 'Not Paid Members ('+notPaidMemberCount+')', 'Expired Members ('+expiredMemberCount+')'],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        show: false
                    }
                }
            }],
            legend: {
                position: 'right',
                offsetY: 0,
                height: 230,
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();

        // Earning Reports Chart:
        var monthlyPayment = response.data.monthlyPayment;
        var monthlyPaymentTotal = [];
        $.each(monthlyPayment, function (index, value) {
            monthlyPaymentTotal.push(value);
        });
        var recentMonthsName = response.data.recentMonthsName;
        var myArray = [];
        $.each(recentMonthsName, function (index, value) {
            myArray.push(value);
        });

        var options3 = {
            series: [{
                name: "Earning Reports",
                data: monthlyPaymentTotal
            }],
                chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: myArray,
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart3"), options3);
        chart.render();

        // Gender Reports:
        var maleMemberCount = response.data.maleMemberCount;
        var femaleMemberCount = response.data.femaleMemberCount;
        var optionsGender = {
            series: [maleMemberCount, femaleMemberCount],
                chart: {
                width: 380,
                type: 'donut',
            },
            dataLabels: { enabled: false },
            colors:['#71dd37', '#03c3ec'],
            labels: ['Male Members ('+maleMemberCount+')', 'Female Members ('+femaleMemberCount+')'],
            responsive: [{
                breakpoint: 480,
                optionsGender: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        show: false
                    }
                }
            }],
            legend: {
                position: 'right',
                offsetY: 0,
                height: 230,
            }
        };
        var chart = new ApexCharts(document.querySelector("#chartGender"), optionsGender);
        chart.render();

        var photo1 = response.data.photo1;
        var photo2 = response.data.photo2;
        var photo3 = response.data.photo3;
        var photo4 = response.data.photo4;
        var options = {
            series: [photo1, photo2, photo3, photo4],
            chart: {
            // height: 197.7,
            width: 350,
            type: 'radialBar',
          },
          plotOptions: {
            radialBar: {
              offsetY: 0,
              startAngle: 0,
              endAngle: 270,
              hollow: {
                margin: 5,
                size: '30%',
                background: 'transparent',
                image: undefined,
              },
              dataLabels: {
                name: {
                  show: false,
                },
                value: {
                  show: false,
                }
              }
            }
          },
          colors: ['#1ab7ea', '#0084ff', '#39539E', '#0077B5'],
          labels: ['Photo 1', 'Photo 2', 'Photo 3', 'Photo 4'],
          legend: {
            show: true,
            floating: true,
            fontSize: '16px',
            position: 'right',
            offsetX: 160,
            offsetY: -10,
            labels: {
              useSeriesColors: true,
            },
            markers: {
              size: 0
            },
            formatter: function(seriesName, opts) {
              return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
            },
            itemMargin: {
              vertical: 3
            }
          },
          responsive: [{
            breakpoint: 480,
            options: {
              legend: {
                  show: false
              }
            }
          }]
          };

          var chart = new ApexCharts(document.querySelector("#chart4"), options);
          chart.render();

        var totalApprovedMembers = response.data.totalApprovedMembers;
        var approvedMembers = [];
        $.each(totalApprovedMembers, function (index, value) {
            approvedMembers.push(value);
        });
        var totalApprovedMembers = response.data.totalUnapprovedMembers;
        var unApprovedMembers = [];
        $.each(totalApprovedMembers, function (index, value) {
            unApprovedMembers.push(value);
        });
        var totalApprovedMembers = response.data.totalSuspendedMembers;
        var suspendedMembers = [];
        $.each(totalApprovedMembers, function (index, value) {
            suspendedMembers.push(value);
        });
        var options = {
        colors: ['#00e396', '#feb019', '#ff3e1d'],
        series: [{
            name: 'Approved Members',
            data: approvedMembers
        },{
            name: 'Unapproved Members',
            data: unApprovedMembers
        },{
            name: 'Suspended Members',
            data: suspendedMembers
        }],
        chart: {
            type: 'bar',
            height: 350,
            stacked: true,
            toolbar: {
                show: true
            },
            zoom: {
                enabled: true
            }
        },
        responsive: [{
        breakpoint: 480,
        options: {
            legend: {
                position: 'bottom',
                offsetX: -10,
                offsetY: 0
            }
        }
        }],
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 10,
                dataLabels: {
                    total: {
                        enabled: true,
                        style: {
                            fontSize: '13px',
                            fontWeight: 900
                        }
                    }
                }
            },
        },
            xaxis: {
                categories: myArray,
            },
            legend: {
                position: 'top',
                offsetY: 0,
            },
            fill: {
                opacity: 1
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart5"), options);
        chart.render();
    }else{
        ajaxMemberGraphResponse
    }
}


function startCountdown(targetDate) {
    function updateCountdown() {
        // var lastClockIn = new Date('2025-02-03 14:48:22');
        var lastClockIn = new Date(targetDate);
        var currentTime = new Date();
        // Calculate the time difference in milliseconds
        var timeDiff = currentTime - lastClockIn;
        // Calculate total hours, minutes, and seconds
        var totalHours = Math.floor(timeDiff / (1000 * 60 * 60));
        var minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
        // Display the countdown in HH:MM:SS format
        var countdown = totalHours + ":" + String(minutes).padStart(2, '0') + ":" + String(seconds).padStart(2, '0');
        $('#lastPunchIntime').html('<i class="bx bx-time-five fs-24"></i> '+countdown);
    }
    // Update the countdown every second
    setInterval(updateCountdown, 1000);
}
