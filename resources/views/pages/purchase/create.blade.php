@extends('layouts.main', ['title' => 'Transaksi Pembelian'])
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Transaksi Pembelian</h1>
</div>
<div class="row justify-content-center">
    <div class="col-12">
        <form action="{{ route('pembelian.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5>Input Barang</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="item" class="col-sm-3 col-form-label">Barang</label>
                        <div class="col-sm-4">
                            <select name="item" id="item" class="form-control">
                                <option value=""></option>
                                @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->item_name . ' - Rp.' . $item->unit_price }}
                                </option>
                                @endforeach
                            </select>
                            <span class="text-danger error_text item_error"></span>
                        </div>
                        <label for="amount" class="col-sm-1 col-form-label">Jumlah</label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" name="amount" id="amount" min="1">
                            <span class="text-danger error_text amount_error"></span>
                        </div>
                        <div class="col-sm-1 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="addCart"><i
                                    class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Barang</h5>
                </div>
                @if(Session::has('error'))
                <p class="alert alert-danger text-center">{{ Session::get('error') }}</p>
                @endif
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped" style="width: 100%">
                        <thead class="text-center">
                            <tr class="bg-primary text-white">
                                <th width="5%">No</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                                <th width="10%"><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @php $no = 1; @endphp
                            @foreach ($datas as $data)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->quantity }}</td>
                                <td>{{ $data->price }}</td>
                                <td>{{ $data->quantity * $data->price }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" id="delCart"
                                        value="{{ $data->id }}"><i class="fas fa-times"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row justify-content-end">
                        <div class="col-6">
                            <div class="form-group row">
                                <label for="item" class="col-sm-4 col-form-label">Total Harga</label>
                                <div class="col-sm-8">
                                    <input type="text" id="total" name="total" class="form-control" value="{{ $total }}"
                                        disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary my-3">Simpan Transaksi</button>
                </div>
        </form>
    </div>
</div>
@endsection
@push('js')
<script>
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

    const table = $('#table').DataTable({
        searching : false,
        paging : false,
        info : false,
        responsive : true,
    });

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    $('#item').select2({
        theme: 'bootstrap4',
        placeholder: 'Pilih Barang',
        allowClear: true
    });

    $('#amount').on('change', function(){
        let min = parseInt($(this).attr('min'));

        if ($(this).val() < min)
        {
            $(this).val(min);
        }   
    });

    $('body').on('click', '#addCart', function() {
        let item = $('#item').val();
        let price = $('#price').val();
        let amount = $('#amount').val();
        $.ajax({
            url:'{{ route('pembelian.addCart') }}',
            method: 'POST',
            data: {
                item : item,
                price : price,
                amount : amount
            },
            dataType:'json',
            beforeSend:function(){
                $(document).find('span.error_text').text('')
            },
            success:function(result){
                if(result.status == 0){
                    $.each(result.error, function(prefix, val){
                        $('span.'+prefix+'_error').text(val[0]);
                    });
                }else{
                    Toast.fire({
                        icon: 'success',
                        title: result.msg
                    });

                    let tbody = $('#table tbody');
                    tbody.empty();

                    let no = 1;

                    $.each(result.datas, function (key, value){
                        let row = $('<tr></tr>');
                        row.append('<td>' + no++ + '</td>');
                        row.append('<td>' + value.name + '</td>');
                        row.append('<td>' + value.quantity + '</td>');
                        row.append('<td>' + value.price + '</td>');
                        row.append('<td>' + (value.quantity * value.price) + '</td>');
                        row.append('<td><button type="' + 'button"  class="' + 'btn btn-danger btn-sm" id="' + 'delCart" value="' +
                            value.id +'"><i class="' + 'fas fa-times"></i></button></td>');
                        
                        tbody.append(row);

                        $('#amount').val("");
                    })

                    $('#total').val(result.total);
                }
            }
        })
    })

    $('body').on('click', '#delCart', function() {
        var id = $(this).val();

        Swal.fire({
            title: 'Yakin Hapus Dari List ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya Hapus!',
            confirmButtonColor: '#FF0000',
            cancelButtonText: 'Tidak',
            cancelButtonColor: '#3085d6',
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('pembelian.removeCart') }}",
                    data: {
                        id:id
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result.status == 1) {
                            Toast.fire({
                                icon: 'success',
                                title: result.msg
                            });
                            let tbody = $('#table tbody');
                            tbody.empty();

                            let no = 1;

                            $.each(result.datas, function (key, value){
                                let row = $('<tr></tr>');
                                row.append('<td>' + no++ + '</td>');
                                row.append('<td>' + value.name + '</td>');
                                row.append('<td>' + value.quantity + '</td>');
                                row.append('<td>' + value.price + '</td>');
                                row.append('<td>' + (value.quantity * value.price) + '</td>');
                                row.append('<td><button type="' + 'button"  class="' + 'btn btn-danger btn-sm" id="' + 'delCart" value="' +
                                    value.id +'"><i class="' + 'fas fa-times"></i></button></td>');
                                
                                tbody.append(row);
                            });

                            $('#total').val(result.total);
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal...',
                                text: result.error,
                            })
                        }
                    }
                });
            }
        })
    });

</script>
@endpush