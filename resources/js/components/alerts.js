import Swal from 'sweetalert2';

window.Swal = Swal;

// Notifikasi berhasil
export function showSuccess(message = 'Data berhasil disimpan.') {
    return Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        confirmButtonText: 'OK'
    });
}

// Notifikasi gagal
export function showError(message = 'Terjadi kesalahan.') {
    return Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: message,
        confirmButtonText: 'OK'
    });
}

// Notifikasi informasi
export function showInfo(message) {
    return Swal.fire({
        icon: 'info',
        title: 'Informasi',
        text: message,
        confirmButtonText: 'OK'
    });
}

// Konfirmasi hapus
export function confirmDelete(message = 'Data yang dihapus tidak dapat dikembalikan.') {
    return Swal.fire({
        icon: 'warning',
        title: 'Hapus Data?',
        text: message,
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    });
}