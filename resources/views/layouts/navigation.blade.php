<nav class="bg-white border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">

            <!-- LEFT -->
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="font-bold text-lg">
                    FabatikShopee ✨
                </a>
            </div>

            <!-- SEARCH -->
            <div class="flex-1 mx-10 hidden md:block">
                <form action="{{ route('home') }}" method="GET">
                    <input
                        type="text"
                        name="q"
                        placeholder="Cari produk favorit lu..."
                        class="w-full border rounded-full px-5 py-2 focus:outline-none focus:ring"
                    >
                </form>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-6">

                @auth
                    <!-- CART -->
                    <a href="{{ route('cart.index') }}" class="relative flex items-center gap-1">
                        🛒 Cart
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-3 bg-red-600 text-white text-xs px-2 rounded-full">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- ✅ ADMIN DASHBOARD BUTTON (FIX TOTAL) -->
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-1 bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                            🛠 Admin
                        </a>
                    @endif

                    <!-- USER DROPDOWN -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="font-semibold">
                            {{ Auth::user()->name }} ⌄
                        </button>

                        <div x-show="open" @click.away="open=false"
                             class="absolute right-0 mt-2 w-40 bg-white border rounded shadow">
                            <a href="{{ route('orders.index') }}"
                               class="block px-4 py-2 hover:bg-gray-100">
                                Pesanan
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-4 py-2 hover:bg-gray-100">
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
