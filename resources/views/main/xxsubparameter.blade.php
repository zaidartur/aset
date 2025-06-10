@extends('layouts.layout')

@section('title', 'Sub Parameter')
@section('master', 'Master Data')


@section('css')
<link href="https://cdn.datatables.net/v/dt/dt-2.3.2/b-3.2.3/b-colvis-3.2.3/b-html5-3.2.3/fc-5.0.4/fh-4.0.2/r-3.0.4/datatables.min.css" rel="stylesheet" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
                <h6>Tabel Sub Parameter</h6>
            </div>
            
            <div class="card-body">
                <div class="mb-5">
                    <button type="button" onclick="_add()" class="btn btn-success btn-sm mb-0 w-20"><i class="fas fa-plus-circle"></i> Buat Sub Parameter</button>
                    <button type="button" onclick="_import()" class="btn btn-primary btn-sm mb-0 w-20"><i class="fas fa-file-excel" aria-hidden="true"></i> Import Sub Parameter</button>
                    <button type="button" onclick="_template()" class="btn btn-info btn-sm mb-0 w-20"><i class="fas fa-download" aria-hidden="true"></i> Download Template</button>
                </div>
                <div class="table-responsive p-3">
                    <table class="table align-items-center mb-0" id="tb_params">
                        <thead>
                            <tr>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kode Parameter</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Parameter</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Parent</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                {{-- <th class="text-secondary opacity-7"></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($lists as $i => $item)
                                <tr>
                                    <td class="text-left">{{ $i+1 }}</td>
                                    <td class="text-uppercase font-weight-bolder">{{ $item->kode_subdata }}</td>
                                    <td class="text-uppercase font-weight-bolder">{{ $item->uraian }}</td>
                                    <td class="text-uppercase font-weight-bolder" title="{{ $item->isparam->kode_aset }}">{{ $item->isparam->uraian }}</td>
                                    <td class="text-left">{{ $item->keterangan }}</td>
                                    <td class="text-left">
                                        <button type="button" class="btn btn-info btn-sm btn-icon btn-round" onclick="_edit('{{ $item->uuid_subdata }}')" title="Edit Parameter"><i class="fas fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm btn-icon btn-round" onclick="_delete('{{ $item->uuid_subdata }}')" title="Hapus Parameter"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Tabel Material Barang</h6>
            </div>
            
            <div class="card-body">
                <div class="mb-5">
                    <button type="button" onclick="_add_material()" class="btn btn-success btn-sm mb-0 w-20"><i class="fas fa-plus-circle"></i> Buat Baru</button>
                    <button type="button" onclick="_import_material()" class="btn btn-primary btn-sm mb-0 w-20"><i class="fas fa-file-excel" aria-hidden="true"></i> Import Data</button>
                    <button type="button" onclick="_template_material()" class="btn btn-info btn-sm mb-0 w-20"><i class="fas fa-download" aria-hidden="true"></i> Download Template</button>
                </div>
                <div class="table-responsive p-3">
                    <table class="table align-items-center mb-0" id="tb_material">
                        <thead>
                            <tr>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Id Material</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Material</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                {{-- <th class="text-secondary opacity-7"></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bahans as $b => $bahan)
                                <tr>
                                    <td class="text-left">{{ $b+1 }}</td>
                                    <td class="text-uppercase font-weight-bolder">{{ $bahan->uuid_bahan }}</td>
                                    <td class="text-uppercase font-weight-bolder">{{ $bahan->nama_bahan }}</td>
                                    <td class="text-left">{{ $bahan->keterangan }}</td>
                                    <td class="text-left">
                                        <button type="button" class="btn btn-info btn-sm btn-icon btn-round" onclick="_edit_material('{{ $bahan->uuid_bahan }}')" title="Edit Parameter"><i class="fas fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm btn-icon btn-round" onclick="_delete_material('{{ $bahan->uuid_bahan }}')" title="Hapus Parameter"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_new" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title_modal">Buat Parameter</h5>
            </div>
            <form action="#" method="POST" id="form_add">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode" class="col-form-label">Kode Parameter *</label>
                        <div class="w-100">
                            <select name="parent" id="parent" class="form-control select2" style="width: 100%;" required>
                                @if (count($params) > 0)
                                    <option value="">Pilih Kode Parameter</option>
                                    @foreach ($params as $param)
                                        <option value="{{ $param->uuid_aset }}">{{ $param->kode_aset }} - {{ $param->uraian }}</option>
                                    @endforeach
                                @endif
                                <option value="">Tidak Ada Data</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kode" class="col-form-label">Kode Sub Parameter (lengkap)*</label>
                        <input type="text" class="form-control" id="kode" name="kode" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-form-label">Uraian *</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
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

<div class="modal fade" id="add_material" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title_bahan">Buat Data</h5>
            </div>
            <form action="#" method="POST" id="form_material">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode" class="col-form-label">Nama Bahan/Material*</label>
                        <input type="text" class="form-control" id="bahan" name="nama" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="keterangan" class="col-form-label">Keterangan</label>
                        <textarea class="form-control" id="is_keterangan" name="keterangan"></textarea>
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

<script>
    let tbparam, tbmaterial
    $(document).ready(function() {
        tbparam = $('#tb_params').DataTable({
            language: {
                processing: "Memproses ...",
            },
            dom: '<"top"fl>rt<"bottom"ip><"clear">',
            proccessing: true,
            serverSide: true,
            lengthMenu: [10, 25, 50, 100],
            pageLength: 10,
            paging: true,
            ajax: {
                url: '{{ route("subparameter.ss") }}',
                // type: 'GET',
            },
            columns: [
                {
                    data: null,
                },
                {
                    data: 'kode',
                },
                {
                    data: 'uraian'
                },
                {
                    data: 'parameter'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'opsi'
                },
            ],
            columnsDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: 0,
                },
                {
                    searchable: true,
                    orderable: false,
                    targets: 1,
                },
                {
                    searchable: true,
                    orderable: false,
                    targets: 2,
                },
                {
                    searchable: false,
                    orderable: false,
                    targets: 3,
                },
                {
                    searchable: true,
                    orderable: false,
                    targets: 4,
                },
                {
                    searchable: false,
                    orderable: false,
                    targets: 5,
                },
            ]
        })
        tbparam.on('draw.dt', function() {
            var PageInfo = $('#tb_params').DataTable().page.info();
            tbparam.column(0, {
                page: 'current'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });

        tbmaterial = $('#tb_material').DataTable({
            dom: '<"top"fl>rt<"bottom"ip><"clear">',
        })
    })

    $('.select2').select2({
        theme: 'classic',
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function _import() {
        Swal.fire({
            title: 'Pilih file Excel',
            html: '<p class="text-warning">Pastikan data sudah sesuai dengan template yang disediakan</p>',
            input: 'file',
            inputAttributes: {
                accept: '.xlsx,.xls',
            },
            showCancelButton: true,
            confirmButtonText: 'Import',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                $(".swal2-file").change(function () {
                    var reader = new FileReader();
                    reader.readAsDataURL(this.files[0]);
                });
            },
            preConfirm: (login) => {
                Swal.getCancelButton().setAttribute('style', 'display:none;')
                Swal.getConfirmButton().setAttribute('style', 'display:none;')
                $('.swal2-file').prop('readonly', 'true')

                if ($('.swal2-file')[0].files[0] == null) {
                    Swal.showValidationMessage('File belum dimasukkan')

                    Swal.getCancelButton().setAttribute('style', 'display:block;')
                    Swal.getConfirmButton().setAttribute('style', 'display:block;')
                    $('.swal2-file').prop('readonly', 'false')
                } else {
                    var formData = new FormData();
                    var file = $('.swal2-file')[0].files[0];
                    formData.append("fileToUpload", file);
                    return $.ajax({
                        url: "{{ route('subparameter.import') }}",
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (a) {
                            if (a.res === 'success') {
                                return a;
                            } else {
                                Swal.showValidationMessage(
                                    'Jenis file salah'
                                )
                            }

                            Swal.getCancelButton().setAttribute('style', 'display:block;')
                            Swal.getConfirmButton().setAttribute('style', 'display:block;')
                            $('.swal2-file').prop('readonly', 'false')

                        },
                        error: function (err) {
                            Swal.getCancelButton().setAttribute('style', 'display:block;')
                            Swal.getConfirmButton().setAttribute('style', 'display:block;')
                            $('.swal2-file').prop('readonly', 'false')

                            Swal.fire({
                                title: '',
                                text: 'Request failed',
                                icon: 'error',
                            })
                        },
                    })
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            Swal.getCancelButton().setAttribute('style', 'display:block;')
            Swal.getConfirmButton().setAttribute('style', 'display:block;')
            $('.swal2-file').prop('readonly', 'false')

            if (result.isConfirmed) {
                const res = result.value
                Swal.fire({
                    title: 'File berhasil diimport',
                    text: `Total: ${res.total}, Sukses: ${res.success}, Gagal: ${res.incomplete}, Duplikat: ${res.duplicate}`,
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                }).then(function () {
                    location.reload();
                })
            }
        });
    }

    function _add() {
        $('#title_modal').text('Buat Parameter')
        $('#form_add')[0].reset()
        const uid = document.getElementById('uuid')
        if (uid) {
            $('#uuid').remove()
        }
        $('#form_add').attr('action', '{{ route("subparameter.save") }}')
        $('#add_new').modal('show', {backdrop: 'static', keyboard: false})
    }

    function _edit(uid) {
        $.ajax({
            url: "/sub-parameter/detail-sub-parameter/" + uid,
            type: "GET",
            dataType: "JSON",
            success: function(res) {
                console.log('res', res)
                if (res) {
                    $('#title_modal').text('Edit Parameter')
                    $('#form_add').attr('action', '{{ route("subparameter.update") }}')
                    $('#parent').val(res.parent).trigger('change')
                    $('#kode').val(res.kode_subdata)
                    $('#nama').val(res.uraian)
                    $('#keterangan').val(res.keterangan)
                    const uid = document.getElementById('uuid')
                    if (uid) {
                        console.log('uuid exists')
                        $('#uuid').val(res.uuid_aset)
                    } else {
                        console.log('uuid created')
                        $('#form_add').append(`<input type="hidden" name="uuid" id="uuid" value="${res.uuid_subdata}" required>`)
                    }
                    $('#add_new').modal('show')
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "Parameter tidak ditemukan!"
                    })
                }
            },
            error: function() {
                Toast.fire({
                    icon: "error",
                    title: "Terjadi kesalahan pada sistem!"
                })
            }
        })
    }

    function _delete(uid) {
        Swal.fire({
            title: "Hapus Data?",
            text: "Anda yakin ingin menghapusnya?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Konfirmasi!",
            cancelButtonText: 'Batalkan',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('subparameter.drop') }}",
                    type: "POST",
                    data: {uuid: uid},
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Data berhasil dihapus!.",
                                icon: "success",
                            }).then(function() {
                                location.reload()
                            })
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: "Data gagal dihapus!"
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: "error",
                            title: "Terjadi kesalahan pada sistem!"
                        })
                    }
                })
            }
        });
    }
</script>

<script>
    function _import_material() {
        Swal.fire({
            title: 'Pilih file Excel',
            html: '<p class="text-warning">Pastikan data sudah sesuai dengan template yang disediakan</p>',
            input: 'file',
            inputAttributes: {
                accept: '.xlsx,.xls',
            },
            showCancelButton: true,
            confirmButtonText: 'Import',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            onBeforeOpen: () => {
                $(".swal2-file").change(function () {
                    var reader = new FileReader();
                    reader.readAsDataURL(this.files[0]);
                });
            },
            preConfirm: (login) => {
                Swal.getCancelButton().setAttribute('style', 'display:none;')
                Swal.getConfirmButton().setAttribute('style', 'display:none;')
                $('.swal2-file').prop('readonly', 'true')

                if ($('.swal2-file')[0].files[0] == null) {
                    Swal.showValidationMessage('File belum dimasukkan')

                    Swal.getCancelButton().setAttribute('style', 'display:block;')
                    Swal.getConfirmButton().setAttribute('style', 'display:block;')
                    $('.swal2-file').prop('readonly', 'false')
                } else {
                    var formData = new FormData();
                    var file = $('.swal2-file')[0].files[0];
                    formData.append("fileToUpload", file);
                    return $.ajax({
                        url: "{{ route('bahan.import') }}",
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (a) {
                            if (a.res === 'success') {
                                return a;
                            } else {
                                Swal.showValidationMessage(
                                    'Jenis file salah'
                                )
                            }

                            Swal.getCancelButton().setAttribute('style', 'display:block;')
                            Swal.getConfirmButton().setAttribute('style', 'display:block;')
                            $('.swal2-file').prop('readonly', 'false')

                        },
                        error: function (err) {
                            Swal.getCancelButton().setAttribute('style', 'display:block;')
                            Swal.getConfirmButton().setAttribute('style', 'display:block;')
                            $('.swal2-file').prop('readonly', 'false')

                            Swal.fire({
                                title: '',
                                text: 'Request failed',
                                icon: 'error',
                            })
                        },
                    })
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            Swal.getCancelButton().setAttribute('style', 'display:block;')
            Swal.getConfirmButton().setAttribute('style', 'display:block;')
            $('.swal2-file').prop('readonly', 'false')

            if (result.isConfirmed) {
                const res = result.value
                Swal.fire({
                    title: 'File berhasil diimport',
                    text: `Total: ${res.total}, Sukses: ${res.success}, Gagal: ${res.incomplete}, Duplikat: ${res.duplicate}`,
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                }).then(function () {
                    location.reload();
                })
            }
        });
    }

    function _add_material() {
        $('#title_bahan').text('Tambah Data')
        $('#form_material')[0].reset()
        const uid = document.getElementById('uuid_bahan')
        if (uid) {
            $('#uuid_bahan').remove()
        }
        $('#form_material').attr('action', '{{ route("bahan.save") }}')
        $('#add_material').modal('show', {backdrop: 'static', keyboard: false})
    }

    function _edit_material(uid) {
        $.ajax({
            url: "/master-bahan/detail-bahan/" + uid,
            type: "GET",
            dataType: "JSON",
            success: function(res) {
                console.log('res', res)
                if (res) {
                    $('#title_bahan').text('Edit Data')
                    $('#form_material').attr('action', '{{ route("bahan.update") }}')
                    $('#bahan').val(res.nama_bahan)
                    $('#is_keterangan').val(res.keterangan)
                    const uid = document.getElementById('uuid_bahan')
                    if (uid) {
                        $('#uuid_bahan').val(res.uuid_aset)
                    } else {
                        $('#form_material').append(`<input type="hidden" name="uuid" id="uuid_bahan" value="${res.uuid_bahan}" required>`)
                    }
                    $('#add_material').modal('show')
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "Data tidak ditemukan!"
                    })
                }
            },
            error: function() {
                Toast.fire({
                    icon: "error",
                    title: "Terjadi kesalahan pada sistem!"
                })
            }
        })
    }

    function _delete_material(uid) {
        Swal.fire({
            title: "Hapus Data?",
            text: "Anda yakin ingin menghapusnya?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Konfirmasi!",
            cancelButtonText: 'Batalkan',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('bahan.drop') }}",
                    type: "POST",
                    data: {uuid: uid},
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Data berhasil dihapus!.",
                                icon: "success",
                            }).then(function() {
                                location.reload()
                            })
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: "Data gagal dihapus!"
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: "error",
                            title: "Terjadi kesalahan pada sistem!"
                        })
                    }
                })
            }
        });
    }
</script>
@endsection