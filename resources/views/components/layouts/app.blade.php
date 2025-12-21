<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'M7 Church') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
<x-layouts.app.sidebar :title="$title ?? null">
    {{ $slot }}
</x-layouts.app.sidebar>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (Session::has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: "{{ Session::get('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (Session::has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ Session::get('error') }}",
            });
        @endif

        @if (Session::has('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: "{{ Session::get('warning') }}",
            });
        @endif

        @if (Session::has('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informação',
                text: "{{ Session::get('info') }}",
            });
        @endif

        @if ($errors->any())
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += "<p>{{ $error }}</p>";
            @endforeach
            Swal.fire({
                icon: 'error',
                title: 'Erro de Validação',
                html: errorMessages,
            });
        @endif
    });
</script>

@livewireScripts
</body>
</html>
