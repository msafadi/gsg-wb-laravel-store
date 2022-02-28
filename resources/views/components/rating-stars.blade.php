@props([
    'rating'
])
<select {{ $attributes->class(['ps-rating']) }}>
    @for($i = 1; $i <= 5; $i++)
    <option value="{{ $i <= $rating? 1 : $i }}">{{ $i }}</option>
    @endfor
</select>