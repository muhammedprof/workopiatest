@props(['type', 'message'])

@if($message)
<div
  x-data="{ show: true }"
  x-init="setTimeout(() => show = false, 3500)"
  x-show="show"
  class="p-4 mb-4 text-sm text-white {{ $type === 'success' ? 'bg-green-500' : 'bg-red-500' }} rounded">
    {{ $message }}
</div>
@endif