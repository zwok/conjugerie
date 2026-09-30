@props(['initials', 'color', 'size' => 'size-7 text-[11px]'])

<span {{ $attributes->merge(['class' => "$size inline-flex shrink-0 items-center justify-center rounded-full font-bold text-white"]) }}
      style="background-color: {{ $color }}" aria-hidden="true">{{ $initials }}</span>
