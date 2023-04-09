<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>{{ $title ?? '' }} | {{ env('APP_NAME') }}</title>

    <meta property="og:title" content="Homepage | Atlas Template"/>

    <meta property="og:locale" content="en_US"/>

    <link rel="canonical" href="https://atlas.tailwindmade.com/"/>

    <meta property="og:url" content="https://atlas.tailwindmade.com/"/>

    <meta name="description"
          content="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."/>

    <link rel="icon" type="image/png" href="{{ asset('assets') }}/img/favicon.png"/>

    <meta property="og:site_name" content="Atlas Template"/>

    <meta property="og:image" content="https://atlas.tailwindmade.com{{ asset('assets') }}/img/social.jpg"/>

    <meta name="twitter:card" content="summary_large_image"/>

    <meta name="twitter:site" content="@tailwindmade"/>

    <link crossorigin="crossorigin" href="https://fonts.gstatic.com" rel="preconnect"/>

    <link as="style" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="preload"/>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet"/>

    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css"/>

    {{--    <link crossorigin="anonymous" href="{{ asset('assets') }}/styles/main.min.css" media="screen" rel="stylesheet"/>--}}

    @vite('resources/css/app.css')

    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.5.0/highlight.min.js"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.5.0/styles/atom-one-dark.min.css"/>

    <script>
        hljs.initHighlightingOnLoad();
    </script>

    <!--Alpine Js V3-->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @stack('styles')

</head>
