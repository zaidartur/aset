@extends('layouts.layout')

@section('title', 'Data Aset')
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
                        <th style="width: 20%;">Keterangan</th>
                        <th style="width: 15%;">Opsi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-simple modal-edit-user">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modalTitle">Tambah Data</h3>
                    <p class="text-muted">&nbsp;</p>
                </div>

                <form id="addForm" class="row g-3" method="POST" action="#">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="parameter">Kategori</label>
                        <select id="parameter" name="parameter" class="select2 form-select" data-allow-clear="true" aria-label="Pilih Kategori" onchange="_params(this.value, this.id)">
                            @if (count($params) > 0)
                                <option value="">Pilih Kategori</option>
                                @foreach ($params as $param)
                                    <option value="{{ $param->uuid_aset }}">{{ $param->kode_aset }} - {{ $param->uraian }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="uraian">Uraian</label>
                        <select id="uraian" name="uraian" class="select2 form-select" data-allow-clear="true" aria-label="Pilih Kategori dahulu" onchange="_setNumber(this.value, this.id)">
                            <option value="" selected>Pilih Kategori</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="urutan">Kode Urut (otomatis)</label>
                        <input type="text" id="urutan" name="urutan" class="form-control" placeholder="000" required readonly />
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="nama">Nama Barang</label>
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Barang" required />
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="merek">Merek Barang</label>
                        <input type="text" id="merek" name="merek" class="form-control" placeholder="Nama merek" required />
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="tipe">Tipe Barang</label>
                        <input type="text" id="tipe" name="tipe" class="form-control" placeholder="Jenis/Tipe" />
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="ukuran">Ukuran/Dimensi</label>
                        <input type="text" id="ukuran" name="ukuran" class="form-control" placeholder="Ukuran barang" />
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="bahan">Bahan</label>
                        <input type="text" id="bahan" name="bahan" class="form-control" placeholder="Bahan utama" required />
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="harga">Harga Pembelian</label>
                        {{-- <input type="text" id="ukuran" name="ukuran" class="form-control numeral-mask" placeholder="Harga barang" /> --}}
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control numeral-mask" id="harga" name="harga" placeholder="Nominal harga" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="tahun">Tahun Pembelian</label>
                        <input type="text" id="tahun" name="tahun" class="form-control" placeholder="{{ date('Y') }}" required />
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="ruang">Ruang/Lokasi</label>
                        <input type="text" id="ruang" name="ruang" class="form-control" placeholder="Lokasi barang" required />
                    </div>
                    <div class="col-12 row g-3">
                        <label for="">Kondisi Barang</label>
                        <div class="col-md mb-md-0 mb-3">
                            <div class="form-check custom-option custom-option-icon">
                                <label class="form-check-label custom-option-content" for="baik">
                                    <span class="custom-option-body">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.336"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.1" d="M13.8179 4.54512L13.6275 4.27845C12.8298 3.16176 11.1702 3.16176 10.3725 4.27845L10.1821 4.54512C9.76092 5.13471 9.05384 5.45043 8.33373 5.37041L7.48471 5.27608C6.21088 5.13454 5.13454 6.21088 5.27608 7.48471L5.37041 8.33373C5.45043 9.05384 5.13471 9.76092 4.54512 10.1821L4.27845 10.3725C3.16176 11.1702 3.16176 12.8298 4.27845 13.6275L4.54512 13.8179C5.13471 14.2391 5.45043 14.9462 5.37041 15.6663L5.27608 16.5153C5.13454 17.7891 6.21088 18.8655 7.48471 18.7239L8.33373 18.6296C9.05384 18.5496 9.76092 18.8653 10.1821 19.4549L10.3725 19.7215C11.1702 20.8382 12.8298 20.8382 13.6275 19.7215L13.8179 19.4549C14.2391 18.8653 14.9462 18.5496 15.6663 18.6296L16.5153 18.7239C17.7891 18.8655 18.8655 17.7891 18.7239 16.5153L18.6296 15.6663C18.5496 14.9462 18.8653 14.2391 19.4549 13.8179L19.7215 13.6275C20.8382 12.8298 20.8382 11.1702 19.7215 10.3725L19.4549 10.1821C18.8653 9.76092 18.5496 9.05384 18.6296 8.33373L18.7239 7.48471C18.8655 6.21088 17.7891 5.13454 16.5153 5.27608L15.6663 5.37041C14.9462 5.45043 14.2391 5.13471 13.8179 4.54512Z" fill="#2d8227"></path> <path d="M13.8179 4.54512L13.6275 4.27845C12.8298 3.16176 11.1702 3.16176 10.3725 4.27845L10.1821 4.54512C9.76092 5.13471 9.05384 5.45043 8.33373 5.37041L7.48471 5.27608C6.21088 5.13454 5.13454 6.21088 5.27608 7.48471L5.37041 8.33373C5.45043 9.05384 5.13471 9.76092 4.54512 10.1821L4.27845 10.3725C3.16176 11.1702 3.16176 12.8298 4.27845 13.6275L4.54512 13.8179C5.13471 14.2391 5.45043 14.9462 5.37041 15.6663L5.27608 16.5153C5.13454 17.7891 6.21088 18.8655 7.48471 18.7239L8.33373 18.6296C9.05384 18.5496 9.76092 18.8653 10.1821 19.4549L10.3725 19.7215C11.1702 20.8382 12.8298 20.8382 13.6275 19.7215L13.8179 19.4549C14.2391 18.8653 14.9462 18.5496 15.6663 18.6296L16.5153 18.7239C17.7891 18.8655 18.8655 17.7891 18.7239 16.5153L18.6296 15.6663C18.5496 14.9462 18.8653 14.2391 19.4549 13.8179L19.7215 13.6275C20.8382 12.8298 20.8382 11.1702 19.7215 10.3725L19.4549 10.1821C18.8653 9.76092 18.5496 9.05384 18.6296 8.33373L18.7239 7.48471C18.8655 6.21088 17.7891 5.13454 16.5153 5.27608L15.6663 5.37041C14.9462 5.45043 14.2391 5.13471 13.8179 4.54512Z" stroke="#2d8227" stroke-width="1.3679999999999999" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M9 12L10.8189 13.8189V13.8189C10.9189 13.9189 11.0811 13.9189 11.1811 13.8189V13.8189L15 10" stroke="#2d8227" stroke-width="1.3679999999999999" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                        <span class="custom-option-title"> Baik </span>
                                        <small> Tidak ada kerusakan pada barang </small>
                                    </span>
                                    <input name="kondisi" class="form-check-input" type="radio" value="b" id="baik" required>
                                </label>
                            </div>
                        </div>
                        <div class="col-md mb-md-0 mb-3">
                            <div class="form-check custom-option custom-option-icon">
                                <label class="form-check-label custom-option-content" for="ringan">
                                    <span class="custom-option-body">
                                        <svg fill="#ca6716" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 496 496" xml:space="preserve" stroke="#ca6716" stroke-width="10.416"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M256,160V0h-43.744l-47.08,56.496l31.096,31.088l-83.144,72.064l33.344-66.688l-40-24L140.944,0H0v160 c0,61.448,44.24,114.248,104,125.616V448H75.776c-25.12,0-47.696,13.952-58.936,36.424L11.056,496H232h12.944H496V160H256z M288,176h48v96h-48V176z M39.128,480c9.288-10.096,22.432-16,36.656-16H120V271.816l-6.944-0.928 C57.728,263.488,16,215.824,16,160V16h99.056l-29.52,59.048l40,24L62.872,224.36L219.728,88.424l-32.904-32.912L219.744,16H240 v144c0,55.824-41.728,103.488-97.056,110.88L136,271.816V464h44.224c14.224,0,27.368,5.904,36.656,16H39.128z M480,480H236.552 c-11.88-19.8-32.944-32-56.328-32H152V285.616c54.488-10.36,95.96-55.208,102.872-109.616H272v112h80V176h128V480z"></path> <rect x="448" y="448" width="16" height="16"></rect> <rect x="416" y="448" width="16" height="16"></rect> <rect x="384" y="448" width="16" height="16"></rect> <rect x="448" y="416" width="16" height="16"></rect> <rect x="448" y="384" width="16" height="16"></rect> <rect x="416" y="416" width="16" height="16"></rect> </g> </g> </g> </g></svg>

                                        <span class="custom-option-title"> Rusak Ringan </span>
                                        <small> Terdapat kerusakan minor pada barang </small>
                                    </span>
                                    <input name="kondisi" class="form-check-input" type="radio" value="rr" id="ringan" required>
                                </label>
                            </div>
                        </div>
                        <div class="col-md mb-md-0 mb-3">
                            <div class="form-check custom-option custom-option-icon">
                                <label class="form-check-label custom-option-content" for="berat">
                                    <span class="custom-option-body">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.1" d="M10.2501 5.147L3.64909 17.0287C2.9085 18.3618 3.87244 20 5.39741 20H18.5994C20.1243 20 21.0883 18.3618 20.3477 17.0287L13.7467 5.147C12.9847 3.77538 11.0121 3.77538 10.2501 5.147Z" fill="#b41313"></path> <path d="M12 10V13" stroke="#b41313" stroke-width="2.112" stroke-linecap="round"></path> <path d="M12 16V15.9888" stroke="#b41313" stroke-width="2.112" stroke-linecap="round"></path> <path d="M10.2515 5.147L3.65056 17.0287C2.90997 18.3618 3.8739 20 5.39887 20H18.6008C20.1258 20 21.0897 18.3618 20.3491 17.0287L13.7482 5.147C12.9861 3.77538 11.0135 3.77538 10.2515 5.147Z" stroke="#b41313" stroke-width="2.112" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                        <span class="custom-option-title"> Rusak Berat </span>
                                        <small> Barang tidak bisa digunakan </small>
                                    </span>
                                    <input name="kondisi" class="form-check-input" type="radio" value="rb" id="berat" required>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control"></textarea>
                    </div>

                    <div class="col-12 text-center">
                          <button type="submit" class="btn btn-success me-sm-3 me-1"><i class="ti ti-device-floppy"></i>&nbsp; Simpan</button>
                          <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-circle-x"></i>&nbsp; Batalkan</button>
                    </div>
                </form>
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

<script src="{{ asset('') }}assets/js/data-aset-validation.js"></script>

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
                    url: '{{ route("aset.ss") }}',
                },
                columns: [
                    { data: null },
                    { data: 'nama' },
                    { data: 'merek' },
                    { data: 'tahun' },
                    { data: 'ruang' },
                    { data: 'kondisi' },
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