<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 text-gray-900 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-12 items-center">
            <div class="flex items-center space-x-2">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center">
                    <x-application-logo class="block h-6 w-auto fill-current text-gray-900" />
                </a>
                <!-- Navigation Links -->
                <div class="hidden md:flex space-x-2 text-sm font-medium">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="px-2 py-1">{{ __('Home') }}</x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="px-2 py-1">{{ __('Products') }}</x-nav-link>
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="px-2 py-1">{{ __('Categories') }}</x-nav-link>
                    <x-nav-link :href="route('about')" :active="request()->routeIs('about')" class="px-2 py-1">{{ __('About') }}</x-nav-link>
                    <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="px-2 py-1">{{ __('Contact') }}</x-nav-link>
                </div>
            </div>
            <!-- Right Side -->
            <div class="flex items-center space-x-1">
                <!-- Cart Icon -->
                <a href="{{ route('cart.index') }}" class="relative hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span class="absolute -top-1 -right-2 bg-gray-900 text-white rounded-full w-4 h-4 text-[10px] flex items-center justify-center">{{ Cart::count() ?? 0 }}</span>
                </a>
                <!-- User Dropdown / Auth -->
                <div>
                    @auth
                        <x-dropdown align="right" width="40">
                            <x-slot name="trigger">
                                <button class="flex items-center space-x-1 px-2 py-1 rounded hover:bg-gray-100 focus:outline-none">
                                    <span class="text-xs font-medium">{{ \Illuminate\Support\Str::limit(\Illuminate\Support\Facades\Auth::user()->name, 12) }}</span>
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('orders.index')">{{ __('My Orders') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('wishlist.index')">{{ __('Wishlist') }}</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <a href="{{ route('login') }}" class="px-2 py-1 text-xs rounded hover:bg-gray-100 transition">Login</a>
                        <a href="{{ route('register') }}" class="px-2 py-1 text-xs rounded bg-gray-900 hover:bg-gray-700 text-white transition">Register</a>
                    @endauth
                </div>
                <!-- Hamburger -->
                <button @click="open = ! open" class="ml-1 md:hidden p-2 rounded hover:bg-gray-100 focus:outline-none">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1 px-4 text-sm">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">{{ __('Home') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">{{ __('Products') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">{{ __('Categories') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')">{{ __('About') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')">{{ __('Contact') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                {{ __('Cart') }} <span class="bg-gray-900 text-white rounded-full px-2 ml-2 text-xs">{{ Cart::count() ?? 0 }}</span>
            </x-responsive-nav-link>
        </div>
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 px-4">
                <div class="font-medium text-base text-gray-900">{{ \Illuminate\Support\Facades\Auth::user()->name }}</div>
                <div class="font-medium text-xs text-gray-500">{{ \Illuminate\Support\Facades\Auth::user()->email }}</div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('orders.index')">{{ __('My Orders') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('wishlist.index')">{{ __('Wishlist') }}</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200 px-4">
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">{{ __('Login') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">{{ __('Register') }}</x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>
