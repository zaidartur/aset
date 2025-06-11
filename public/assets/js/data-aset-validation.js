'use strict';
document.addEventListener('DOMContentLoaded', function () {
    (function () {
        const formValidationInput = document.getElementById('form_add'),
            formValidationSubdata = jQuery(formValidationInput.querySelector('[name="subdata"]'))

        let addNew = document.getElementById('add_new');
        addNew.addEventListener('show.bs.modal', function (event) {
            // Init custom option check
            window.Helpers.initCustomOptionCheck();
        });
        const fv = FormValidation.formValidation(formValidationInput, {
            fields: {
                nama: {
                    validators: {
                        notEmpty: {
                            message: 'Nama barang wajib di isi'
                        }
                    }
                },
                merek: {
                    validators: {
                        notEmpty: {
                            message: 'Merek barang wajib di isi'
                        }
                    }
                },
                harga: {
                    validators: {
                        notEmpty: {
                            message: 'Harga barang wajib di isi'
                        }
                    }
                },
                tahun: {
                    validators: {
                        notEmpty: {
                            message: 'Tahun pembelian wajib di isi'
                        }
                    }
                },
                kondisi: {
                    validators: {
                        notEmpty: {
                            message: 'Mohon untuk memilih kondisi barang'
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.mb-3'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),

                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            },
            init: instance => {
                instance.on('plugins.message.placed', function (e) {
                    if (e.element.parentElement.classList.contains('input-group')) {
                        e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                    }
                    if (e.element.parentElement.parentElement.classList.contains('custom-option')) {
                        e.element.closest('.row').insertAdjacentElement('afterend', e.messageElement);
                    }
                });
            }
        });

        if (formValidationSubdata.length) {
            formValidationSubdata.wrap('<div class="position-relative"></div>');
            formValidationSubdata.select2({
                placeholder: 'Pilih Kategori',
                dropdownParent: formValidationSubdata.parent()
            }).on('change.select2', function () {
                // Revalidate the color field when an option is chosen
                fv.revalidateField('subdata');
            });
        }
    })();
});