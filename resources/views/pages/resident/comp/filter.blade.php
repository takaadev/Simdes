<x-filters :url="route('residents.index')" table="#residentTable">
    <x-slot:filter>
        {{-- Jenis Kelamin --}}
        <x-filter-item label="Urutkan Nama" id="filterSortName" name="sort_name" :options="[
            'asc' => 'A - Z',
            'desc' => 'Z - A',
        ]"
            placeholder="Urutan Nama" />
        <x-filter-item label="Jenis Kelamin" id="filterGenderSelect" name="jenis_kelamin" :options="\App\Enums\GenderList::options()" />

        {{-- Kewarganegaraan --}}
        <x-filter-item label="Kewarganegaraan" id="filterCitizenshipSelect" name="kewarganegaraan" :options="\App\Enums\CitizenshipList::options()" />

        {{-- Status Perkawinan --}}
        <x-filter-item label="Status Perkawinan" id="filterMaritalStatusSelect" name="status_perkawinan"
            :options="\App\Enums\MaritalStatusList::options()" />

        {{-- Pendidikan --}}
        <x-filter-item label="Pendidikan" id="filterEducationSelect" name="pendidikan" :options="\App\Enums\EducationList::options()" />

        {{-- Agama --}}
        <x-filter-item label="Agama" id="filterReligionSelect" name="agama" :options="\App\Enums\ReligionList::options()" />

        {{-- Pekerjaan --}}
        <x-filter-item label="Pekerjaan" id="filterOccupationSelect" name="pekerjaan" :options="\App\Enums\OccupationList::options()" />

        {{-- Status dalam Keluarga --}}
        {{-- <x-filter-item
            label="Status dalam Keluarga"
            id="filterFamilyStatusSelect"
            name="status_dalam_keluarga"
            :options="\App\Enums\FamilyStatusList::options()"
        /> --}}

        {{-- Tahun Kelahiran --}}
        <x-filter-item label="Tahun Kelahiran" id="filterYearSelect" name="year" :options="\App\Enums\ListYear::options()" />

        {{-- Status Data --}}
        <x-filter-item label="Status Data" id="filterDeletedSelect" name="deleted" :options="[
            'active' => 'Aktif',
            'trashed' => 'Dihapus',
        ]" />
    </x-slot:filter>

    <x-slot:actions>
        <button type="button" id="btnCetakPenduduk" class="btn-simdes-outline">
            <i class="fa-solid fa-print" aria-hidden="true"></i>
            <span>Cetak</span>
        </button>

        <button type="button" id="btnImportExcelPenduduk" class="btn-simdes-excel">
                <i class="fa-solid fa-file-excel" aria-hidden="true"></i>
                <span>Import Excel</span>
            </button>
            <div class="modal fade simdes-modal" id="indukImportExcelModal" tabindex="-1" aria-labelledby="indukImportExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="indukImportExcelModalLabel">
            <i class="fa-solid fa-file-excel text-simdes-primary me-2" aria-hidden="true"></i>Import Data Buku Induk Penduduk (Excel)
          </h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup modal import Excel"></button>
        </div>

        <form id="importExcelForm" action="#" method="POST" novalidate>
          <div class="modal-body p-4">
            <div id="importExcelAlert" class="login-alert login-alert-error" role="alert" aria-live="assertive">
              <i class="fa-solid fa-circle-exclamation mt-1" aria-hidden="true"></i>
              <span id="importExcelAlertText"></span>
            </div>

            <!-- Template Download Card -->
            <div class="import-template-card">
              <div style="flex:1 1 280px;">
                <div class="fw-bold" style="font-size:0.88rem; color:var(--simdes-deep-green);">
                  <i class="fa-solid fa-table-list me-1 text-simdes-primary" aria-hidden="true"></i>Format Kolom Buku Induk Penduduk (Kode B.1)
                </div>
                <div class="text-simdes-muted mt-1" style="font-size:0.78rem; line-height:1.5;">
                  Gunakan berkas template Excel resmi berisi susunan kolom Buku Induk Penduduk beserta contoh data fiktif. Nomor urut pada tabel tetap dibuat otomatis oleh sistem.
                </div>
              </div>
              <button type="button" id="btnDownloadTemplateExcel" class="btn-simdes-outline bg-white">
                <i class="fa-solid fa-download text-simdes-primary" aria-hidden="true"></i>
                <span>Unduh Template (.xlsx)</span>
              </button>
            </div>

            <!-- File Upload Dropzone -->
            <fieldset class="simdes-fieldset">
              <legend class="simdes-legend">1. Pilih Berkas Excel</legend>
              <div class="simdes-excel-dropzone" id="importExcelDropzone">
                <label for="importExcelFileInput" class="simdes-label mb-1">
                  Unggah Berkas Spreadsheet Penduduk<span class="req-star" aria-hidden="true">*</span>
                </label>
                <span id="importFileHint" class="simdes-field-hint mb-2">
                  Mendukung format Microsoft Excel (<strong>.xlsx</strong>, <strong>.xls</strong>) maupun <strong>.csv</strong>. Anda juga dapat menyeret berkas ke kotak ini.
                </span>
                <input
                  type="file"
                  id="importExcelFileInput"
                  name="excelFile"
                  class="simdes-file-input"
                  accept=".xlsx,.xls,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/csv"
                  aria-describedby="importFileHint"
                >
              </div>
            </fieldset>

            <!-- Import Mode -->
            <fieldset class="simdes-fieldset">
              <legend class="simdes-legend">2. Mode Penyimpanan Import</legend>
              <div class="simdes-field mb-1" role="radiogroup" aria-labelledby="importModeGroupLabel">
                <span id="importModeGroupLabel" class="simdes-field-hint mb-2">
                  Tentukan cara penggabungan baris Excel dengan data Buku Induk Penduduk yang sudah ada:
                </span>
                <div class="simdes-radio-group">
                  <div class="simdes-radio-option">
                    <input
                      type="radio"
                      id="importModeAppend"
                      name="importMode"
                      value="append"
                      class="simdes-radio-input"
                      checked
                    >
                    <label for="importModeAppend" class="simdes-radio-pill">
                      <i class="fa-solid fa-layer-group" aria-hidden="true"></i>
                      <span>Tambahkan ke Data Ada</span>
                    </label>
                  </div>
                  <div class="simdes-radio-option">
                    <input
                      type="radio"
                      id="importModeReplace"
                      name="importMode"
                      value="replace"
                      class="simdes-radio-input"
                    >
                    <label for="importModeReplace" class="simdes-radio-pill">
                      <i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i>
                      <span>Ganti Seluruh Data</span>
                    </label>
                  </div>
                </div>
              </div>
            </fieldset>

            <!-- Live Parsed Preview -->
            <div id="importPreviewSection" class="detail-sheet-box mt-3" hidden>
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                <div class="detail-item-label mb-0">
                  <i class="fa-solid fa-magnifying-glass-chart me-1 text-simdes-primary" aria-hidden="true"></i>Pratinjau Hasil Pembacaan Berkas
                </div>
                <div class="d-flex flex-wrap align-items-center gap-1">
                  <span class="badge-simdes badge-simdes-neutral" id="importFileBadge">-</span>
                  <span class="badge-simdes badge-simdes-success" id="importValidBadge">0 siap diimport</span>
                  <span class="badge-simdes badge-simdes-warning" id="importSkipBadge" hidden>0 dilewati</span>
                </div>
              </div>

              <div class="import-preview-table-wrap" tabindex="0" role="region" aria-label="Pratinjau baris import Excel">
                <table class="import-preview-table">
                  <thead>
                    <tr>
                      <th scope="col">Baris</th>
                      <th scope="col">Nama Lengkap/Panggilan</th>
                      <th scope="col">L/P</th>
                      <th scope="col">Tempat, Tgl Lahir</th>
                      <th scope="col">NIK</th>
                      <th scope="col">Nomor KK</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>
                  <tbody id="importPreviewTableBody"></tbody>
                </table>
              </div>
              <small id="importPreviewNote" class="text-simdes-muted d-block mt-2" style="font-size:0.75rem;"></small>
            </div>
          </div>

          <div class="modal-footer bg-light border-top px-4 py-3" style="border-color:var(--simdes-border) !important;">
            <button type="button" class="btn-simdes-outline" data-bs-dismiss="modal">Batal</button>
            <button type="submit" id="confirmImportExcelBtn" class="btn-simdes-primary">
              <i class="fa-solid fa-file-import" aria-hidden="true"></i>
              <span id="confirmImportExcelBtnText">Import Data ke Buku Induk</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

        @include('pages.resident.create')
    </x-slot:actions>

</x-filters>
