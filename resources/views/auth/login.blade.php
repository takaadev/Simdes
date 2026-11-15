<x-layouts.guest-layout>
    <div class="login-card">
        <!-- Mobile & Tablet Header Identity -->
        <div class="login-mobile-brand">
            <img src="assets/images/logo.png" alt="Lambang Desa Tiaro" width="46" height="46">
            <div>
                <div class="fw-bolder" style="color:var(--simdes-deep-green); font-size:1.02rem; line-height:1.2;">SIMDES
                    DESA
                    TIARO</div>
                <div style="font-size:0.76rem; color:var(--simdes-secondary); font-weight:600;">Kantor
                    Pemerintah Desa Tiaro</div>
            </div>
        </div>

        <div class="login-card-eyebrow">Otentikasi Aparatur Desa</div>
        <h2 id="loginCardHeading" class="login-card-title">Selamat Datang Kembali</h2>
        <p class="login-card-subtitle">
            Silakan masuk menggunakan akun aparatur desa Anda untuk mengakses ruang kerja administrasi.
        </p>

        <!-- Dynamic Feedback Banner -->
        <div id="loginAlertBox" class="login-alert" role="alert" aria-live="assertive">
            <i class="fa-solid fa-circle-exclamation mt-1" aria-hidden="true"></i>
            <span id="loginAlertText"></span>
        </div>

        <form id="simdesLoginForm" action="" method="post" novalidate>
            <!-- Username Input -->
            <div class="simdes-field">
                <label for="username" class="simdes-label">Nama Pengguna (Username)</label>
                <span id="usernameHint" class="simdes-field-hint">Contoh: sekdes.tiaro atau kaur.umum</span>
                <div class="simdes-input-wrap">
                    <i class="fa-regular fa-user simdes-input-icon" aria-hidden="true"></i>
                    <input type="text" id="username" name="username" class="simdes-input has-start-icon"
                        placeholder="Masukkan username aparatur" autocomplete="username" autocorrect="off"
                        autocapitalize="off" spellcheck="false" enterkeyhint="next" minlength="3" required
                        data-field-label="Nama pengguna" aria-describedby="usernameHint usernameError">
                </div>
                <div id="usernameError" class="field-error-msg" role="status">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    <span class="field-error-text">Nama pengguna wajib diisi (minimal 3 karakter).</span>
                </div>
            </div>

            <!-- Password Input -->
            <div class="simdes-field">
                <div class="d-flex align-items-center justify-content-between">
                    <label for="password" class="simdes-label">Kata Sandi</label>
                    <a href="#forgotPasswordModal" id="forgotPasswordLink" class="fw-semibold"
                        style="font-size:0.78rem; margin-block-end:0.38rem;">
                        Lupa kata sandi?
                    </a>
                </div>
                <span id="passwordHint" class="simdes-field-hint">Minimal 6 karakter kombinasi huruf atau
                    angka</span>
                <div class="simdes-input-wrap">
                    <i class="fa-solid fa-lock simdes-input-icon" aria-hidden="true"></i>
                    <input type="password" id="password" name="password"
                        class="simdes-input has-start-icon has-end-btn" placeholder="Masukkan kata sandi"
                        autocomplete="current-password" enterkeyhint="done" minlength="6" required
                        data-field-label="Kata sandi" aria-describedby="passwordHint passwordError">
                    <button type="button" class="password-toggle-btn" data-toggle-password="password"
                        aria-pressed="false" aria-label="Tampilkan kata sandi" aria-describedby="passwordToggleWarn">
                        <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                    <span id="passwordToggleWarn" class="visually-hidden-accessible">
                        Peringatan: tindakan ini akan menampilkan kata sandi Anda pada layar.
                    </span>
                </div>
                <div id="passwordError" class="field-error-msg" role="status">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    <span class="field-error-text">Kata sandi wajib diisi (minimal 6 karakter).</span>
                </div>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center gap-2">
                    <input type="checkbox" id="rememberMe" name="remember_me" class="simdes-checkbox">
                    <label for="rememberMe" class="mb-0" style="font-size:0.85rem; font-weight:500; cursor:pointer;">
                        Ingat saya di perangkat ini
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <a href="{{ route('dashboard') }}" class="btn-simdes-primary w-100"
                style="min-block-size:48px; font-size:0.95rem;">
                <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                <span>Masuk ke SIMDES</span>
            </a>
        </form>

        <!-- Frontend Demo Helper -->
        <div class="login-demo-box">
            <div class="login-demo-text">
                <strong class="d-block text-dark">Mode Pratinjau Antarmuka (Dummy)</strong>
                Akun uji: <code>sekdes.tiaro</code> &bull; Sandi: <code>tiaro2026</code>
            </div>
            <button type="button" id="fillDemoAccountBtn" class="login-demo-fill-btn">
                <i class="fa-solid fa-wand-magic-sparkles me-1" aria-hidden="true"></i>Isi Otomatis
            </button>
        </div>
    </div>
</x-layouts.guest-layout>
