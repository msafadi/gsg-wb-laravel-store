@props([
    'required' => false,
    'label'
])
<label {{ $attributes->class(['form-label', 'required' => $required]) }}>
    {{ $slot }}
</label>