<div class="modal fade" id="modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title"></h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('barang.store') }}" id="main_form" class="form-horizontal" method="POST">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="item_name" class="col-sm-6">Nama Barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="item_name" id="item_name" class="form-control">
                            <span class="text-danger error_text item_name_error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="unit_price" class="col-sm-6">Harga</label>
                        <div class="col-sm-12">
                            <input type="number" name="unit_price" id="unit_price" class="form-control" step="any">
                            <span class="text-danger error_text unit_price_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                            Simpan</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                class="fas fa-window-close"></i>
                            Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>