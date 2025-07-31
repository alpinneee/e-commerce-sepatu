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
                <!-- User Dropdown / Auth -->
                <div>
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(\Illuminate\Support\Facades\Auth::user()->name) }}&background=6366f1&color=fff" alt="{{ \Illuminate\Support\Facades\Auth::user()->name }}" class="w-7 h-7 rounded-full">
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ \Illuminate\Support\Facades\Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ \Illuminate\Support\Facades\Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">My Orders</a>
                                <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Wishlist</a>
                                <a href="{{ route('cart.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">cart</a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Log Out</button>
                                </form>
                            </div>
                        </div>
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
