@extends('layouts.admin')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm">
                            <li class="breadcrumb-item"><a href="#">Báo biểu</a></li>
                            <li class="breadcrumb-item active">Hồ Sơ</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!--1. in phếu theo dõi -->
                        <div class="card card-default" id="job-start">
                            <div class="card-header">
                                <h3 class="card-title">1. Phiếu Theo Dõi (JOB)</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                            class="fas fa-minus"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                            class="fas fa-remove"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>From:</label> <label class="waiting text-warning"></label>
                                            <select class="form-control select2 " style="width: 100%;" name="fromjob">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>To: </label> <label class="waiting text-warning"></label>
                                            <select class="form-control select2" style="width: 100%;" name="tojob">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btnPrint"><i class="fa fa-print"
                                        aria-hidden="true"></i> In</button>
                            </div>
                        </div>
                        <!-- /.in phếu theo dõi -->

                        <!--2. in job order -->
                        <div class="card card-default" id="job-order">
                            <div class="card-header">
                                <h3 class="card-title">2. Job Order</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                            class="fas fa-minus"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                            class="fas fa-remove"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="job-order-custom-content-below-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="job-tab" data-toggle="pill" href="#content-job-tab"
                                            role="tab" aria-controls="content-job-tab" aria-selected="true">Job Order</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="customer-tab" data-toggle="pill"
                                            href="#content-customer-tab" role="tab" aria-controls="content-customer-tab"
                                            aria-selected="false">Khách
                                            Hàng</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="date-tab" data-toggle="pill" href="#content-date-tab"
                                            role="tab" aria-controls="content-date-tab" aria-selected="false">Ngày Job</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="job-order-custom-content-below-tabContent">
                                    <div class="tab-pane fade show active" id="content-job-tab" role="tabpanel"
                                        aria-labelledby="job-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Chọn Job Order: </label> <label class="waiting text-warning"></label>
                                                    <select class="form-control select2" style="width: 100%;" name="jobno">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="content-customer-tab" role="tabpanel"
                                        aria-labelledby="customer-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Mã Khách Hàng: </label>
                                                    <select class="form-control select2" style="width: 100%;" name="custno">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Chọn Job Order:</label> <label class="waiting text-warning"></label>
                                                    <select class="duallistbox" multiple="multiple" id="duallistbox2"
                                                        name="jobno">
                                                        {{-- <option value="0">Option 2</option> --}}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="content-date-tab" role="tabpanel"
                                        aria-labelledby="date-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Chọn ngày: </label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="far fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control float-right" name="date"
                                                            id="reservation">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.row -->
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btnPrint"><i class="fa fa-print"
                                        aria-hidden="true"></i> In</button>
                                <button type="button" class="btn btn-success "><i class="fa fa-file-excel"
                                        aria-hidden="true"></i> Excel</button>
                            </div>
                        </div>
                        <!-- /.in phếu theo dõi -->
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Bootstrap Duallistbox</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                            class="fas fa-minus"></i></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                            class="fas fa-remove"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Multiple</label>
                                            {{-- <select class="duallistbox" multiple="multiple" id="duallistbox"
                                                name="duallistbox_demo1">
                                                <option value="option2">Option 2</option>
                                                <option value="option3" selected="selected">Option 3</option>
                                                <option value="option4">Option 4</option>
                                                <option value="option5">Option 5</option>
                                                <option value="option6" selected="selected">Option 6</option>
                                                <option value="option7">Option 7</option>
                                                <option value="option8">Option 8</option>
                                                <option value="option9">Option 9</option>
                                                <option value="option0">Option 10</option>
                                            </select> --}}
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" onclick="btnPrint()">Population 1</button>
                            </div>
                        </div>
                        <!-- /.card -->

                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>

@endsection
<script>
    // $('[name=duallistbox_demo1]').bootstrapDualListbox();

</script>
