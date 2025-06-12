'use strict';

// Select2 (jquery)
$(function () {
    const select2 = $('.select2');

    // Select2 Country
    if (select2.length) {
        select2.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Pilih data',
                dropdownParent: $this.parent()
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
        const formValidationAdd = document.getElementById('addForm')
        // const selectParam = jQuery(formValidationAdd.querySelector('[name="parameter"]'))

        // Edit user form validation
        FormValidation.formValidation(formValidationAdd, {
            fields: {
                parameter: {
                    validators: {
                        notEmpty: {
                            message: 'Kategori wajib dipilih'
                        },
                    }
                },
                nama: {
                    validators: {
                        notEmpty: {
                            message: 'Nama barang wajib diisi'
                        },
                    }
                },
                urutan: {
                    validators: {
                        notEmpty: {
                            message: 'Nomor urut tidak boleh kosong'
                        },
                    }
                },
                merek: {
                    validators: {
                        notEmpty: {
                            message: 'Merek barang wajib diisi'
                        },
                    }
                },
                harga: {
                    validators: {
                        notEmpty: {
                            message: 'Harga pembelian wajib diisi'
                        },
                        // regexp: {
                        //     regexp: /^[0-9]*$/,
                        //     message: 'Harga pembelian harus berupa angka'
                        // }
                    }
                },
                tahun: {
                    validators: {
                        notEmpty: {
                            message: 'Tahun pembelian wajib diisi'
                        },
                        regexp: {
                            regexp: /^[0-9]*$/,
                            message: 'Tahun pembelian harus berupa angka'
                        }
                    }
                },
                ruang: {
                    validators: {
                        notEmpty: {
                            message: 'Lokasi/ruang wajib diisi'
                        },
                    }
                },
                bahan: {
                    validators: {
                        notEmpty: {
                            message: 'Bahan wajib diisi'
                        },
                    }
                },
                kondisi: {
                    validators: {
                        notEmpty: {
                            message: 'Kondisi barang wajib dipilih'
                        },
                    }
                },
                uraian: {
                    validators: {
                        notEmpty: {
                            message: 'Uraian wajib dipilih'
                        },
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    // Use this for enabling/changing valid/invalid class
                    // eleInvalidClass: '',
                    eleValidClass: '',
                    rowSelector: '.col-12'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                // Submit the form when all fields are valid
                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            },
            init: instance => {
                instance.on('plugins.message.placed', function (e) {
                //* Move the error message out of the `input-group` element
                    if (e.element.parentElement.classList.contains('input-group')) {
                        // `e.field`: The field name
                        // `e.messageElement`: The message element
                        // `e.element`: The field element
                        e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                    }
                    //* Move the error message out of the `row` element for custom-options
                    if (e.element.parentElement.parentElement.classList.contains('custom-option')) {
                        e.element.closest('.row').insertAdjacentElement('afterend', e.messageElement);
                    }
                });
            }
        });
    })();
});