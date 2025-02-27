<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ request()->is('/') ? 'Login Page' :  'Books Page' }}</title>
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="h-full">

<div class="min-h-full">
<nav class="bg-gray-300">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">
        <div class="flex w-full justify-between items-center">
            <div class="flex items-center">
                <div class="shrink-0">
                    <img class="size-8" src="{{asset('images/images-removebg-preview.png')}}" alt="Your Company">
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <x-nav_link href="/books" :active="request()->is('books')">Home</x-nav_link>
                        <x-nav_link href="/books/create" :active="request()->is('books/create')">Enter a Book</x-nav_link>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @auth
                    <p>Welcome, <span class="italic text-sm">{{Auth::user()->name}}</span> </p>
                    <form action="/logout" method="post">
                        @csrf
                        <button type="submit" class="cursor-pointer rounded-md px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-700 hover:text-white">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
    </div>
</nav>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{$heading}} </h1>
    </div>
</header>
<main class="pb-16">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    {{$slot}}
    </div>
</main>
</div>

</body>
</html>