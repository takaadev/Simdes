<x-action-create href="{{ route('agendas.store') }}" size="lg" modalTitle="Tambah Surat Keluar" icon="share">

    {{-- A. Informasi Surat Keluar --}}
    <x-fieldset label="A. Informasi Surat Keluar">
        <div class="row g-3">

            {{-- Kategori Surat --}}
            <div class="col-12">
                <label class="form-label">
                    Kategori Surat <span class="text-danger">*</span>
                </label>

                <select name="kategori_agenda" id="kategori_agenda" class="form-select" required>
                    <option value="">Pilih Kategori Surat</option>

                    @foreach (\App\Enums\AgendaCategoryList::groupedOptions() as $group => $options)
                        <optgroup label="{{ $group }}">
                            @foreach ($options as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            {{-- Nomor Surat --}}
            <div class="col-12">
                <label class="form-label">Nomor Surat</label>

                <div class="input-group">
                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control"
                        placeholder="Nomor surat belum dibuat" readonly required>

                    <button type="button" id="btnGenerateNumber" class="btn btn-simdes-outline">
                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i>
                        Generate
                    </button>
                </div>

                <small class="text-muted">
                    Pilih kategori surat kemudian tekan Generate.
                </small>
            </div>

            {{-- Tanggal Surat --}}
            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_surat" label="Tanggal Surat" required />
            </div>

            {{-- Tanggal Kirim --}}
            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tanggal_kirim" label="Tanggal Kirim" :value="now()->format('Y-m-d')" required />
            </div>

            {{-- Tujuan --}}
            <div class="col-12">
                <x-input-text name="tujuan" label="Tujuan / Penerima Surat"
                    placeholder="Nama instansi atau pihak penerima" required />
            </div>

            {{-- Perihal --}}
            <div class="col-12">
                <x-input-textarea name="isi_surat" label="Perihal Surat"
                    placeholder="Tuliskan perihal atau ringkasan isi surat..." :rows="3" required />
            </div>
        </div>
    </x-fieldset>
    @push('footer')
    <script>
        $(document).ready(function() {

            // =====================================================
            // GENERATE NOMOR SURAT
            // =====================================================
            $('#btnGenerateNumber').on('click', function() {

                const button = $(this);
                const category = $('#kategori_agenda').val();
                const date = $('[name="tgl_surat"]').val();

                if (!category) {
                    console.warn('Silakan pilih kategori surat terlebih dahulu.');
                    return;
                }

                if (!date) {
                    console.warn('Silakan tentukan tanggal surat terlebih dahulu.');
                    return;
                }

                button.prop('disabled', true);

                const originalHtml = button.html();

                button.html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Generate...'
                );

                $.ajax({
                    url: "{{ route('agendas.generate-number') }}",
                    method: 'POST',
                    data: {
                        kategori_agenda: category,
                        tgl_surat: date,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function(response) {

                        console.log('Generate Nomor Surat:', response);

                        if (response.success) {

                            $('#nomor_surat')
                                .val(response.nomor_surat)
                                .trigger('change');

                            console.log('Nomor Surat Berhasil Dibuat:', response.nomor_surat);

                        } else {
                            console.warn('Nomor surat gagal dibuat:', response);
                        }
                    },

                    error: function(xhr) {

                        console.error('Generate Nomor Surat Error:', {
                            status: xhr.status,
                            response: xhr.responseJSON,
                            errors: xhr.responseJSON?.errors
                        });

                    },

                    complete: function() {
                        button.prop('disabled', false);
                        button.html(originalHtml);
                    }
                });

            });

            // =====================================================
            // RESET NOMOR JIKA KATEGORI / TANGGAL BERUBAH
            // =====================================================
            $('#kategori_agenda, [name="tgl_surat"]').on('change', function() {

                $('#nomor_surat').val('');

                console.log('Nomor surat direset karena kategori atau tanggal berubah.');

            });

        });
    </script>
@endpush
</x-action-create>
