/* ==========================================================================
   SIMDES DESA TIARO — Form Validation & Password Controls (forms.js)
   Implements accessible show/hide password toggles, deferred validation
   (:user-invalid + WeakMap fallback + ARIA sync), and login form handling.
   ========================================================================== */

(() => {
  'use strict';

  const REMEMBER_USER_KEY = 'simdes_tiaro_remembered_username';

  /**
   * Interaction-aware fallback for browsers without CSS :user-invalid
   * and ARIA state synchronization for screen readers.
   */
  const InteractionValidator = (() => {
    const dirtyState = new WeakMap();

    const syncFieldState = (input, customMessage = '') => {
      if (!input || typeof input.checkValidity !== 'function') return true;

      if (customMessage) {
        input.setCustomValidity(customMessage);
      }

      const valid = input.checkValidity();
      const fieldWrap = input.closest('.simdes-field');
      const errorBox = fieldWrap?.querySelector('.field-error-text');

      if (!CSS.supports('selector(:user-invalid)')) {
        input.classList.toggle('user-invalid-fallback', !valid);
        input.classList.toggle('user-valid-fallback', valid);
      }

      input.classList.toggle('is-invalid-field', !valid);
      fieldWrap?.classList.toggle('has-error', !valid);

      if (!valid) {
        input.setAttribute('aria-invalid', 'true');
        if (errorBox && input.validationMessage) {
          errorBox.textContent = input.validationMessage;
        }
      } else {
        input.setAttribute('aria-invalid', 'false');
      }

      return valid;
    };

    const evaluateInputRules = (input) => {
      input.setCustomValidity('');
      const val = input.value.trim();
      const label = input.dataset.fieldLabel || 'Kolom ini';

      if (input.type === 'radio') {
        if (input.hasAttribute('required') && input.form) {
          const radios = Array.from(input.form.elements[input.name] || []);
          const anyChecked = radios.some((r) => r.checked);
          if (!anyChecked) {
            input.setCustomValidity(`${label} wajib dipilih.`);
          }
        }
        return;
      }

      if (input.hasAttribute('required') && val.length === 0) {
        input.setCustomValidity(`${label} wajib diisi.`);
      } else if (input.validity?.patternMismatch) {
        input.setCustomValidity(input.dataset.patternError || `${label} belum sesuai format yang diminta.`);
      } else if (input.minLength > 0 && val.length < input.minLength) {
        input.setCustomValidity(`${label} minimal ${input.minLength} karakter.`);
      }
    };

    const bindForm = (form) => {
      if (!form) return;

      const inputs = Array.from(form.querySelectorAll('input, select, textarea'));

      inputs.forEach((input) => {
        if (input.type === 'hidden') return;

        // On blur: mark blurred and validate if user interacted or left required empty after focus
        input.addEventListener('blur', () => {
          const state = dirtyState.get(input) || { interacted: false, blurred: false };
          state.blurred = true;
          dirtyState.set(input, state);
          if (state.interacted) {
            evaluateInputRules(input);
            syncFieldState(input);
          }
        });

        // On input/change: clear existing errors immediately without nagging prematurely
        const onValueChange = () => {
          if (input.type === 'radio' && input.name) {
            const groupRadios = inputs.filter((el) => el.type === 'radio' && el.name === input.name);
            groupRadios.forEach((r) => {
              dirtyState.set(r, { interacted: true, blurred: true });
              evaluateInputRules(r);
              syncFieldState(r);
            });
            return;
          }

          const state = dirtyState.get(input) || { interacted: false, blurred: false };
          state.interacted = true;
          dirtyState.set(input, state);

          input.setCustomValidity('');
          if (state.blurred || input.getAttribute('aria-invalid') === 'true') {
            evaluateInputRules(input);
            syncFieldState(input);
          }
        };

        input.addEventListener('input', onValueChange);
        input.addEventListener('change', onValueChange);
      });

      form.addEventListener('reset', () => {
        inputs.forEach((input) => {
          dirtyState.delete(input);
          input.setCustomValidity('');
          input.classList.remove('user-invalid-fallback', 'user-valid-fallback', 'is-invalid-field');
          input.removeAttribute('aria-invalid');
          input.closest('.simdes-field')?.classList.remove('has-error');
        });
      });
    };

    const validateAll = (form) => {
      const inputs = Array.from(form.querySelectorAll('input, select, textarea'));
      let firstInvalid = null;

      inputs.forEach((input) => {
        if (input.type === 'hidden') return;
        if (input.disabled || input.closest('[hidden]')) return;
        if (input.type === 'checkbox' && !input.hasAttribute('required')) return;
        dirtyState.set(input, { interacted: true, blurred: true });
        evaluateInputRules(input);
        const ok = syncFieldState(input);
        if (!ok && !firstInvalid) {
          firstInvalid = input;
        }
      });

      if (firstInvalid) {
        firstInvalid.focus();
        return false;
      }
      return true;
    };

    return { bindForm, validateAll, syncFieldState };
  })();

  function initPasswordToggles(root = document) {
    root.querySelectorAll('[data-toggle-password]').forEach((btn) => {
      const targetId = btn.getAttribute('data-toggle-password');
      const input = targetId ? document.getElementById(targetId) : null;
      const icon = btn.querySelector('i');
      if (!input) return;

      btn.addEventListener('click', () => {
        const showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        btn.setAttribute('aria-pressed', String(!showing));
        btn.setAttribute(
          'aria-label',
          showing ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi'
        );
        if (icon) {
          icon.classList.toggle('fa-eye', showing);
          icon.classList.toggle('fa-eye-slash', !showing);
        }
      });
    });
  }

  function initLoginForm() {
    const loginForm = document.getElementById('simdesLoginForm');
    if (!loginForm) return;

    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const rememberCheck = document.getElementById('rememberMe');
    const submitBtn = document.getElementById('loginSubmitBtn');
    const alertBox = document.getElementById('loginAlertBox');
    const alertText = document.getElementById('loginAlertText');
    const demoFillBtn = document.getElementById('fillDemoAccountBtn');
    const forgotLink = document.getElementById('forgotPasswordLink');

    InteractionValidator.bindForm(loginForm);

    // Restore remembered username if saved
    try {
      const remembered = localStorage.getItem(REMEMBER_USER_KEY);
      if (remembered && usernameInput) {
        usernameInput.value = remembered;
        if (rememberCheck) rememberCheck.checked = true;
      }
    } catch (_) {
      // Ignore storage access errors
    }

    const showBanner = (message, type = 'error') => {
      if (!alertBox || !alertText) return;
      alertBox.className = `login-alert show ${
        type === 'success' ? 'login-alert-success' : 'login-alert-error'
      }`;
      const icon = alertBox.querySelector('i');
      if (icon) {
        icon.className =
          type === 'success'
            ? 'fa-solid fa-circle-check mt-1'
            : 'fa-solid fa-circle-exclamation mt-1';
      }
      alertText.textContent = message;
    };

    const hideBanner = () => {
      alertBox?.classList.remove('show');
    };

    demoFillBtn?.addEventListener('click', () => {
      if (!usernameInput || !passwordInput) return;
      usernameInput.value = 'sekdes.tiaro';
      passwordInput.value = 'tiaro2026';
      usernameInput.dispatchEvent(new Event('input', { bubbles: true }));
      passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
      hideBanner();
      submitBtn?.focus();
    });

    forgotLink?.addEventListener('click', (e) => {
      e.preventDefault();
      const modalEl = document.getElementById('forgotPasswordModal');
      if (modalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modalEl).show();
      }
    });

    loginForm.addEventListener('submit', (e) => {
      e.preventDefault();
      hideBanner();

      const isValid = InteractionValidator.validateAll(loginForm);
      if (!isValid) {
        showBanner('Mohon periksa kembali kolom nama pengguna dan kata sandi Anda.', 'error');
        return;
      }

      const username = usernameInput.value.trim();
      const remember = Boolean(rememberCheck?.checked);

      try {
        if (remember) {
          localStorage.setItem(REMEMBER_USER_KEY, username);
        } else {
          localStorage.removeItem(REMEMBER_USER_KEY);
        }
      } catch (_) {
        // Ignore storage error
      }

      // Prevent duplicate submissions & provide visual progress
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          <span>Memverifikasi Akses...</span>
        `;
      }

      window.SimdesApp?.setUserSession(username, remember);
      showBanner('Akses terverifikasi. Mengalihkan ke Dashboard SIMDES Desa Tiaro...', 'success');

      window.setTimeout(() => {
        window.location.href = 'pages/dashboard.html';
      }, 480);
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    initPasswordToggles(document);
    initLoginForm();
  });

  window.SimdesForms = {
    initPasswordToggles,
    InteractionValidator
  };
})();

