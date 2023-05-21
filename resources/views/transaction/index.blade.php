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
        @if(auth()->user()->level == 'user')
        <a href="{{ route('transaction.create') }}" id="btn-add" class="btn btn-primary mb-3"><i class=" ion-ios-add"></i> Add New Order</a>
        @else
        <form action="" method="get" class="row mb-3">
            <div class="form-group col-md-4">
                <label for="from">From</label>
                <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
            </div>

            <div class="form-group col-md-4">
                <label for="to">To</label>
                <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
            </div>

            <div class="form-group mt-3 col-md-4">
                <button type="submit" class="btn btn-primary mt-1">Submit</button>
            </div>
        </form>
        @endif


        <table id="datatable" class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-nowrap">No</th>
                    <th class="text-nowrap">Transcode</th>
                    <th class="text-nowrap">Custname</th>
                    <th class="text-nowrap">Transdate</th>
                    <th class="text-nowrap">Total Room Price</th>
                    <th class="text-nowrap">Total Extra Change</th>
                    <th class="text-nowrap">Final Total</th>
                    <th class="text-nowrap">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Extra Change</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="" method="post" id="form-extra">
                @csrf

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="">

                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="price">Price</label>
                        <input type="number" name="price" id="price" class="form-control" value="">

                        @error('price')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="javascript:;" id="btn-close" class="btn btn-white" data-bs-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form action="" class="d-none" id="form-delete" method="post">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>

<script>
    let from = $("#from").val();
    let to = $("#to").val();

    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('transaction.list') }}",
            type: "GET",
            data: {
                from: from,
                to: to
            }
        },
        deferRender: true,
        pagination: true,
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'transcode',
                name: 'transcode'
            },
            {
                data: 'custname',
                name: 'custname'
            },
            {
                data: 'transdate',
                name: 'transdate'
            },
            {
                data: 'total_room_price',
                name: 'total_room_price'
            },
            {
                data: 'total_extra_change',
                name: 'total_extra_change'
            },
            {
                data: 'final_total',
                name: 'final_total'
            },
            {
                data: 'action',
                name: 'action',
            },
        ]
    });

    $("#btn-add").on('click', function() {
        let route = $(this).attr('data-route')
        $("#form-extra").attr('action', route)
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