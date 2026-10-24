@extends('theme::template')

@section('layout')
    <div class="w-full px-6 lg:px-10 flex flex-col lg:flex-row my-10 justify-between gap-8 text-slate-300">
        <!-- Widget -->
        <div class="xosid-sidebar w-full space-y-8">
            @include('theme::partials.sidebar')
        </div>
        {{-- Content --}}
        <main class="xosid-main-content w-full bg-slate-900/40 rounded-2xl p-6 lg:p-8 border border-white/5 shadow-2xl backdrop-blur-md overflow-hidden transform-gpu ring-1 ring-white/5 ring-inset">
            @yield('content')
        </main>
    </div>
@endsection
