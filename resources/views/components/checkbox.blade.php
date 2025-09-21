@props(['name' => 'remember', 'id' => $name, 'checked' => false])

<input
  type="checkbox"
  name="{{ $name }}"
  id="{{ $id }}"
  {{ $checked ? 'checked' : '' }}
  {{ $attributes->merge(['class' => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500']) }}
/>