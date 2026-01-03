<nav class="bg-white border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">

            <!-- LOGO -->
            <a href="{{ route('home') }}" class="font-bold text-lg">
                RayhanShope ✨
            </a>

            <!-- SEARCH -->
            <div class="hidden md:block flex-1 mx-10">
                <form action="{{ route('home') }}" method="GET">
                    <input type="text"
                        name="q"
                        placeholder="Cari produk favorit lu..."
                        class="w-full border rounded-full px-5 py-2">
                </form>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                @auth
                    <!-- CART -->
                    <a href="{{ route('cart.index') }}" class="relative">
                        🛒
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-2 rounded-full">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- 🔥 ADMIN DASHBOARD -->
                    @if(Auth::user()->isAdmin())
                        <a href="{{ url('/admin/dashboard') }}"
                           class="bg-red-600 text-white px-3 py-1 rounded">
                            🛠 Admin
                        </a>
                    @endif

                    <!-- USER -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-gray-600">Logout</button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endguest
            </div>
        </div>
    </div>
</nav>
