<?php
session_start();
require 'config.php';
$db = new Database();

// Jika sudah login, langsung arahkan ke dashboard sesuai role
if (isset($_SESSION['user_id'], $_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
        exit;
    }
    if ($_SESSION['role'] === 'user') {
        header("Location: user/dashboard.php");
        exit;
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Username dan password wajib diisi!";
    } else {
        $user = $db->get('users', ['username' => $username]);

        if ($user && password_verify($password, $user['password'])) {
            $role = strtolower(trim($user['role'] ?? ''));

            if ($role === 'admin' || $role === 'user') {
                session_regenerate_id(true);
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $role;

                if ($role === 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: user/dashboard.php");
                }
                exit;
            }

            $error = "Role akun tidak valid!";
        } else {
            $error = "Username atau password salah!";
        }
    }
}
?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Web Kebudayaan | Login Page</title>
    <link rel="stylesheet" href="./assets/css/adminlte.css" />
    <style>
  /* Background isi card login */
  .login-card-body {
    background-color: #FFFDF7 !important;
    color: #5C4033 !important;
  }

  /* Tulisan selamat datang */
  .login-box-msg {
    color: #5C4033 !important;
  }

  /* Input username dan password */
  .form-control {
    background-color: #FFFDF7 !important;
    border-color: #D8C7AE !important;
    color: #5C4033 !important;
  }

  .form-control:focus {
    border-color: #A67C52 !important;
    box-shadow: 0 0 0 0.2rem rgba(166, 124, 82, 0.2);
  }

  /* Ikon username dan password */
  .input-group-text {
    background-color: #E8DCC8 !important;
    border-color: #D8C7AE !important;
    color: #5C4033 !important;
  }

  /* Tulisan link */
  .login-card-body a {
    color: #A67C52;
  }

  .login-card-body a:hover {
    color: #5C4033;
  }

  /* Checkbox */
  .form-check-input:checked {
    background-color: #5C4033;
    border-color: #5C4033;
  }


/* Checkbox Remember Me */
.form-check-input {
    accent-color: #5C4033;
    border-color: #A67C52 !important;
}

.form-check-input:checked {
    background-color: #5C4033 !important;
    border-color: #5C4033 !important;
}

/* Tulisan Username dan Password */
.form-floating > label {
    color: #6B4A3B !important;
    opacity: 1 !important;
}

.form-control::placeholder {
    color: #8B735E !important;
    opacity: 1;
}

/* Tombol Login Email */
.btn-login-email {
    background-color: #A67C52;
    color: #FFFDF7 !important;
    border: none;
}

.btn-login-email:hover {
    background-color: #8B6341;
    color: #FFFFFF !important;
}

/* Tombol Login Google */
.btn-login-google {
    background-color: #5C4033;
    color: #FFFDF7 !important;
    border: none;
}

.btn-login-google:hover {
    background-color: #432D24;
    color: #FFFFFF !important;
}

/* Ikon tombol */
.social-auth-links .bi {
    color: #D4AF37;
}

</style>
    <!--begin::Theme Init (prevents flash of incorrect theme on load, #6043)-->
    <script>
      (() => {
        'use strict';
        const root = document.documentElement;

        // Applications with their own theming opt out of AdminLTE's color mode
        // entirely, here as well as in the bundle.
        if (root.getAttribute('data-lte-color-mode') === 'off') {
          return;
        }

        const STORAGE_KEY = 'lte-theme';
        let stored = null;
        try {
          stored = localStorage.getItem(STORAGE_KEY);
        } catch {
          // localStorage may be unavailable (private mode, sandboxed iframe).
        }
        // Mirror the precedence in color-mode.ts: the visitor's stored choice
        // wins, then a theme this page declared itself, then the OS preference.
        const authored = root.getAttribute('data-bs-theme');
        let resolved = 'light';
        if (stored === 'dark' || stored === 'light') {
          resolved = stored;
        } else if (authored === 'dark' || authored === 'light') {
          resolved = authored;
        } else if (globalThis.matchMedia('(prefers-color-scheme: dark)').matches) {
          resolved = 'dark';
        }
        root.setAttribute('data-bs-theme', resolved);
        root.style.colorScheme = resolved;
        // Flag values computed here, so the bundle does not mistake them for a
        // theme the page declared and stop following the OS preference.
        if (resolved !== authored) {
          root.setAttribute('data-lte-theme-resolved', '');
        }
      })();
    </script>
    <!--end::Theme Init-->

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE 4 | Login Page v2" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a free Bootstrap 5 admin dashboard template with almost 50 example pages, built with vanilla JS and designed with accessibility in mind."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel"
    />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="./assets/css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.3.0/index.css"
      integrity="sha256-5UwXtC6TaPHCAbVQ59waEAqOssHyhn/twyuLRRWszzo="
      crossorigin="anonymous"
      media="print"
      onload="this.media = 'all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="./assets/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="login-page"
        style="background-color: #E8DCC8;">
    <main class="login-box">
      <div class="card shadow-lg border-0"
            style="background-color: #FFFDF7; border-radius: 12px;">
        <div class="card-header text-center border-0"
              style="background-color: #5C4033; border-radius: 12px 12px 0 0;">

          <h1 class="mb-0">
            <span style="color: #F5E6C8; font-weight: bold;">
              Login
            </span>
            <span style="color: #D4AF37;">
              Kebudayaan
            </span>
          </h1>

          <small style="color: #E8DCC8;">
            WARISAN KEBUDAYAAN PURBALINGGA
          </small>
        </div>
        <div class="card-body login-card-body">
          <p class="login-box-msg" style="color:#5C4033;">
            Selamat Datang
            <br>
            <small style="color: #8B735E;">
              Silahkan Masuk Ke Akun Anda
            </small>
          </p>

          <form action="../index3.html" method="post">
            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="loginUsername" type="text" name="username" class="form-control" value="" placeholder="" required />
                <label for="loginUsername">Username</label>
              </div>
              <div class="input-group-text">
                <span class="bi bi-person-fill"></span>
              </div>
            </div>
            <div class="input-group mb-1">
              <div class="form-floating">
                <input id="loginPassword" type="password" class="form-control" name="password" placeholder="" required />
                <label for="loginPassword">Password</label>
              </div>
              <div class="input-group-text">
                <span class="bi bi-lock-fill"></span>
              </div>
            </div>
            <!--begin::Row-->
            <div class="row">
              <div class="col-8 d-inline-flex align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault"> Remember Me </label>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <div class="d-grid gap-2">
                  <button type="submit" class="btn w-100" style="background-color: #5C4033; color:#FFFDF7; border: none;">Masuk</button>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!--end::Row-->
          </form>

<div class="social-auth-links text-center mb-3 d-grid gap-2">

    <p style="color: #8B735E;">- ATAU -</p>

    <!-- Login Email -->
    <a href="#" class="btn"
       style="background-color: #A67C52;
              color: #FFFDF7;
              border: none;">
        <i class="bi bi-envelope-fill me-2"
           style="color: #D4AF37;"></i>
        Login menggunakan Email
    </a>

    <!-- Login Google -->
    <a href="#" class="btn"
       style="background-color: #5C4033;
              color: #FFFDF7;
              border: none;">
        <i class="bi bi-google me-2"
           style="color: #D4AF37;"></i>
        Login menggunakan Google
    </a>
</div>

          <!-- /.social-auth-links -->
          <p class="mb-1">
            <a href="forgot-password.html">I forgot my password</a>
          </p>
          <p class="mb-0">
            <a href="register.html" class="text-center"> Register a new membership </a>
          </p>
        </div>
        <!-- /.login-card-body -->
      </div>
    </main>
    <!-- /.login-box -->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="./assets/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
          sidebarWrapper &&
          globalThis.OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <!--begin::Color Mode Toggle-->
    <!-- The light/dark/auto switcher ships in adminlte.js as the ColorMode
     module (since 4.1) — no page script needed. Only the no-flash snippet
     in <head> stays inline, because it must run before first paint. -->
    <!--end::Color Mode Toggle-->

    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
