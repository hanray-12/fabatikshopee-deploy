<nav class="bg-white border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">

            <!-- LEFT -->
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <x-application-logo size="sm" theme="light" />
                </a>
            </div>

            <!-- SEARCH -->
            <div class="flex-1 mx-10 hidden md:block">
                <form action="{{ route('home') }}" method="GET">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') ?? request('q') }}"
                        placeholder="Cari produk..."
                        class="w-full border rounded-full px-5 py-2 focus:ring focus:ring-red-200 focus:outline-none"
                    />
                </form>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                <!-- CART -->
                @php
                    $cartCount = collect(session('cart', []))->sum('quantity');
                @endphp

                <a href="{{ route('cart.index') }}" class="relative font-semibold">
                    🛒 Cart
                    @if($cartCount > 0)
                        <span
                            class="absolute -top-2 -right-3 bg-red-600 text-white text-xs px-2 py-0.5 rounded-full">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- ADMIN BUTTON -->
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-red-700 flex items-center gap-2">
                            ⚙️ Admin
                        </a>
                    @endif

                    <!-- USER DROPDOWN -->
                    <div class="relative">
                        <button class="font-semibold">
                            {{ auth()->user()->name }}
                        </button>

                        <div class="mt-2 absolute right-0 bg-white border rounded shadow-lg w-40 hidden group-hover:block">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endguest

            </div>
        </div>
    </div>
</nav>
