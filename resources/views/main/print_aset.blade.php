@extends('layouts.layout')

@section('title', 'Labeling Aset')
@section('master', '')
    

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
            <table class="datatables-basic table" id="tb_aset" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 15%;">Nama Barang</th>
                        <th style="width: 15%;">Merek Barang</th>
                        <th style="width: 5%;">Tahun Pembelian</th>
                        <th style="width: 15%;">Ruang</th>
                        <th style="width: 10%;">Kondisi</th>
                        <th style="width: 20%;">Kode</th>
                        <th style="width: 15%;">Opsi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection


@section('js')
<!-- Vendors JS -->
<script src="{{ asset('') }}assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/select2/select2.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/cleavejs/cleave.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/cleavejs/cleave-phone.js"></script>
<!-- Flat Picker -->
<script src="{{ asset('') }}assets/vendor/libs/moment/moment.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/flatpickr/flatpickr.js"></script>
<!-- Form Validation -->
<script src="{{ asset('') }}assets/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/bs-stepper/bs-stepper.js"></script>

<script>
    let tb_aset
    $(document).ready(function() {
        var dt_basic_table = $('#tb_aset'),
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
                ajax: {
                    url: '{{ route("report.label.ss") }}',
                },
                columns: [
                    { data: null },
                    { data: 'nama' },
                    { data: 'merek' },
                    { data: 'tahun' },
                    { data: 'ruang' },
                    { data: 'kondisi' },
                    { data: 'kode' },
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
                        text: '<i class="ti ti-circle-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Tambah Data</span>',
                        className: 'btn btn-success me-2',
                        action: function (e, dt, node, config) {
                            _add()
                        }
                    },
                    {
                        text: '<i class="ti ti-file-spreadsheet me-sm-1"></i> <span class="d-none d-sm-inline-block">Import Data</span>',
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

            $('div.head-label').html('<h5 class="card-title mb-0">Tabel Data Aset</h5>');
            dt_basic.on('draw.dt', function() {
                var PageInfo = $('#tb_aset').DataTable().page.info();
                dt_basic.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });
        }

        $('#subdata').select2({
            placeholder: 'Pilih Kategori',
            // search: true,
            // allowClear: true,
            // dropdownParent: $('#add_new'),
        })

        const numeralMask = document.querySelectorAll('.numeral-mask');
        // Verification masking
        if (numeralMask.length) {
            numeralMask.forEach(e => {
                new Cleave(e, {
                    numeral: true
                });
            });
        }
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        var $state, val = state.element.value
        if (val === 'b') {
            $state = $(`<span><i class="ti ti-checks text-success"></i> ${state.text}</span>`)
        } else if (val === 'rr') {
            $state = $(`<span><i class="ti ti-egg-cracked text-warning"></i> ${state.text}</span>`)
        } else if (val === 'rb') {
            $state = $(`<span><i class="ti ti-alert-triangle text-danger"></i> ${state.text}</span>`)
        } else {
            $state = $(`<span>${state.text}</span>`)
        }
        return $state;
    }

    function _add() {
        $('#modalTitle').text('Tambah Data')
        $('#addForm')[0].reset()
        const uid = document.getElementById('uuid')
        if (uid) {
            $('#uuid').remove()
        }
        $('#parameter').val('').trigger('change')
        $('#uraian').empty().trigger("change")
        $select = $('#uraian').data('select2').$container
        $select.find('.select2-selection__placeholder').text('Pilih Kategori')

        $('#addForm').attr('action', '{{ route("aset.save") }}')

        $('#editUser').modal('show')
    }

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
                        url: "{{ route('aset.import') }}",
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

    function _detail(uid) {
        $.ajax({
            url: '/tabel-aset/detail/' + uid,
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {
                console.log(res)
                if (res.status === 'success') {
                    //
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

    function _edit(uid) {
        $.ajax({
            url: '/tabel-aset/detail/' + uid,
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {
                console.log(res)
                if (res.status === 'success') {
                    const data = res.data
                    $('#modalTitle').text('Edit Data')
                    $('#addForm').attr('action', '{{ route("aset.update") }}')

                    $('#parameter').val(data.kode_parent).trigger('change')
                    $('#nama').val(data.nama_barang)
                    $('#merek').val(data.merek_barang)
                    $('#tipe').val(data.type_barang)
                    $('#ukuran').val(data.ukuran_barang)
                    $('#bahan').val(data.bahan)
                    $('#harga').val(data.harga_beli)
                    $('#tahun').val(data.tahun_beli)
                    $('#ruang').val(data.lokasi)
                    if (data.kondisi_barang === 'b') {
                        document.getElementById('baik').checked = true
                    } else if (data.kondisi_barang === 'rr') {
                        document.getElementById('ringan').checked = true
                    } else if (data.kondisi_barang === 'rb') {
                        document.getElementById('berat').checked = true
                    }

                    setTimeout(() => {
                        $('#uraian').val(data.kode_utama).trigger('change')
                    }, 3000);
                    setTimeout(() => {
                        $('#urutan').val((data.kode_urut < 9 ? ('00' + 1) : ((data.kode_urut > 9 && data.kode_urut < 99) ? ('0' + data.kode_urut) : data.kode_urut) ))
                    }, 4000);

                    const uid = document.getElementById('uuid')
                    if (uid) {
                        $('#uuid').val(res.uuid_barang)
                    } else {
                        $('#addForm').append(`<input type="hidden" name="uuid" id="uuid" value="${data.uuid_barang}" required>`)
                    }

                    const numeralMask = document.querySelectorAll('.numeral-mask');
                    // Verification masking
                    if (numeralMask.length) {
                        numeralMask.forEach(e => {
                            new Cleave(e, {
                                numeral: true
                            });
                        });
                    }

                    $('#editUser').modal('show')
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

    function _delete(uid, name) {
        Swal.fire({
            title: "Hapus Data?",
            html: "Anda yakin ingin menghapus <b>"+ name +"</b>?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Konfirmasi!",
            cancelButtonText: 'Batalkan',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('aset.drop') }}",
                    type: "POST",
                    data: {uuid: uid},
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                title: "Berhasil!",
                                html: "<b>"+name+"</b> berhasil dihapus!.",
                                icon: "success",
                            }).then(function() {
                                location.reload()
                            })
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: "Barang gagal dihapus!"
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

    function _params(val, id) {
        $('#'+id).select2('close')
        $('#urutan').val('')
        $select = $('#uraian').data('select2').$container
        $select.find('.select2-selection__placeholder').text('Mencari data...')

        if (val) {
            $.ajax({
                url: '/tabel-aset/subdata/' + val,
                type: 'GET',
                dataType: 'JSON',
                success: function(res) {
                    $('#uraian').empty().trigger("change")
                    console.log(res.length, res)
                    if (res && res.length > 0) {
                        // var newOption = new Option('Pilih Uraian', '', false, false)
                        // $('#uraian').append(newOption).trigger('change')
                        res.forEach((r) => {
                            var newOptions = new Option((r.kode_subdata +' - '+ r.uraian), r.uuid_subdata, false, false)
                            $('#uraian').append(newOptions).trigger('change')
                        })

                        $select.find('.select2-selection__placeholder').text('Pilih Uraian')
                        $('#uraian').val('').trigger('change')
                    } else {
                        $select.find('.select2-selection__placeholder').text('Pilih Kategori...')
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
    }

    function _setNumber(val, id) {
        $('#urutan').val('')
        if (val) {
            $.ajax({
                url: '{{ route("aset.number") }}',
                type: 'POST',
                data: {uuid: val},
                dataType: 'JSON',
                success: function(res) {
                    const number = parseInt(res.last) + 1
                    if (number < 9) {
                        $('#urutan').val('00' + number)
                    } else if (number > 9 && number < 99) {
                        $('#urutan').val('0' + number)
                    } else if (number > 99 && number < 1000) {
                        $('#urutan').val(number)
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
    }

    function _test() {
        Toast.fire({
            icon: "error",
            title: "Terjadi kesalahan pada sistem!"
        })
    }
</script>
@endsection