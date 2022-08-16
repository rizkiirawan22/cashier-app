@extends('layouts.main', ['title' => 'Data Barang'])
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Barang</h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <button type="button" id="add" class="btn btn-primary"><i class="fas fa-plus-circle"></i>
                    Tambah</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table" class="table table-bordered table-striped" style="width: 100%">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th>Nama Barang</th>
                                <th>Harga Satuan</th>
                                <th width="15%"><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@includeIf('pages.item.modal')
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
        const table = $('#table').DataTable({
            processing: true,
            autoWidth: false,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route('barang.index') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'item_name'
                },
                {
                    data: 'unit_price'
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

        $('#add').click(function() {
            $('#main_form')[0].reset();  
            $('#id').val('');  
            $('span.error_text').html("");
            $('#title').html("Tambah Barang");
            $('#modal').modal('show');
        });

        $('body').on('click', '#edit', function() {
            let id = $(this).val();
            $('span.error_text').html("");
            $.ajax({
                type: "GET",
                url: "/barang/" + id + '/edit',
                success: function(response) {
                    $('#title').html("Ubah Barang");
                    $('#modal').modal('show');
                    $('#id').val(response.id);
                    $('#item_name').val(response.item_name);
                    $('#unit_price').val(response.unit_price);
                }
            });
        });

        $('body').on('click', '#delete', function() {
            let id = $(this).val();

            Swal.fire({
                title: 'Yakin Hapus ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya Hapus!',
                confirmButtonColor: '#FF0000',
                cancelButtonText: 'Tidak',
                cancelButtonColor: '#3085d6',
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "/barang/" + id,
                        success: function(response) {
                            if (response.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: response.success
                                });
                                table.ajax.reload();
                            }
                            if (response.error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal...',
                                    text: response.error,
                                })
                            }
                        }
                    });
                }
            })
        });

        $('#main_form').on('submit', function(e){
            e.preventDefault();

            $.ajax({
                url:$(this).attr('action'),
                method:$(this).attr('method'),
                data:new FormData(this),
                processData:false,
                dataType:'json',
                contentType:false,
                beforeSend:function(){
                    $(document).find('span.error_text').text('')
                },
                success:function(data){
                    if(data.status == 0){
                        console.log(data.error);
                        $.each(data.error, function(prefix, val){
                            $('span.'+prefix+'_error').text(val[0]);
                        });
                    }else{
                        $('#main_form')[0].reset();
                        $('#modal').modal('hide');
                        table.ajax.reload();
                        Toast.fire({
                            icon: 'success',
                            title: data.msg
                        });
                    }
                }
            })
        })
</script>
@endpush