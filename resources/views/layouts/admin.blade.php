<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>관리자 - {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex" x-data="{ sidebarOpen: false }">
            {{-- Mobile Overlay --}}
            <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
                 class="fixed inset-0 bg-black/50 z-30 md:hidden" x-cloak></div>

            {{-- Sidebar --}}
            <aside class="w-64 bg-gray-900 text-gray-100 min-h-screen flex-shrink-0 fixed inset-y-0 left-0 z-40 transform -translate-x-full transition-transform duration-200 md:relative md:translate-x-0"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
                   x-cloak>
                <div class="p-4 border-b border-gray-700">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-white">
                        관리자 패널
                    </a>
                </div>
                <nav class="mt-4 space-y-1 px-2">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"></path></svg>
                        대시보드
                    </a>
                    <a href="{{ route('admin.hanja-chars.index') }}"
                       class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.hanja-chars.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        한자 관리
                    </a>
                    <a href="{{ route('admin.learning-tracks.index') }}"
                       class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.learning-tracks.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        트랙 관리
                    </a>
                    <a href="{{ route('admin.lessons.index') }}"
                       class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.lessons.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        레슨 관리
                    </a>
                    <a href="{{ route('admin.quiz-sets.index') }}"
                       class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.quiz-sets.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        퀴즈 관리
                    </a>
                    <a href="{{ route('admin.saju-examples.index') }}"
                       class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.saju-examples.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        사주 예시
                    </a>
                    <a href="{{ route('admin.audit-logs.index') }}"
                       class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.audit-logs.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        감사 로그
                    </a>

                    <hr class="border-gray-700 my-4">

                    <a href="{{ route('dashboard') }}"
                       class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
                        사이트로 돌아가기
                    </a>
                </nav>
            </aside>

            {{-- Main Content --}}
            <div class="flex-1 flex flex-col">
                {{-- Top Bar --}}
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="px-6 py-4 flex items-center justify-between">
                        {{-- Hamburger (mobile only) --}}
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden mr-3 p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <h1 class="text-lg font-semibold text-gray-800">
                            @yield('title', '관리자')
                        </h1>
                        <div class="text-sm text-gray-500">
                            {{ Auth::user()->email }}
                        </div>
                    </div>
                </header>

                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="mx-6 mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mx-6 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Page Content --}}
                <main class="flex-1 p-6">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
