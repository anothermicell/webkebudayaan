<?php
session_start();
require 'config.php';
$db = new Database();

// Jika sudah login, arahkan sesuai role
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
    $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
    $username    = trim($_POST['username'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $password    = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Username dan password wajib diisi!";
    } elseif ($db->countWhere('users', ['username' => $username]) > 0) {
        $error = "Username sudah terdaftar!";
    } else {
        $dataInsert = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => 'user'
        ];

        // Aktifkan dua baris di bawah ini jika tabel `users` memiliki kolom `nama_lengkap` dan `email`
        // if ($namaLengkap !== '') { $dataInsert['nama_lengkap'] = $namaLengkap; }
        // if ($email !== '') { $dataInsert['email'] = $email; }

        $berhasil = $db->insert('users', $dataInsert);

        if ($berhasil) {
            $newUser = $db->get('users', ['username' => $username]);
            session_regenerate_id(true);
            $_SESSION['user_id']  = $newUser['id'] ?? null;
            $_SESSION['username'] = $username;
            $_SESSION['role']     = 'user';

            header("Location: user/dashboard.php");
            exit;
        } else {
            $error = "Registrasi gagal. Silakan coba lagi.";
        }
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Web Kebudayaan | Register</title>
    <!-- Theme Init -->
    <script>
        (() => {
            'use strict';
            const root = document.documentElement;

            if (root.getAttribute('data-lte-color-mode') === 'off') {
                return;
            }

            const STORAGE_KEY = 'lte-theme';
            let stored = null;

            try {
                stored = localStorage.getItem(STORAGE_KEY);
            } catch {}

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

            if (resolved !== authored) {
                root.setAttribute('data-lte-theme-resolved', '');
            }
        })();
    </script>

    <!-- Meta Tags -->
    <meta name="color-scheme" content="light" />
    <meta name="theme-color" content="#5C4033" />

    <!-- Fonts -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.3.0/index.css"
        crossorigin="anonymous"/>

    <!-- OverlayScrollbars -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous"/>

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous"/>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="./assets/css/adminlte.css" />

    <!-- Custom CSS Tema Kebudayaan -->
    <style>

        /* Background halaman */
        body.register-page {
            background-color: #E8DCC8 !important;
            min-height: 100vh;
            height: auto !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 24px 16px;
        }

        /* Card Register */
        .register-box .card {
            background-color: #FFFDF7;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(92, 64, 51, 0.15);
        }

        /* Header */
        .register-box .card-header {
            background-color: #5C4033;
            text-align: center;
            border: none;
            padding: 14px 18px;
        }

        .register-box .card-header h1 {
            color: #F5E6C8;
            font-size: 1.65rem;
        }

        .register-box .card-header small {
            color: #E8DCC8;
        }

        /* Isi Card */
        .register-card-body {
            background-color: #FFFDF7 !important;
            color: #5C4033 !important;
            padding: 1.15rem;
        }

        /* Tulisan Register */
        .register-box-msg {
            color: #5C4033 !important;
            margin-bottom: 0.85rem;
        }

        /* Input */
        .register-box .form-control {
            background-color: #FFFDF7 !important;
            border-color: #D8C7AE !important;
            color: #5C4033 !important;
        }

        .register-box .form-control:focus {
            border-color: #A67C52 !important;
            box-shadow: 0 0 0 0.2rem rgba(166, 124, 82, 0.2);
        }

        /* Sedikit memperkecil tinggi input floating */
        .register-box .form-floating > .form-control {
            height: calc(3rem + 2px);
            min-height: calc(3rem + 2px);
            padding: 0.75rem;
        }

        /* Label Input */
        .register-box .form-floating > label {
            color: #6B4A3B !important;
            opacity: 1;
            padding: 0.75rem;
        }

        /* Placeholder */
        .register-box .form-control::placeholder {
            color: #8B735E;
            opacity: 1;
        }

        /* Ikon Input */
        .register-box .input-group-text {
            background-color: #E8DCC8 !important;
            border-color: #D8C7AE !important;
            color: #5C4033 !important;
        }

        /* Checkbox */
        .register-box .form-check-input {
            accent-color: #5C4033;
            border-color: #A67C52 !important;
        }

        .register-box .form-check-input:checked {
            background-color: #5C4033 !important;
            border-color: #5C4033 !important;
        }

        /* Label Checkbox */
        .register-box .form-check-label {
            color: #5C4033;
        }

        /* Tombol Daftar */
        .btn-register {
            background-color: #5C4033;
            color: #FFFDF7 !important;
            border: none;
        }

        .btn-register:hover {
            background-color: #432D24;
            color: #FFFFFF !important;
        }

        /* Tombol Email */
        .btn-email {
            background-color: #A67C52;
            color: #FFFDF7 !important;
            border: none;
        }

        .btn-email:hover {
            background-color: #8B6341;
            color: #FFFFFF !important;
        }

        /* Tombol Google */
        .btn-google {
            background-color: #5C4033;
            color: #FFFDF7 !important;
            border: none;
        }

        .btn-google:hover {
            background-color: #432D24;
            color: #FFFFFF !important;
        }

        /* Ikon Tombol */
        .social-auth-links .bi {
            color: #D4AF37;
        }

        /* Link */
        .register-card-body a {
            color: #A67C52;
        }

        .register-card-body a:hover {
            color: #5C4033;
        }

    </style>
</head>

<body class="register-page">
    <main class="register-box">
        <div class="card">
            <!-- Header Register -->
            <div class="card-header">
                <h1 class="mb-1">
                    <span style="color: #F5E6C8; font-weight: bold;">
                        Daftar
                    </span>

                    <span style="color: #D4AF37;">
                        Kebudayaan
                    </span>
                </h1>

                <small>
                    WARISAN KEBUDAYAAN PURBALINGGA
                </small>

            </div>
            <!-- Register Card Body -->
            <div class="card-body register-card-body">
                <p class="register-box-msg">
                    Buat Akun Baru
                    <br>
                    <small style="color: #8B735E;">
                        Silakan lengkapi data untuk mendaftar
                    </small>
                </p>

                <form action="../index3.html" method="post">
                    <!-- Nama Lengkap -->
                    <div class="input-group mb-2">
                        <div class="form-floating">
                            <input
                                id="registerFullName"
                                type="text"
                                class="form-control"
                                name="nama_lengkap"
                                placeholder=""
                                required/>

                            <label for="registerFullName">
                                Nama Lengkap
                            </label>
                        </div>
                        <div class="input-group-text">
                            <span class="bi bi-person"></span>
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="input-group mb-2">
                        <div class="form-floating">
                            <input
                                id="registerUsername"
                                type="text"
                                class="form-control"
                                name="username"
                                placeholder=""
                                required/>

                            <label for="registerUsername">
                                Username
                            </label>
                        </div>
                        <div class="input-group-text">
                            <span class="bi bi-person-fill"></span>
                        </div>

                    </div>

                    <!-- Email -->
                    <div class="input-group mb-2">

                        <div class="form-floating">
                            <input
                                id="registerEmail"
                                type="email"
                                class="form-control"
                                name="email"
                                placeholder=""
                                required/>

                            <label for="registerEmail">
                                Email
                            </label>
                        </div>
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>

                    </div>

                    <!-- Password -->
                    <div class="input-group mb-2">

                        <div class="form-floating">
                            <input
                                id="registerPassword"
                                type="password"
                                class="form-control"
                                name="password"
                                placeholder=""
                                required/>

                            <label for="registerPassword">
                                Password
                            </label>
                        </div>
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>

                    </div>

                    <!-- Persetujuan dan Tombol Daftar -->
                    <div class="row align-items-center mb-3">
                        <div class="col-8">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    value="1"
                                    id="agreeTerms"
                                    required/>

                                <label
                                    class="form-check-label"
                                    for="agreeTerms"
                                >
                                    Saya menyetujui ketentuan
                                </label>
                            </div>
                        </div>

                        <div class="col-4">

                            <div class="d-grid">

                                <button
                                    type="submit"
                                    class="btn btn-register"
                                >
                                    Daftar
                                </button>

                            </div>

                        </div>

                    </div>
                </form>

                <!-- Login Alternatif -->
                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <p style="color: #8B735E;">- ATAU -</p>
                    <!-- Daftar Email -->
                    <a
                        href="#"
                        class="btn"
                        style="background-color: #A67C52;
                               color: #FFFDF7;
                               border: none;">
                        <i
                            class="bi bi-envelope-fill me-2"
                            style="color: #D4AF37;"
                        ></i>
                        Daftar menggunakan Email
                    </a>

                    <!-- Daftar Google -->
                    <a
                        href="#"
                        class="btn"
                        style="background-color: #5C4033;
                               color: #FFFDF7;
                               border: none;">
                        <i
                            class="bi bi-google me-2"
                            style="color: #D4AF37;"
                        ></i>
                        Daftar menggunakan Google
                    </a>
                </div>

                <!-- Link Login -->
                <p class="mb-0">
                    <a href="login.html">
                        Saya sudah memiliki akun
                    </a>
                </p>
            </div>
            <!-- /.register-card-body -->
        </div>
    </main>

    <!-- OverlayScrollbars -->
    <script
        src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"
    ></script>

    <!-- Popper -->
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        crossorigin="anonymous"
    ></script>

    <!-- Bootstrap -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        crossorigin="anonymous"
    ></script>

    <!-- AdminLTE -->
    <script src="./assets/js/adminlte.js"></script>

    <!-- OverlayScrollbars Configure -->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';

        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };

        document.addEventListener('DOMContentLoaded', function () {

            const sidebarWrapper = document.querySelector(
                SELECTOR_SIDEBAR_WRAPPER
            );

            const isMobile = window.innerWidth <= 992;

            if (
                sidebarWrapper &&
                globalThis.OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
                !isMobile
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(
                    sidebarWrapper,
                    {
                        scrollbars: {
                            theme: Default.scrollbarTheme,
                            autoHide: Default.scrollbarAutoHide,
                            clickScroll: Default.scrollbarClickScroll,
                        },
                    }
                );
            }

        });
    </script>
</body>
</html>