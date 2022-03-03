@props([
    'rating'
])
<select {{ $attributes->class(['ps-rating']) }}>
    <option value=""></option>
    @for($i = 1; $i <= 5; $i++)
    <option value="{{ $i }}" @if($i == $rating) selected @endif>{{ $i }}</option>
    @endfor
</select>