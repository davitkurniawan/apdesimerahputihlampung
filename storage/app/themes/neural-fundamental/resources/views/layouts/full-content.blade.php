@extends('theme::template')

@section('layout')
    <div class="w-full px-6 lg:px-10 flex flex-col my-10 justify-center items-center text-slate-300">
        <main class="xosid-main-content w-full bg-slate-900/40 rounded-2xl p-6 lg:p-8 border border-white/5 shadow-2xl backdrop-blur-md overflow-hidden transform-gpu ring-1 ring-white/5 ring-inset">
            {{-- Content --}}
            @yield('content')
        </main>
    </div>
@endsection
