
<header>
    <nav class="bg-primary border-gray-200 fixed w-full top-0 left-0 z-50">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
            <a wire:navigate href="{{ route('landing.index') }}" class="flex items-center">
                <img src="{{ Vite::asset('resources/images/logo/color-no-bg.png') }}" class="h-8 mr-3" alt="Banana Express" />
            </a>
            <button data-collapse-toggle="mega-menu-full-image" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-accent rounded-lg md:hidden hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="mega-menu-full-image" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5 text-accent" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>
            <div id="mega-menu-full-image" class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1">
                <ul class="flex flex-col font-bold mt-4 md:flex-row md:space-x-8 md:mt-0">
                    <li>
                        <a href="#" class="block py-2 pl-3 pr-4 text-accent border-b border-secondary hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-secondary md:p-0">Tracking</a>
                    </li>
                    <li>
                        <a wire:navigate href="{{ route('landing.index').'#service' }}" class="block py-2 pl-3 pr-4 text-accent border-b border-secondary hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-secondary md:p-0">Services</a>
                    </li>
                    <li>
                        <a wire:navigate href="{{ route('landing.index').'#client' }}" class="block py-2 pl-3 pr-4 text-accent border-b border-secondary hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-secondary md:p-0">Our Client</a>
                    </li>
                    <li>
                        <a wire:navigate href="{{ route('landing.index').'#faq' }}" class="block py-2 pl-3 pr-4 text-accent border-b border-secondary hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-secondary md:p-0">FAQ</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 pl-3 pr-4 text-accent border-b border-secondary hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-secondary md:p-0">News</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 pl-3 pr-4 text-accent border-b border-secondary hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-secondary md:p-0">Contact</a>
                    </li>
                    <li>

                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
