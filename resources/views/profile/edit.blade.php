<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Profil Saya') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Profil</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f8fafc] min-h-screen px-6 lg:px-10 relative overflow-hidden">
        {{-- Decorative background elements --}}
        <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-blue-50/50 to-transparent -z-10"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-100/30 rounded-full blur-3xl -z-10"></div>
        <div class="absolute top-1/2 -left-24 w-72 h-72 bg-indigo-100/20 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-4xl mx-auto space-y-8 relative">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 p-8 md:p-10">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 p-8 md:p-10">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-rose-100 p-8 md:p-10">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
