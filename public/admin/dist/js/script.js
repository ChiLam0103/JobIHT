//load trang
$(document).ready(function () {
    var list_job_start_take = "api/v1/file/job-start/take=5000";
    var list_job_order_take = "api/v1/file/job-order/take=5000";
    var list_customer_take = "api/v1/data-basic/customer/list-take/type=1&take=5000";
    $("#job-start .waiting").html("Vui lòng đợi");
    $("#job-order #job-tab .waiting").html("Vui lòng đợi");
    $("#job-order #customer-tab .waiting").html("Vui lòng đợi");
    $.ajax({
        url: list_job_start_take,
        type: "get", // chọn phương thức gửi là get
        dateType: "json", // dữ liệu trả về dạng text
        success: function (result) {
            // Sau khi gửi và kết quả trả về thành công thì gán nội dung trả về
            $.each(result.data, function (key, value) {
                $("#job-start [name='fromjob']").append('<option value=' + value.JOB_NO + '>' + value.JOB_NO + '</option>');
                $("#job-start [name='tojob']").append('<option value=' + value.JOB_NO + '>' + value.JOB_NO + '</option>');
            });
            $("#job-start .waiting").html("");
        }
    });
    $.ajax({
        url: list_job_order_take,
        type: "get", // chọn phương thức gửi là get
        dateType: "json", // dữ liệu trả về dạng text
        success: function (result) {
            // Sau khi gửi và kết quả trả về thành công thì gán nội dung trả về
            $.each(result.data, function (key, value) {
                $("#job-order #content-job-tab [name='jobno']").append('<option value=' + value.JOB_NO + '>' + value.JOB_NO + '</option>');
            });
            $("#job-order #job-tab .waiting").html("");
        }
    });
    $.ajax({
        url: list_customer_take,
        type: "get", // chọn phương thức gửi là get
        dateType: "json", // dữ liệu trả về dạng text
        success: function (result) {
            // Sau khi gửi và kết quả trả về thành công thì gán nội dung trả về
            $.each(result.data, function (key, value) {
                $("#job-order #content-customer-tab [name='custno']").append('<option value=' + value.CUST_NO + '>' + value.CUST_NO + '|' + value.CUST_NAME + '</option>');
            });
            $("#job-order #customer-tab .waiting").html("");
        }
    });

});
// 1. in phếu theo dõi
$("#job-start .btnPrint").click(function () {
    // alert("The paragraph was clicked.");
    var fromjob = $("#job-start [name='fromjob']").val();
    var tojob = $("#job-start [name='tojob']").val();
    var url = "api/v1/print/file/job-start/fromjob=" + fromjob + "&tojob=" + tojob;
    window.open(url);
});
// 2. in phếu job order
//-----2.1 load danh sách job khi chọn khách hàng
$("#job-order #content-customer-tab  [name='custno']").on('change', function () {
    var custno = $("#job-order #content-customer-tab  [name='custno']").val();
    $(".waiting").html("Vui lòng đợi");
    $("#job-order #content-customer-tab  [name='jobno_helper1']").empty();
    // $("#job-order #content-customer-tab  [name='jobno_helper1']").bootstrapDualListbox('refresh', true);
    var url = "api/v1/print/file/job-order/custno=" + custno;
    $.ajax({
        url: url, // gửi ajax đến file result.php
        type: "get", // chọn phương thức gửi là get
        dateType: "json", // dữ liệu trả về dạng text
        cache: false,
        contentType: false,
        processData: false,
        success: function (result) {
            // Sau khi gửi và kết quả trả về thành công thì gán nội dung trả về
            console.log(result.job_m);
            $.each(result.job_m, function (key, value) {
                $("#job-order #content-customer-tab  [name='jobno_helper1']").append('<option value=' + key + '>' + value.JOB_NO + '</option>');
            });
            $(".waiting").html("");
            // $("#job-order #content-customer-tab  [name='jobno_helper1']").bootstrapDualListbox('refresh', true);
            // $('.duallistbox').bootstrapDualListbox('refresh', true);
        }
    });
});
//-----2.2 chức năng in
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
    window.open(url);
});

//hàm định dạng ngày
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
