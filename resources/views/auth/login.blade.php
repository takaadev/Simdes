<x-layouts.guest-layout>
    <div class="login-card">
        {{-- Mobile & Tablet Header Identity --}}
        <div class="login-mobile-brand">
            <img src="{{ asset('logo.png') }}" alt="Lambang Desa Tiaro" width="46" height="46">
            <div>
                <div class="fw-bolder" style="color:var(--simdes-deep-green); font-size:1.02rem; line-height:1.2;">
                    SIMDES DESA TIARO
                </div>
                <div style="font-size:0.76rem; color:var(--simdes-secondary); font-weight:600;">
                    Kantor Pemerintah Desa Tiaro
                </div>
            </div>
        </div>

        <div class="login-card-eyebrow">Otentikasi Aparatur Desa</div>
        <h2 id="loginCardHeading" class="login-card-title">Selamat Datang Kembali</h2>
        <p class="login-card-subtitle">
            Silakan masuk menggunakan akun aparatur desa Anda untuk mengakses ruang kerja administrasi.
        </p>

        <form id="simdesLoginForm" action="{{ route('login.auth') }}" method="POST" novalidate>
            @csrf

            {{-- Username / NIAP / Email Input --}}
            <div class="simdes-field">
                <label for="username" class="simdes-label">NIAP / Alamat Surel</label>
                <span id="usernameHint" class="simdes-field-hint">
                    Masukkan NIAP aparatur desa atau alamat surel terdaftar
                </span>
                <div class="simdes-input-wrap">
                    <i class="fa-regular fa-user simdes-input-icon" aria-hidden="true"></i>
                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                        class="simdes-input has-start-icon @error('username') is-invalid @enderror"
                        placeholder="Contoh: 1502010001 atau sekdes@desatiaro.id" autocomplete="username"
                        autocorrect="off" autocapitalize="off" spellcheck="false" enterkeyhint="next" minlength="3"
                        required autofocus aria-describedby="usernameHint usernameError">
                </div>
                <div id="usernameError" class="field-error-msg @error('username') d-flex @enderror" role="status">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    <span class="field-error-text">
                        @error('username')
                            {{ $message }}
                        @else
                            NIAP atau alamat surel wajib diisi (minimal 3 karakter).
                        @enderror
                    </span>
                </div>
            </div>

            {{-- Password Input --}}
            <div class="simdes-field">
                <div class="d-flex align-items-center justify-content-between">
                    <label for="password" class="simdes-label">Kata Sandi</label>
                </div>
                <span id="passwordHint" class="simdes-field-hint">Jika lupa kata sandi, silakan hubungi Admin.</span>
                <div class="simdes-input-wrap">
                    <i class="fa-solid fa-lock simdes-input-icon" aria-hidden="true"></i>
                    <input type="password" id="password" name="password"
                        class="simdes-input has-start-icon has-end-btn @error('password') is-invalid @enderror"
                        placeholder="Masukkan kata sandi" autocomplete="current-password" enterkeyhint="done"
                        minlength="6" required aria-describedby="passwordHint passwordError">
                    <button type="button" class="password-toggle-btn" data-toggle-password="password"
                        aria-pressed="false" aria-label="Tampilkan kata sandi" aria-describedby="passwordToggleWarn">
                        <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                    <span id="passwordToggleWarn" class="visually-hidden-accessible">
                        Peringatan: tindakan ini akan menampilkan kata sandi Anda pada layar.
                    </span>
                </div>
                <div id="passwordError" class="field-error-msg @error('password') d-flex @enderror" role="status">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    <span class="field-error-text">
                        @error('password')
                            {{ $message }}
                        @else
                            Kata sandi wajib diisi (minimal 6 karakter).
                        @enderror
                    </span>
                </div>
            </div>

            {{-- Remember Me Checkbox --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center gap-2">
                    <input type="checkbox" id="rememberMe" name="remember_me" value="1" class="simdes-checkbox"
                        @checked(old('remember_me'))>
                    <label for="rememberMe" class="mb-0" style="font-size:0.85rem; font-weight:500; cursor:pointer;">
                        Ingat saya di perangkat ini
                    </label>
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit" id="btnLoginSubmit" class="btn-simdes-primary w-100">
                <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                <span>Masuk ke SIMDES</span>
            </button>
        </form>

        {{-- Informasi Bantuan Akun --}}
        <div class="login-demo-box">
            <div class="login-demo-text">
                <strong class="d-block text-dark">
                    <i class="fa-solid fa-circle-info me-1 text-primary" aria-hidden="true"></i>
                    Belum Memiliki Akun?
                </strong>
                Silakan hubungi Admin untuk mendapatkan akses akun ke aplikasi.
            </div>
        </div>
    </div>

    @push('footer')
        <script src="{{ asset('assets/js/toast.js') }}"></script>
        <script>
            $(function() {
                const $form = $('#simdesLoginForm');
                const $username = $('#username');
                const $password = $('#password');
                const $remember = $('#rememberMe');
                const $submitBtn = $('#btnLoginSubmit');
                const $usernameError = $('#usernameError');
                const $passwordError = $('#passwordError');

                const defaultBtnHtml = $submitBtn.html();
                let isSubmitting = false;

                function notify(options) {
                    if (typeof window.showToast === 'function') {
                        window.showToast(options);
                    }
                }

                // Tampilkan toast dari flash session / error redirect
                const flashStatus = @json(session('status'));
                const flashError = @json($errors->first('login') ?: session('error'));

                if (flashStatus) {
                    notify({
                        title: 'Informasi',
                        message: flashStatus,
                        variant: 'success',
                    });
                }

                if (flashError) {
                    notify({
                        title: 'Gagal Masuk',
                        message: flashError,
                        variant: 'danger',
                    });
                }

                function setLoading(state, text = 'Memproses masuk...') {
                    isSubmitting = state;
                    $submitBtn.prop('disabled', state);
                    $username.prop('readonly', state);
                    $password.prop('readonly', state);

                    if (state) {
                        $submitBtn.html(
                            `<i class="fa-solid fa-spinner fa-spin me-2" aria-hidden="true"></i><span>${text}</span>`
                        );
                    } else {
                        $submitBtn.html(defaultBtnHtml);
                    }
                }

                function setFieldError($input, $errorBox, message) {
                    if (message) {
                        $input.addClass('is-invalid');
                        $errorBox.find('.field-error-text').text(message);
                        $errorBox.addClass('d-flex').show();
                    } else {
                        $input.removeClass('is-invalid');
                        $errorBox.removeClass('d-flex').hide();
                    }
                }

                $username.on('input', function() {
                    setFieldError($username, $usernameError, '');
                });

                $password.on('input', function() {
                    setFieldError($password, $passwordError, '');
                });

                // Toggle tampil / sembunyi kata sandi
                $('[data-toggle-password]').on('click', function() {
                    const $btn = $(this);
                    const $input = $('#' + $btn.data('toggle-password'));
                    const $icon = $btn.find('i');

                    if (!$input.length) return;

                    const show = $input.attr('type') === 'password';
                    $input.attr('type', show ? 'text' : 'password');
                    $btn.attr('aria-pressed', String(show));
                    $btn.attr('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');

                    $icon.toggleClass('fa-eye', !show).toggleClass('fa-eye-slash', show);
                });

                // Submit form via jQuery AJAX + Proceed Loading + Toast
                $form.on('submit', function(e) {
                    e.preventDefault();

                    if (isSubmitting) return;

                    setFieldError($username, $usernameError, '');
                    setFieldError($password, $passwordError, '');

                    const usernameVal = String($username.val() ?? '').trim();
                    const passwordVal = String($password.val() ?? '');
                    let hasError = false;

                    if (usernameVal.length < 3) {
                        setFieldError($username, $usernameError,
                            'NIAP atau alamat surel wajib diisi (minimal 3 karakter).');
                        hasError = true;
                    }

                    if (passwordVal.length < 6) {
                        setFieldError($password, $passwordError,
                            'Kata sandi wajib diisi (minimal 6 karakter).');
                        hasError = true;
                    }

                    if (hasError) {
                        $form.find('.is-invalid').first().trigger('focus');
                        notify({
                            title: 'Periksa Input',
                            message: 'Lengkapi NIAP/alamat surel dan kata sandi dengan benar.',
                            variant: 'warning',
                        });
                        return;
                    }

                    setLoading(true, 'Memverifikasi akun...');

                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        dataType: 'json',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        data: {
                            _token: $form.find('input[name="_token"]').val(),
                            username: usernameVal,
                            password: passwordVal,
                            remember_me: $remember.is(':checked') ? 1 : 0,
                        },
                        success: function(response) {
                            $submitBtn.html(
                                '<i class="fa-solid fa-circle-check me-2" aria-hidden="true"></i><span>Berhasil! Mengalihkan...</span>'
                            );

                            notify({
                                title: 'Login Berhasil',
                                message: response.message ||
                                    'Mengalihkan ke ruang kerja SIMDES...',
                                variant: 'success',
                                duration: 2500,
                            });

                            setTimeout(() => {
                                window.location.href = response.redirect ||
                                    @json(route('dashboard'));
                            }, 450);
                        },
                        error: function(xhr) {
                            setLoading(false);

                            const res = xhr.responseJSON || {};

                            if (xhr.status === 422 && res.errors) {
                                if (res.errors.username?.[0]) {
                                    setFieldError($username, $usernameError, res.errors.username[
                                    0]);
                                }
                                if (res.errors.password?.[0]) {
                                    setFieldError($password, $passwordError, res.errors.password[
                                    0]);
                                }

                                notify({
                                    title: 'Gagal Masuk',
                                    message: res.errors.login?.[0] || res.message ||
                                        'Periksa kembali data login Anda.',
                                    variant: 'danger',
                                });
                                return;
                            }

                            if (xhr.status === 429) {
                                notify({
                                    title: 'Terlalu Banyak Percobaan',
                                    message: res.message ||
                                        'Terlalu banyak percobaan masuk. Coba lagi beberapa saat.',
                                    variant: 'warning',
                                });
                                return;
                            }

                            if (xhr.status === 419) {
                                notify({
                                    title: 'Sesi Kedaluwarsa',
                                    message: 'Sesi halaman telah kedaluwarsa. Memuat ulang halaman...',
                                    variant: 'warning',
                                });
                                setTimeout(() => window.location.reload(), 1200);
                                return;
                            }

                            notify({
                                title: 'Gagal Masuk',
                                message: res.message ||
                                    'Terjadi kesalahan saat masuk. Silakan coba lagi.',
                                variant: 'danger',
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layouts.guest-layout>
