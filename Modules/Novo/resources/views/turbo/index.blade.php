@extends('layouts.app')
@section('content')
    <div class="mx-auto flex w-full max-w-2xl flex-col gap-6">
        <header class="space-y-1">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Novo Módulo + Turbo Streams</h1>
            <p class="text-sm text-slate-600">Este exemplo vive no modulo Novo e usa Turbo para atualizar a lista sem
                recarregar.</p>
        </header>

        <form method="post" action="{{ route('novo.turbo.store') }}" data-turbo-stream="true"
            class="space-y-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            @csrf
            <div>
                <label for="message" class="text-sm font-medium text-slate-700">Mensagem (max 140)</label>
                <input id="message" name="message" type="text" maxlength="140" required
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200">
            </div>
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-sky-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-sky-700">
                    Enviar
                </button>
                <x-ui::button type="submit" variant="primary">
                    Salvar
                </x-ui::button>
                @error('message')
                    <span class="text-sm text-rose-600">{{ $message }}</span>
                @enderror
            </div>
        </form>

        <form method="post" action="{{ route('novo.turbo.clear') }}" data-turbo-stream="true">
            @csrf
            @method('delete')
            <x-ui::button type="submit" variant="secondary">
                Limpar tudo
            </x-ui::button>
        </form>

        <div id="novo-messages" class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            @forelse($messages as $message)
                @include('novo::turbo.partials.message', ['message' => $message])
            @empty
                <p id="novo-empty" class="px-4 py-6 text-sm text-slate-500">Nenhuma mensagem ainda.</p>
            @endforelse
        </div>
    </div>
@endsection
