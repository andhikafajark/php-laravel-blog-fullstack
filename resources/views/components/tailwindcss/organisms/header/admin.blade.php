<!DOCTYPE html>
<html :class="{ dark }" x-data="data()" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>{{ $title ?? '' }} | {{ env('APP_NAME') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    {{--    <link rel="stylesheet" href="{{ asset('assets') }}/css/tailwind.output.css" />--}}
    @vite('resources/css/app.css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css"/>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="{{ asset('assets') }}/js/init-alpine.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" defer></script>
    <script src="{{ asset('assets') }}/js/charts-lines.js" defer></script>
    <script src="{{ asset('assets') }}/js/charts-pie.js" defer></script>

    @stack('styles')

</head>
