<div id="novo-message-{{ $message['id'] }}" class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-4 py-3 last:border-b-0">
    <div class="flex items-center gap-3">
        <span class="text-sm font-medium text-slate-900">{{ $message['text'] }}</span>
        <span class="text-xs text-slate-500">({{ $message['created_at'] }})</span>
    </div>
    <form method="post" action="{{ route('novo.turbo.destroy', $message['id']) }}" data-turbo-stream="true">
        @csrf
        @method('delete')
        {{-- Submit para remover a mensagem atual (rota DELETE). --}}
        <x-ui::button type="submit" variant="danger" class="tw:min-w-0 tw:px-2 tw:py-1 tw:text-xs">
            Remover
        </x-ui::button>
    </form>
</div>
