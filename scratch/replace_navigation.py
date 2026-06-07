import re

file_path = '/home/yrizzz/Desktop/Absen/pulse-presence/resources/views/layouts/app.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Desktop Navigation Replacement
desktop_pattern = r'<!-- Navigation Links \(Desktop\) -->\s*<div class="hidden space-x-1 sm:-my-px sm:ml-10 sm:flex">.*?</div>'
desktop_replacement = """<!-- Navigation Links (Desktop) -->
                            <div class="hidden space-x-1 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('dashboard') }}" 
                                   class="inline-flex items-center border-b-[3px] px-3.5 pt-1 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'border-sky-400 text-sky-400' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Dashboard
                                </a>

                                <a href="{{ route('attendance.index') }}" 
                                   class="inline-flex items-center border-b-[3px] px-3.5 pt-1 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('attendance.*') ? 'border-sky-400 text-sky-400' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Attendance Space
                                </a>

                                <a href="{{ route('leaves.index') }}" 
                                   class="inline-flex items-center border-b-[3px] px-3.5 pt-1 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('leaves.*') ? 'border-sky-400 text-sky-400' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Leave Management
                                </a>

                                <a href="{{ route('reports.index') }}" 
                                   class="inline-flex items-center border-b-[3px] px-3.5 pt-1 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('reports.*') ? 'border-sky-400 text-sky-400' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Analytics
                                </a>

                                <a href="{{ route('settings.index') }}" 
                                   class="inline-flex items-center border-b-[3px] px-3.5 pt-1 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('settings.*') ? 'border-sky-400 text-sky-400' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Control Panel
                                </a>
                            </div>"""

content = re.sub(desktop_pattern, desktop_replacement, content, flags=re.DOTALL)

# Mobile Navigation Replacement
mobile_pattern = r'<!-- Mobile menu button -->.*?<div x-show="mobileOpen" @click\.away="mobileOpen = false" class="absolute top-16 left-0 right-0 bg-\[#121d33\] border-b border-white/5 shadow-md z-50" style="display: none;">\s*<div class="space-y-1\.5 pb-3\.5 pt-2\.5 px-4">.*?</div>'
mobile_replacement = """<!-- Mobile menu button -->
                        <div class="flex items-center sm:hidden" x-data="{ mobileOpen: false }">
                            <button @click="mobileOpen = !mobileOpen" class="inline-flex items-center justify-center rounded-xl p-2 text-slate-400 hover:bg-slate-800 hover:text-white transition-colors duration-150">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                                </svg>
                            </button>

                            <div x-show="mobileOpen" @click.away="mobileOpen = false" class="absolute top-16 left-0 right-0 bg-[#121d33] border-b border-white/5 shadow-md z-50" style="display: none;">
                                <div class="space-y-1.5 pb-3.5 pt-2.5 px-4">
                                    <a href="{{ route('dashboard') }}" class="block py-2 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'text-sky-400' : 'text-slate-300' }}">Dashboard</a>
                                    <a href="{{ route('attendance.index') }}" class="block py-2 text-sm font-semibold {{ request()->routeIs('attendance.*') ? 'text-sky-400' : 'text-slate-300' }}">Attendance Space</a>
                                    <a href="{{ route('leaves.index') }}" class="block py-2 text-sm font-semibold {{ request()->routeIs('leaves.*') ? 'text-sky-400' : 'text-slate-300' }}">Leave Management</a>
                                    <a href="{{ route('reports.index') }}" class="block py-2 text-sm font-semibold {{ request()->routeIs('reports.*') ? 'text-sky-400' : 'text-slate-300' }}">Analytics</a>
                                    <a href="{{ route('settings.index') }}" class="block py-2 text-sm font-semibold {{ request()->routeIs('settings.*') ? 'text-sky-400' : 'text-slate-300' }}">Control Panel</a>
                                </div>"""

content = re.sub(mobile_pattern, mobile_replacement, content, flags=re.DOTALL)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Navigation update python run completed successfully!")
