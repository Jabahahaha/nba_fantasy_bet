@props(['field'])

@error($field)
    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
@enderror
