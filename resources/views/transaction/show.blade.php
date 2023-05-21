@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="panel panel-inverse">
    <!-- BEGIN panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title">{{ $title }}</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
        </div>
    </div>
    <!-- END panel-heading -->
    <!-- BEGIN panel-body -->
    <div class="panel-body">

        <form action="{{ route('transaction.update', $transaction->id) }}" method="post" class="row">
            @csrf
            @method('PUT')

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="transcode">Transcode</label>
                    <input type="text" name="transcode" id="transcode" class="form-control" value="{{ $transaction->transcode }}" disabled>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="transdate">Transdate</label>
                    <input type="date" name="transdate" id="transdate" class="form-control" value="{{ $transaction->transdate }}">
                </div>

                <div class="form-group mb-3">

                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="custname">Custname</label>
                    <input type="text" name="custname" id="custname" class="form-control" value="{{ $transaction->custname }}">

                    @error('custname')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="total_room_price">Total Room Price</label>
                    <input type="text" name="total_room_price" id="total_room_price" class="form-control" value="{{ $transaction->total_room_price }}" disabled>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="total_extra_change">Total Extra Change</label>
                    <input type="text" name="total_extra_change" id="total_extra_change" class="form-control" value="{{ $transaction->total_extra_change }}" disabled>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="final_total">Final Total</label>
                    <input type="text" name="final_total" id="final_total" class="form-control" value="{{ $transaction->final_total == 0 ? $transaction->total_room_price + $transaction->total_extra_change : $transaction->final_total }}" disabled>
                </div>
            </div>

        </form>


        <table id="datatable" class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-nowrap">No</th>
                    <th class="text-nowrap">Room Name</th>
                    <th class="text-nowrap">Day</th>
                    <th class="text-nowrap">Extra Change</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@endsection

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>

<script>
    function getData() {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('transaction.list-room', $transaction->id) }}",
            deferRender: true,
            pagination: true,
            bDestroy: true,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'room_name',
                    name: 'room_name'
                },
                {
                    data: 'day',
                    name: 'day'
                },
                {
                    data: 'extra',
                    name: 'extra'
                },
            ]
        });
    }

    getData()


    $("#btn-save").on('click', function() {
        let route = $(this).attr('data-route')
        let transaction = $("#transaction").val()
        let extra = $("#extra").val()
        let qty = $("#qty").val()
        let room = $("#room").val()

        $.ajax({
            url: route,
            type: "POST",
            method: "POST",
            data: {
                transaction: transaction,
                extra: extra,
                qty: qty,
                room: room,
            },
            success: function(response) {
                $("#extra").val("")
                $("#qty").val("")
                getData()
            }
        })
    })

    $(".btn-room").on('click', function() {
        let route = "{{ route('transaction.room', $transaction->id) }}";
        let room = $("#room").val()
        let day = $("#day").val()

        $.ajax({
            url: route,
            type: "POST",
            method: "POST",
            data: {
                room: room,
                day: day,
            },
            success: function(response) {
                $("#room").val("")
                $("#day").val("")
                getData()
            }
        })
    })

    $("#btn-close").on('click', function() {
        $("#form-extra").removeAttr('action')
    })


    $("#datatable").on('click', '.btn-edit', function() {
        let route = $(this).attr('data-route')
        let id = $(this).attr('id')

        $("#form-extra").attr('action', route)
        $("#form-extra").append(`<input type="hidden" name="_method" value="PUT">`);

        $.ajax({
            url: "/extra-change/" + id,
            type: 'GET',
            method: 'GET',
            success: function(response) {
                let extrachange = response.extrachange;

                $("#name").val(extrachange.name)
                $("#price").val(extrachange.price)
            }
        })
    })

    $("#datatable").on('click', '.btn-extra', function() {
        let route = $(this).attr('data-route')
        let id = $(this).attr('id')

        $("#form-extra").attr('action', route)
    })

    $("#datatable").on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let route = $(this).attr('data-route')
        $("#form-delete").attr('action', route)

        swal({
            title: 'Hapus data extra change?',
            text: 'Menghapus extra change bersifat permanen.',
            icon: 'error',
            buttons: {
                cancel: {
                    text: 'Cancel',
                    value: null,
                    visible: true,
                    className: 'btn btn-default',
                    closeModal: true,
                },
                confirm: {
                    text: 'Yes',
                    value: true,
                    visible: true,
                    className: 'btn btn-danger',
                    closeModal: true
                }
            }
        }).then((result) => {
            if (result) {
                $("#form-delete").submit()
            } else {
                $("#form-delete").attr('action', '')
            }
        });
    })
</script>
@endpush