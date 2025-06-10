@extends('layouts.layout')

@section('title', 'Data Aset')
@section('master', '')
    

@section('css')
<link href="https://cdn.datatables.net/v/dt/dt-2.3.2/b-3.2.3/b-colvis-3.2.3/b-html5-3.2.3/fc-5.0.4/fh-4.0.2/r-3.0.4/datatables.min.css" rel="stylesheet" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" href="https://unpkg.com/@jarstone/dselect/dist/css/dselect.css">

<style>
    div.dt-search {
        float: left;
    }
    
    div.dt-length {
        float: right;
    }

    div.dt-info {
        /* text-align: left; */
        float: left;
    }
    
    div.dt-paging {
        /* clear: both;
        text-align: right; */
        float: right;
        margin-top: 0.5em;
    }
</style>
@endsection


@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Daftar Aset</h6>
            </div>
            
            <div class="card-body">
                <div class="mb-5">
                    <button type="button" onclick="_add()" class="btn btn-success btn-sm mb-0 w-20"><i class="fas fa-plus-circle"></i> Tambah Data</button>
                    <button type="button" onclick="_import()" class="btn btn-primary btn-sm mb-0 w-20"><i class="fas fa-file-excel" aria-hidden="true"></i> Import Data</button>
                    <button type="button" onclick="_template()" class="btn btn-info btn-sm mb-0 w-20"><i class="fas fa-download" aria-hidden="true"></i> Download Template</button>
                </div>
                <div class="table-responsive p-3">
                    <table class="table align-items-center mb-0" id="tb_aset">
                        <thead>
                            <tr>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Barang</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Merek Barang</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipe Barang</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tahun Pembelian</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ruang</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kondisi</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_new" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title_modal">Tambah Data</h5>
            </div>
            <form action="#" method="POST" id="form_add">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode" class="col-form-label">Kategori *</label>
                        <div class="w-100">
                            <select name="subdata" id="subdata" class="form-control" style="width: 100%;" required>
                                @if (count($subdata) > 0)
                                    <option value="">Pilih Kategori Barang</option>
                                    @foreach ($subdata as $param)
                                        <option value="{{ $param->uuid_subdata }}">{{ $param->kode_subdata }} - {{ $param->uraian }}</option>
                                    @endforeach
                                @endif
                                <option value="">Tidak Ada Data</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nama" class="col-form-label">Nama Barang *</label>
                        <input type="text" class="form-control" id="nama" name="nama" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="merek" class="col-form-label">Merek Barang *</label>
                        <input type="text" class="form-control" id="merek" name="merek" required>
                    </div>
                    <div class="form-group">
                        <label for="tipe" class="col-form-label">Tipe Barang </label>
                        <input type="text" class="form-control" id="tipe" name="tipe">
                    </div>
                    <div class="form-group">
                        <label for="ukuran" class="col-form-label">Ukuran/Dimensi</label>
                        <input type="text" class="form-control" id="ukuran" name="ukuran">
                    </div>
                    <div class="form-group">
                        <label for="bahan" class="col-form-label">Bahan</label>
                        <input type="text" class="form-control" id="bahan" name="bahan">
                    </div>
                    <div class="form-group">
                        <label for="harga" class="col-form-label">Harga Pembelian *</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>
                    <div class="form-group">
                        <label for="tahun" class="col-form-label">Tahun Pembelian *</label>
                        <input type="text" class="form-control" id="tahun" name="tahun" required>
                    </div>
                    <div class="form-group">
                        <label for="ruang" class="col-form-label">Ruang *</label>
                        <input type="text" class="form-control" id="ruang" name="ruang" required>
                    </div>
                    <div class="form-group">
                        <label for="kondisi" class="col-form-label">Kondisi Barang *</label>
                        <select name="kondisi" id="kondisi" class="form-control" data-live-search="true" required>
                            <option value="">Pilih Kondisi Barang</option>
                            <option value="b">Baik</option>
                            <option value="rr">Rusak Ringan</option>
                            <option value="rb">Rusak Berat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="keterangan" class="col-form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i>&nbsp; Simpan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp; Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('js')
<script src="https://cdn.datatables.net/v/dt/dt-2.3.2/b-3.2.3/b-colvis-3.2.3/b-html5-3.2.3/fc-5.0.4/fh-4.0.2/r-3.0.4/datatables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/i18n/defaults-id_ID.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://unpkg.com/@jarstone/dselect/dist/js/dselect.js"></script>

<script>
    let tb_aset
    $(document).ready(function() {

        $('.select2').select2({
            theme: 'classic',
            // minimumInputLength: 3,
        })

        // $('.selectpicker').selectpicker()
        // dselect(document.querySelector('#kondisi'), {
        //     search: true,
        // })
        // dselect(document.querySelector('#subdata'), {
        //     search: true,
        // })
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function _add() {
        $('#title_modal').text('Tambah Data')
        $('#form_add')[0].reset()
        const uid = document.getElementById('uuid')
        if (uid) {
            $('#uuid').remove()
        }
        $('#form_add').attr('action', '{{ route("parameter.save") }}')
        $('#add_new').modal('show', {backdrop: 'static', keyboard: false})
        $('#kondisi').selectpicker()
    }
</script>
@endsection