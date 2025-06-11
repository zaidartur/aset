@extends('layouts.layout')

@section('title', 'Sub Parameter')
@section('master', 'Master Data')


@section('css')
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/flatpickr/flatpickr.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/select2/select2.css" />
<!-- Row Group CSS -->
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
<!-- Form Validation -->
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/@form-validation/umd/styles/index.min.css" />
@endsection


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table" id="tb_subparam">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Uraian</th>
                        <th>Parent</th>
                        <th>Keterangan</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="add_new" tabindex="-1" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title_modal">Buat Parameter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="form_add">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="parent" class="form-label">Kode Parameter *</label>
                        <div class="w-100">
                            <select name="parent" id="parent" class="select2 form-select" data-allow-clear="true" required>
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
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"><i class="ti ti-circle-x"></i>&nbsp; Tutup</button>
                    <button type="submit" class="btn btn-outline-success"><i class="ti ti-device-floppy"></i>&nbsp; Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('js')
<!-- Vendors JS -->
<script src="{{ asset('') }}assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/select2/select2.js"></script>
<!-- Flat Picker -->
<script src="{{ asset('') }}assets/vendor/libs/moment/moment.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/flatpickr/flatpickr.js"></script>
<!-- Form Validation -->
<script src="{{ asset('') }}assets/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>

<script>
    let tbparam, tbmaterial
    $(document).ready(function() {
        var dt_basic_table = $('#tb_subparam'),
            dt_complex_header_table = $('.dt-complex-header'),
            dt_row_grouping_table = $('.dt-row-grouping'),
            dt_multilingual_table = $('.dt-multilingual'),
            dt_basic;

        // DataTable with buttons
        // --------------------------------------------------------------------
        if (dt_basic_table.length) {
            dt_basic = dt_basic_table.DataTable({
                language: {
                    processing: "Memproses ...",
                },
                proccessing: true,
                serverSide: true,
                paging: true,
                // ordering: false,
                ajax: {
                    url: '{{ route("subparameter.ss") }}',
                },
                columns: [
                    { data: null },
                    { data: 'kode' },
                    { data: 'uraian' },
                    { data: 'parameter'},
                    { data: 'keterangan' },
                    { data: 'opsi' },
                ],
                columnDefs: [
                    {
                        targets: 0,
                        searchable: false,
                        orderable: false,
                    },
                    {
                        target: 1,
                        orderable: false,
                    },
                    {
                        responsivePriority: 1,
                        targets: 2,
                        orderable: false,
                    },
                    {
                        targer: 3,
                        orderable: false,
                    },
                    {
                        targer: 4,
                        orderable: false,
                    },
                    {
                        targets: 5,
                        searchable: false,
                        orderable: false,
                    },
                ],
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                buttons: [
                    {
                        text: '<i class="ti ti-circle-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Buat Parameter</span>',
                        className: 'btn btn-success me-2',
                        action: function (e, dt, node, config) {
                            _add()
                        }
                    },
                    {
                        text: '<i class="ti ti-file-spreadsheet me-sm-1"></i> <span class="d-none d-sm-inline-block">Import Parameter</span>',
                        className: 'btn btn-primary me-2',
                        action: function (e, dt, node, config) {
                            _import()
                        }
                    },
                    {
                        text: '<i class="ti ti-download me-sm-1"></i> <span class="d-none d-sm-inline-block">Download Template</span>',
                        className: 'btn btn-label-info me-2',
                        action: function (e, dt, node, config) {
                            _template()
                        }
                    },
                    // {
                    //     extend: 'collection',
                    //     className: 'btn btn-label-primary dropdown-toggle me-2',
                    //     text: '<i class="ti ti-file-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    //     buttons: [
                    //         {
                    //             extend: 'print',
                    //             text: '<i class="ti ti-printer me-1" ></i>Print',
                    //             className: 'dropdown-item',
                    //             exportOptions: {
                    //                 columns: [3, 4, 5, 6, 7],
                    //                 // prevent avatar to be display
                    //                 format: {
                    //                     body: function (inner, coldex, rowdex) {
                    //                         if (inner.length <= 0) return inner;
                    //                         var el = $.parseHTML(inner);
                    //                         var result = '';
                    //                         $.each(el, function (index, item) {
                    //                             if (item.classList !== undefined && item.classList.contains('user-name')) {
                    //                                 result = result + item.lastChild.firstChild.textContent;
                    //                             } else if (item.innerText === undefined) {
                    //                                 result = result + item.textContent;
                    //                             } else result = result + item.innerText;
                    //                         });
                    //                         return result;
                    //                     }
                    //                 }
                    //             },
                    //             customize: function (win) {
                    //                 //customize print view for dark
                    //                 $(win.document.body)
                    //                     .css('color', config.colors.headingColor)
                    //                     .css('border-color', config.colors.borderColor)
                    //                     .css('background-color', config.colors.bodyBg);
                    //                 $(win.document.body)
                    //                     .find('table')
                    //                     .addClass('compact')
                    //                     .css('color', 'inherit')
                    //                     .css('border-color', 'inherit')
                    //                     .css('background-color', 'inherit');
                    //             }
                    //         },
                    //         {
                    //             extend: 'csv',
                    //             text: '<i class="ti ti-file-text me-1" ></i>Csv',
                    //             className: 'dropdown-item',
                    //             exportOptions: {
                    //                 columns: [3, 4, 5, 6, 7],
                    //                 // prevent avatar to be display
                    //                 format: {
                    //                     body: function (inner, coldex, rowdex) {
                    //                         if (inner.length <= 0) return inner;
                    //                         var el = $.parseHTML(inner);
                    //                         var result = '';
                    //                         $.each(el, function (index, item) {
                    //                             if (item.classList !== undefined && item.classList.contains('user-name')) {
                    //                                 result = result + item.lastChild.firstChild.textContent;
                    //                             } else if (item.innerText === undefined) {
                    //                                 result = result + item.textContent;
                    //                             } else result = result + item.innerText;
                    //                         });
                    //                         return result;
                    //                     }
                    //                 }
                    //             }
                    //         },
                    //         {
                    //             extend: 'excel',
                    //             text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
                    //             className: 'dropdown-item',
                    //             exportOptions: {
                    //                 columns: [3, 4, 5, 6, 7],
                    //                 // prevent avatar to be display
                    //                 format: {
                    //                     body: function (inner, coldex, rowdex) {
                    //                         if (inner.length <= 0) return inner;
                    //                         var el = $.parseHTML(inner);
                    //                         var result = '';
                    //                         $.each(el, function (index, item) {
                    //                             if (item.classList !== undefined && item.classList.contains('user-name')) {
                    //                                 result = result + item.lastChild.firstChild.textContent;
                    //                             } else if (item.innerText === undefined) {
                    //                                 result = result + item.textContent;
                    //                             } else result = result + item.innerText;
                    //                         });
                    //                         return result;
                    //                     }
                    //                 }
                    //             }
                    //         },
                    //         {
                    //             extend: 'pdf',
                    //             text: '<i class="ti ti-file-description me-1"></i>Pdf',
                    //             className: 'dropdown-item',
                    //             exportOptions: {
                    //                 columns: [3, 4, 5, 6, 7],
                    //                 // prevent avatar to be display
                    //                 format: {
                    //                     body: function (inner, coldex, rowdex) {
                    //                         if (inner.length <= 0) return inner;
                    //                         var el = $.parseHTML(inner);
                    //                         var result = '';
                    //                         $.each(el, function (index, item) {
                    //                             if (item.classList !== undefined && item.classList.contains('user-name')) {
                    //                                 result = result + item.lastChild.firstChild.textContent;
                    //                             } else if (item.innerText === undefined) {
                    //                                 result = result + item.textContent;
                    //                             } else result = result + item.innerText;
                    //                         });
                    //                         return result;
                    //                     }
                    //                 }
                    //             }
                    //         },
                    //         {
                    //             extend: 'copy',
                    //             text: '<i class="ti ti-copy me-1" ></i>Copy',
                    //             className: 'dropdown-item',
                    //             exportOptions: {
                    //                 columns: [3, 4, 5, 6, 7],
                    //                 // prevent avatar to be display
                    //                 format: {
                    //                     body: function (inner, coldex, rowdex) {
                    //                         if (inner.length <= 0) return inner;
                    //                         var el = $.parseHTML(inner);
                    //                         var result = '';
                    //                         $.each(el, function (index, item) {
                    //                             if (item.classList !== undefined && item.classList.contains('user-name')) {
                    //                                 result = result + item.lastChild.firstChild.textContent;
                    //                             } else if (item.innerText === undefined) {
                    //                                 result = result + item.textContent;
                    //                             } else result = result + item.innerText;
                    //                         });
                    //                         return result;
                    //                     }
                    //                 }
                    //             }
                    //         }
                    //     ]
                    // },
                ],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data['full_name'];
                            }
                        }),
                        type: 'column',
                        renderer: function (api, rowIdx, columns) {
                            var data = $.map(columns, function (col, i) {
                            return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                                ? '<tr data-dt-row="' +
                                        col.rowIndex +
                                        '" data-dt-column="' +
                                        col.columnIndex +
                                        '">' +
                                            '<td>' +
                                                col.title +
                                                ':' +
                                            '</td> ' +
                                            '<td>' +
                                                col.data +
                                            '</td>' +
                                        '</tr>'
                                : '';
                            }).join('');

                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                }
            });

            $('div.head-label').html('<h5 class="card-title mb-0">Tabel Sub Parameter</h5>');
            dt_basic.on('draw.dt', function() {
                var PageInfo = $('#tb_subparam').DataTable().page.info();
                dt_basic.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });
        }

        $('#parent').select2({
            placeholder: 'Pilih Kode Parameter',
            search: true,
            allowClear: true,
            dropdownParent: $('#add_new'),
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
        $('#add_new').modal('show')
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
                        $('#uuid').val(res.uuid_subdata)
                    } else {
                        $('#form_add').append(`<input type="hidden" name="uuid" id="uuid" value="${res.uuid_subdata}" required>`)
                    }
                    $('#add_new').modal('show')
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
@endsection