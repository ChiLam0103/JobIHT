// 1. in phếu theo dõi
$("#job-start .btnPrint").click(function () {
    // alert("The paragraph was clicked.");
    var fromjob = $("#job-start [name='fromjob']").val();
    var tojob = $("#job-start [name='tojob']").val();
    $.ajax({
        url : "statistics/print/file/job-start", // gửi ajax đến file result.php
        type : "get", // chọn phương thức gửi là get
        // dateType:"text", // dữ liệu trả về dạng text
        data : { // Danh sách các thuộc tính sẽ gửi đi
            fromjob : fromjob,
            tojob : tojob
        },
        success : function (result){
            // Sau khi gửi và kết quả trả về thành công thì gán nội dung trả về
            // đó vào thẻ div có id = result
            console.log(result);
            $('#result').html(result);
        }
    });
    console.log(fromjob);
});

function btnPrint() {
    var selVal = $('[name=duallistbox_demo1]').val();
    console.log('#duallistbox', selVal);
}
