@extends('layouts.layout')

@section('title', 'Parameter')
@section('master', 'Master Data')


@section('css')
<link href="https://cdn.datatables.net/v/dt/dt-2.3.2/b-3.2.3/b-colvis-3.2.3/b-html5-3.2.3/fc-5.0.4/fh-4.0.2/r-3.0.4/datatables.min.css" rel="stylesheet" crossorigin="anonymous">
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.tailwindcss.css"> --}}

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
                <h6>Tabel Parameter</h6>
            </div>
            
            <div class="card-body">
                <div class="mb-5">
                    <button type="button" onclick="_add()" class="btn btn-success btn-sm mb-0 w-20"><i class="fas fa-plus-circle"></i> Buat Parameter</button>
                    <button type="button" onclick="_import()" class="btn btn-primary btn-sm mb-0 w-20"><i class="fas fa-file-excel" aria-hidden="true"></i> Import Parameter</button>
                    <button type="button" onclick="_template()" class="btn btn-info btn-sm mb-0 w-20"><i class="fas fa-download" aria-hidden="true"></i> Download Template</button>
                </div>
                <div class="table-responsive p-3">
                    <table class="table align-items-center mb-0" id="tb_params">
                        <thead>
                            <tr>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kode Parameter</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Parameter</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                                {{-- <th class="text-secondary opacity-7"></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $i => $item)
                                <tr>
                                    <td class="text-left">{{ $i+1 }}</td>
                                    <td class="text-uppercase font-weight-bolder">{{ $item->kode_aset }}</td>
                                    <td class="text-uppercase font-weight-bolder">{{ $item->uraian }}</td>
                                    <td class="text-left">{{ $item->keterangan }}</td>
                                    <td class="text-left">
                                        <button type="button" class="btn btn-info btn-sm btn-icon btn-round" onclick="_edit('{{ $item->uuid_aset }}')" title="Edit Parameter"><i class="fas fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm btn-icon btn-round" onclick="_delete('{{ $item->uuid_aset }}')" title="Hapus Parameter"><i class="fas fa-trash"></i></button>
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
                        <input type="text" class="form-control" id="kode" name="kode" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-form-label">Nama Parameter *</label>
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
@endsection


@section('js')
<script src="https://cdn.datatables.net/v/dt/dt-2.3.2/b-3.2.3/b-colvis-3.2.3/b-html5-3.2.3/fc-5.0.4/fh-4.0.2/r-3.0.4/datatables.min.js" crossorigin="anonymous"></script>
{{-- <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.tailwindcss.js"></script> --}}

<script>
    let tbparam
    $(document).ready(function() {
        tbparam = $('#tb_params').DataTable({
            // dom: "flrtp",
            dom: '<"top"fl>rt<"bottom"ip><"clear">',
        })
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
                        url: "{{ route('parameter.import') }}",
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

            console.log('import', result)
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
        $('#form_add').attr('action', '{{ route("parameter.save") }}')
        $('#add_new').modal('show', {backdrop: 'static', keyboard: false})
    }

    function _edit(uid) {
        $.ajax({
            url: "/parameter/detail-parameter/" + uid,
            type: "GET",
            dataType: "JSON",
            success: function(res) {
                console.log('res', res)
                if (res) {
                    $('#title_modal').text('Edit Parameter')
                    $('#form_add').attr('action', '{{ route("parameter.update") }}')
                    $('#kode').val(res.kode_aset)
                    $('#nama').val(res.uraian)
                    $('#keterangan').val(res.keterangan)
                    const uid = document.getElementById('uuid')
                    if (uid) {
                        console.log('uuid exists')
                        $('#uuid').val(res.uuid_aset)
                    } else {
                        console.log('uuid created')
                        $('#form_add').append(`<input type="hidden" name="uuid" id="uuid" value="${res.uuid_aset}" required>`)
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
            title: "Hapus Parameter?",
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
                    url: "{{ route('parameter.drop') }}",
                    type: "POST",
                    data: {uuid: uid},
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Parameter berhasil dihapus!.",
                                icon: "success",
                            }).then(function() {
                                location.reload()
                            })
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: "Parameter gagal dihapus!"
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