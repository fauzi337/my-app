<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 items-center h-16">
            
            <!-- Left: Logo -->
            <div class="flex justify-start items-center">
                <div class="shrink-0 flex items-center">
                    <img src="{{ asset('images/timeline.png') }}" class="w-12 h-12 object-contain rounded-lg">
                </div>
            </div>

            <!-- Center: Beautified Centered Menu Dropdown -->
            <div class="flex justify-center items-center">
                <div x-data="{
                        menuOpen: false,
                        search: '',
                        menuItems: [
                            { label: 'Timeline Request', href: '{{ route('dashboard.jadwal') }}' },
                            { label: 'Agenda Pihak Luar', href: '{{ route('dashboard.agenda') }}' },
                            { label: 'Developer', href: '{{ route('dashboard.dev') }}' },
                            { label: 'PIC Request', href: '{{ route('dashboard.picreq') }}' },
                            { label: 'Request Server', href: '{{ route('dashboard.reqserver') }}' },
                            { label: 'Daily Report', href: '{{ route('dashboard.daily') }}' },
                            { label: 'Weekly Report', href: '{{ route('dashboard.weekly') }}' }
                        ]
                    }"
                    class="relative">
                    
                    <!-- Beautiful Pill Menu Button -->
                    <button @click="menuOpen = !menuOpen; if(menuOpen) { $nextTick(() => $refs.searchBox.focus()) }"
                        class="inline-flex items-center gap-2 px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-full text-xs font-bold tracking-widest text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 hover:bg-lime-500 hover:text-white dark:hover:bg-lime-500 dark:hover:text-white hover:border-lime-500 dark:hover:border-lime-500 transition-all duration-200 shadow-sm focus:outline-none uppercase">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <span>Pilih Menu</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 transition-transform duration-200" :class="{'rotate-180': menuOpen}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="menuOpen" 
                        @click.outside="menuOpen = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-[-10px]"
                        class="absolute left-1/2 -translate-x-1/2 mt-3 bg-white dark:bg-gray-800 shadow-xl border border-gray-100 dark:border-gray-700 rounded-2xl py-3 w-72 z-50 max-h-80 overflow-y-auto">

                        <!-- Search Input -->
                        <div class="px-4 pb-3 border-b border-gray-100 dark:border-gray-700 mb-2">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input x-model="search" x-ref="searchBox" type="text"
                                    placeholder="Cari menu..."
                                    class="w-full pl-9 pr-4 py-1.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-lime-500 focus:border-lime-500 transition-all duration-200"
                                >
                            </div>
                        </div>

                        <!-- Menu List -->
                        <div class="px-2 space-y-1">
                            <template x-for="item in menuItems.filter(i => i.label.toLowerCase().includes(search.toLowerCase()))" :key="item.label">
                                <a :href="item.href"
                                    class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 hover:bg-lime-50 dark:hover:bg-gray-700 hover:text-lime-600 dark:hover:text-lime-400 rounded-xl cursor-pointer text-sm font-semibold transition-all duration-150 no-underline"
                                    x-text="item.label">
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Settings Dropdown & Dark Mode Toggle -->
            <div class="flex justify-end items-center gap-3">
                
                <!-- Dark Mode Toggle Button -->
                <button id="theme-toggle" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none rounded-full p-2.5 text-sm transition-colors duration-200 shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700">
                    <svg id="theme-toggle-dark-icon" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.46 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>

                <!-- Profile Dropdown -->
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-xs font-bold uppercase rounded-full text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1.5">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="no-underline">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        class="no-underline"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger (Mobile Toggle) -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
        <!-- Quick Menu Search and List for Mobile -->
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700" 
             x-data="{
                mobileSearch: '',
                mobileMenuItems: [
                    { label: 'Timeline Request', href: '{{ route('dashboard.jadwal') }}' },
                    { label: 'Agenda Pihak Luar', href: '{{ route('dashboard.agenda') }}' },
                    { label: 'Developer', href: '{{ route('dashboard.dev') }}' },
                    { label: 'PIC Request', href: '{{ route('dashboard.picreq') }}' },
                    { label: 'Request Server', href: '{{ route('dashboard.reqserver') }}' },
                    { label: 'Daily Report', href: '{{ route('dashboard.daily') }}' },
                    { label: 'Weekly Report', href: '{{ route('dashboard.weekly') }}' }
                ]
             }">
            <div class="mb-2">
                <input x-model="mobileSearch" type="text" placeholder="Cari menu..." class="w-full px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
            </div>
            <div class="grid grid-cols-2 gap-2 mt-2">
                <template x-for="item in mobileMenuItems.filter(i => i.label.toLowerCase().includes(mobileSearch.toLowerCase()))" :key="item.label">
                    <a :href="item.href" class="block px-3 py-2 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg no-underline" x-text="item.label"></a>
                </template>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="no-underline">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            class="no-underline"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>