@extends('layouts.app')

@section('content')
    <h1>Blog Module + Turbo Streams</h1>
    <p>Este exemplo vive no modulo Blog e usa Turbo para atualizar a lista sem recarregar.</p>

    <x-ui::alert variant="success" title="Sucesso">
        Registro salvo com sucesso.
    </x-ui::alert>

    <x-ui::alert variant="warning" dismissible>
        Atenção: esta ação não poderá ser desfeita.
    </x-ui::alert>

    <x-ui::alert variant="danger">
        Ocorreu um erro ao processar a requisição.
    </x-ui::alert>

    <x-ui::alert variant="warning" dismissible>
        Atenção: esta ação não poderá ser desfeita.
    </x-ui::alert>

    <form method="post" action="{{ route('blog.turbo.store') }}" data-turbo-stream="true" style="margin-bottom: 20px;">
        @csrf
        <label for="message" style="display: block; margin-bottom: 6px;">Mensagem (max 140)</label>
        <input id="message" name="message" type="text" maxlength="140" required
            style="padding: 6px; width: 100%; max-width: 420px;">
        <button type="submit" style="margin-top: 10px;">Enviar</button>
        @error('message')
            <div style="color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
        @enderror
    </form>

    <div id="blog-messages">
        @forelse ($messages as $message)
            @include('blog::turbo.partials.message', ['message' => $message])
        @empty
            <p id="blog-empty">Nenhuma mensagem ainda.</p>
        @endforelse

        <x-ui::jodit-editor name="content" label="Conteúdo" :value="$post->content ?? ''" placeholder="Escreva aqui..."
            height="520" />
    </div>
@endsection
