// 1. in phếu theo dõi
$("#job-start .btnPrint").click(function () {
    // alert("The paragraph was clicked.");
    var fromjob = $("#job-start [name='fromjob']").val();
    var tojob = $("#job-start [name='tojob']").val();
    var url = "api/v1/print/file/job-start/fromjob=" + fromjob + "&tojob=" + tojob;
    $.ajax({
        url: url, // gửi ajax đến file result.php
        type: "get", // chọn phương thức gửi là get
        dateType: "json", // dữ liệu trả về dạng text
        // data : { // Danh sách các thuộc tính sẽ gửi đi
        // },
        success: function (result) {
            // Sau khi gửi và kết quả trả về thành công thì gán nội dung trả về
            window.open(url);
        }
    });
});
// 2. in phếu job order
$("#job-order #content-customer-tab  [name='custno']").on('change', function () {
    var custno = $("#job-order #content-customer-tab  [name='custno']").val();
    $("#job-order #content-customer-tab  [name='jobno_helper1'] option").remove();
    var url = "api/v1/print/file/job-order/custno=" + custno;
    $.ajax({
        url: url, // gửi ajax đến file result.php
        type: "get", // chọn phương thức gửi là get
        dateType: "json", // dữ liệu trả về dạng text
        success: function (result) {
            // Sau khi gửi và kết quả trả về thành công thì gán nội dung trả về
            console.log(result.job_m);
            $.each(result.job_m, function (key, value) {
                $("#job-order #content-customer-tab  [name='jobno_helper1']").append('<option value=' + key + '>' + value.JOB_NO + '</option>');
            });
        }
    });
});

$("#job-order .btnPrint").click(function () {
    var active = $('#job-order .nav-item .active').attr('id');
    switch (active) {
        case "job-tab":
            var jobno = $("#job-order #content-job-tab [name='jobno']").val();
            var url = "api/v1/print/file/job-order/jobno=" + jobno;
            break;
        case "customer-tab":
            var custno = $("#job-order #content-customer-tab  [name='custno']").val();
            var jobno = $("#job-order #content-customer-tab  [name='jobno']").val();
            var url = "api/v1/print/file/job-order/custno=" + custno + "&jobno=" + jobno;
            break;
        case "date-tab":
            var date = $("#job-order #content-date-tab  [name='date']").val();
            var fromdate = formartDate(date.slice(0, 10));
            var todate = formartDate(date.slice(13, 23));
            var url = "api/v1/print/file/job-order/fromdate=" + fromdate + "&todate=" + todate;
            break;
    }

    $.ajax({
        url: url, // gửi ajax đến file result.php
        type: "get", // chọn phương thức gửi là get
        dateType: "json", // dữ liệu trả về dạng text
        // data : { // Danh sách các thuộc tính sẽ gửi đi
        // },
        success: function (result) {
            // Sau khi gửi và kết quả trả về thành công thì gán nội dung trả về
            window.open(url);
            console.log(result);
        }
    });
});
function formartDate(date) {
    var day = date.substr(0, 2);
    var month = date.substr(3, 2);
    var year = date.substr(6, 4);
    date = year + month + day;
    return date;
}
function btnPrint() {
    var selVal = $('[name=duallistbox_demo1]').val();
    console.log('#duallistbox', selVal);
}
