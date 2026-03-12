<header class="bg-blue-900 text-white p-4" x-data="{ open: false }">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-3xl font-semibold">
      <a href="{{ route('home') }}">Workopia</a>
    </h1>
    <nav class="hidden md:flex items-center space-x-4">
      <x-nav-link url="/" :active="request()->is('/')">Home</x-nav-link>
      <x-nav-link url="/jobs" :active="request()->is('jobs')"
        >All Jobs</x-nav-link
      >
      @auth
      <x-nav-link url="/bookmarks" :active="request()->is('bookmarks')"
        >Saved Jobs</x-nav-link
      >

      <x-button-link url="/jobs/create" type="button" icon="edit"
        >Create Job</x-button-link
      >

      <!-- User Avatar -->
      <div class="flex items-center space-x-3">
        <a href="{{ route('dashboard') }}">
          @if(Auth::user()->avatar)
          <img
            src="{{ asset('storage/' . Auth::user()->avatar) }}"
            alt="{{ Auth::user()->name }}"
            class="w-10 h-10 rounded-full"
          />
          @else
          <img
            src="{{ asset('storage/avatars/default-avatar.png') }}"
            alt="{{ Auth::user()->name }}"
            class="w-10 h-10 rounded-full"
          />
          @endif
        </a>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-white">
          <i class="fa fa-sign-out"></i> Logout
        </button>
      </form>
      @else
      <x-nav-link url="/login" :active="request()->is('login')"
        >Login</x-nav-link
      >
      <x-nav-link url="/register" :active="request()->is('register')"
        >Register</x-nav-link
      >
      @endauth
    </nav>
    <button
      @click="open = !open"
      class="text-white md:hidden flex items-center"
    >
      <i class="fa fa-bars text-2xl"></i>
    </button>
  </div>
  <!-- Mobile Menu -->
  <nav
    x-show="open"
    @click.away="open = false"
    class="md:hidden bg-blue-900 text-white mt-5 pb-4 space-y-2"
  >
    <x-nav-link mobile="true" url="/" :active="request()->is('/')"
      >Home</x-nav-link
    >
    <x-nav-link mobile="true" url="/jobs" :active="request()->is('jobs')"
      >All Jobs</x-nav-link
    >
    @auth
    <x-nav-link mobile="true" url="/bookmarksmarks" :active="request()->is('bookmarksmarks')"
      >Saved Jobs</x-nav-link
    >
    <x-nav-link mobile="true"
      url="/dashboard"
      :active="request()->is('dashboard')"
      icon="gauge"
      >Dashboard
    </x-nav-link>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="text-white">
        <i class="fa fa-sign-out"></i> Logout
      </button>
    </form>
    <div class="py-2">
      <x-button-link url="/jobs/create" type="button" icon="edit"
        >Create Job</x-button-link
      >
    </div>
    @else
    <x-nav-link mobile="true" url="/login" :active="request()->is('login')"
      >Login</x-nav-link
    >
    <x-nav-link mobile="true" url="/register" :active="request()->is('register')"
      >Register</x-nav-link
    >
    @endauth
  </nav>
</header>