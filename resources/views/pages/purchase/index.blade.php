@extends('layouts.main', ['title' => 'Rekap Transaksi Pembelian'])
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Rekap Transaksi Pembelian</h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table" class="table table-bordered table-striped" style="width: 100%">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th>Waktu Transaksi</th>
                                <th>Total Barang</th>
                                <th>Total Harga</th>
                                <th width="15%"><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@includeIf('pages.purchase.detail')
@endsection
@push('js')
<script type="text/javascript">
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
        
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        }
    });

    @if (session('success'))
        Toast.fire({
        icon: 'success',
        title: '{!! session('success') !!}'
        });
    @endif

    const table = $('#table').DataTable({
        processing: true,
        autoWidth: false,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route('pembelian.index') }}',
        },
        columns: [{
                data: 'DT_RowIndex',
                searchable: false,
                sortable: false
            },
            {
                data: 'date'
            },
            {
                data: 'total_item'
            },
            {
                data: 'total_price'
            },
            {
                data: 'action',
                searchable: false,
                sortable: false
            },
        ],
        columnDefs: [{
            className: 'text-center',
            targets: '_all'
        }]
    });

    $('body').on('click', '#detail', function() {
        let id = $(this).val();
        $.ajax({
            type: 'POST',
            url: '{{ route('pembelian.detail') }}',
            data : {id :id},
            success: function(result) {
                $('#title').html("Detail Pembelian");
                $('#modal').modal('show');

                let tbody = $('#tableDetail tbody');
                tbody.empty();

                let no = 1;

                $.each(result, function (key, value){
                    let row = $('<tr></tr>');
                    console.log(value);
                    row.append('<td>' + no++ + '</td>');
                    row.append('<td>' + value.item.item_name + '</td>');
                    row.append('<td>' + value.amount + '</td>');
                    row.append('<td>' + value.unit_price + '</td>');
                    row.append('<td>' + (value.amount * value.unit_price) + '</td>');
                    
                    tbody.append(row);
                })
            }
        });
    });
</script>
@endpush