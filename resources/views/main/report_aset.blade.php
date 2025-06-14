@extends('layouts.layout')


@section('title', 'Laporan Aset')


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
            <div class="card-header">
                <div class="row g-3">
                    <div class="col">
                        <h5>Data Aset Per Ruangan</h5>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <select class="form-select select2" id="filter" data-allow-clear="true" aria-label="Pilih Ruang/Lokasi">
                                <option value="">Semua Ruangan</option>
                                @foreach ($ruangan as $ruang)
                                    <option value="{{ $ruang->lokasi }}">{{ $ruang->lokasi }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-primary waves-effect" type="button" onclick="_filter()">Filter</button>
                            <button id="bt_print" type="button" class="btn btn-outline-info"><i class="ti ti-printer"></i>&nbsp;&nbsp; Cetak</button>
                        </div>
                        {{-- <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Dropdown
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                <a class="dropdown-item" href="javascript:void(0);">Dropdown link</a>
                                <a class="dropdown-item" href="javascript:void(0);">Dropdown link</a>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            {{-- <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table table-striped table-hover" id="tb_aset">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle">#</th>
                            <th rowspan="2" class="align-middle">Nama Barang</th>
                            <th rowspan="2" class="align-middle">Merek Barang</th>
                            <th rowspan="2" class="align-middle">Tahun Pembelian</th>
                            <th rowspan="2" class="align-middle">Kode Barang</th>
                            <th rowspan="2" class="align-middle">Jumlah Barang</th>
                            <th rowspan="2" class="align-middle">Harga Beli</th>
                            <th colspan="3" class="text-center">Kondisi Barang</th>
                            <th rowspan="2" class="align-middle">Update</th>
                        </tr>
                        <tr>
                            <th>Baik</th>
                            <th>Ruask Ringan</th>
                            <th>Rusak Berat</th>
                        </tr>
                    </thead>
                </table>
            </div> --}}

            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table table-striped table-hover" id="tb_aset">
                    <thead>
                        <tr>
                            <th class="align-middle">#</th>
                            <th class="align-middle">Nama Barang</th>
                            <th class="align-middle">Merek Barang</th>
                            <th class="align-middle">Tahun Pembelian</th>
                            <th class="align-middle">Kode Barang</th>
                            <th class="align-middle">Jumlah</th>
                            <th class="align-middle">Harga Beli</th>
                            <th class="align-middle">Update</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailAset" tabindex="-1" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-simple modal-edit-user">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modalTitle">Detail Data</h3>
                    <p class="text-muted">&nbsp;</p>
                </div>

                <div class="row g-3 mb-5" id="isContent"></div>

                <div class="col-12 text-center">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-circle-x"></i>&nbsp; Tutup</button>
                </div>
            </div>
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
    var dt_basic_table = $('#tb_aset'),
        dt_complex_header_table = $('.dt-complex-header'),
        dt_row_grouping_table = $('.dt-row-grouping'),
        dt_multilingual_table = $('.dt-multilingual'),
        dt_basic;
    $(document).ready(function() {
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
                    url: '{{ route("report.aset.ss") }}',
                },
                ordering: false,
                searchable: false,
                columns: [
                    { data: null },
                    { data: 'nama' },
                    { data: 'merek' },
                    { data: 'tahun' },
                    { data: 'kode' },
                    { data: 'jumlah' },
                    { data: 'harga' },
                    //{ data: 'kondisi_baik' },
                    // { data: 'kondisi_ringan' },
                    // { data: 'kondisi_berat' },
                    { data: 'update' },
                ],
                // dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                dom: '<"row"<"col-sm-12 col-md-6"l>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
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

        // $('#filter').select2()

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

    function _detail(datas, contents, price) {
        const data = JSON.parse(atob(datas))
        const obj  = JSON.parse(atob(contents))
        console.log(data, obj)

        $('#modalTitle').html(`Detail Barang <b>${data.nama_barang}</b>`)
        let text = ''
        text += `<div class="col-12">
                    <label class="form-label" for="uraian">Uraian</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ti ti-list-details"></i></span>
                        <input type="text" class="form-control" value="   ${data.uraian}" disabled />
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="uraian">Nama Barang</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ti ti-letter-case"></i></span>
                        <input type="text" class="form-control" value="   ${data.nama_barang}" disabled />
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="uraian">Merek</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ti ti-article"></i></span>
                        <input type="text" class="form-control" value="   ${data.merek_barang}" disabled />
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="uraian">Tipe Barang</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ti ti-category-2"></i></span>
                        <input type="text" class="form-control" value="   ${data.type_barang ?? '-'}" disabled />
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="uraian">Ukuran/Dimensi</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ti ti-dimensions"></i></span>
                        <input type="text" class="form-control" value="   ${data.ukuran_barang ?? '-'}" disabled />
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="uraian">Bahan</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ti ti-atom"></i></span>
                        <input type="text" class="form-control" value="   ${data.bahan ?? '-'}" disabled />
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="uraian">Total Harga Pembelian</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ti ti-cash"></i></span>
                        <input type="text" class="form-control" value="   ${price}" disabled />
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="uraian">Tahun Pembelian</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                        <input type="text" class="form-control" value="   ${data.tahun_beli}" disabled />
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="uraian">Ruang/Lokasi</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ti ti-home-2"></i></span>
                        <input type="text" class="form-control" value="   ${data.lokasi}" disabled />
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="uraian">Jumlah Barang</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ti ti-calculator"></i></span>
                        <input type="text" class="form-control" value="   ${obj.length ?? 0}" disabled />
                    </div>
                </div>`
            
            text += `<h5 class="text-center mt-5">&middot; <u>Daftar Barang</u> &middot;</h5>`

            text += `<table class="table table-bordered table-hover" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Register</th>
                                <th>Harga Pembelian</th>
                                <th>Kondisi</th>
                                <th>Keterangan</th>
                                <th>Update</th>
                            </tr>
                        </thead><tbody>`
            
            if (obj && obj.length > 0) {
                obj.forEach((ob, i) => {
                    text += `<tr>
                                <td>${i+1}</td>
                                <td>${ob.kode_urut < 9 ? ('00' + ob.kode_urut) : ((ob.kode_urut > 9 && ob.kode_urut < 99) ? ('0' + ob.kode_urut) : ob.kode_urut)}</td>
                                <td>${ob.harga_beli.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</td>
                                <td>${ob.kondisi_barang === 'b' ? ('Baik') : (ob.kondisi_barang === 'rr' ? ('Rusak Ringan') : ('Rusak Berat'))}</td>
                                <td>${ob.keterangan}</td>
                                <td>${ob.updated_at ?? ob.created_at}</td>
                            </tr>`
                })
            }

            text += `</tbody></table>`


        $('#isContent').html(text)


        $('#detailAset').modal('show')
    }

    function _filter() {
        const filt = $('#filter').val()
        $('#tb_aset').DataTable().ajax.url('{{ route("report.aset.ss") }}?ruang=' + filt).load()
    }
</script>
@endsection