@props(['active' => false])

<a 
    class="{{ $active ? 'bg-gray-900 text-white' : 'text-gray-500 hover:bg-gray-700 hover:text-white' }} cursor-pointer rounded-md px-3 py-2 text-sm font-medium"
    {{ $attributes }}
>
    {{ $slot }}
</a>

