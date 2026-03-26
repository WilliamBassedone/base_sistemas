<?php

namespace Modules\Novo\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NovoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('novo::index');
    }

    public function turboIndex(Request $request)
    {
        $messages = $request->session()->get('novo_messages', []);
        return view('novo::turbo.index', compact('messages'));
    }

    public function turboStore(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:140'],
        ]);

        $message = [
            'id' => (string) Str::uuid(),
            'text' => $validated['message'],
            'created_at' => now()->format('H:i:s'),
        ];

        $messages = $request->session()->get('novo_messages', []);
        $messages[] = $message;
        $request->session()->put('novo_messages', $messages);

        // Verifica se o request aceita resposta no formato Turbo Stream.
        if ($request->wantsTurboStream()) {
            // Retorna um conjunto de ações Turbo em uma única resposta.
            return turbo_stream([
                // Remove o placeholder "Nenhuma mensagem ainda", se existir.
                turbo_stream()->remove('novo-empty'),
                // Adiciona a nova mensagem no final da lista.
                turbo_stream()->append('novo-messages', view('novo::turbo.partials.message', [
                    // Envia os dados da mensagem para a partial de renderização.
                    'message' => $message,
                ])),
            ]);
        }

        return redirect()->route('novo.turbo');
    }

    public function turboClear(Request $request)
    {
        $request->session()->forget('novo_messages');
        if ($request->wantsTurboStream()) {
            return turbo_stream([
                turbo_stream()->update('novo-messages', view('novo::turbo.partials.empty'))
            ]);
        }
        return redirect()->route('novo.turbo');
    }

    public function turboDestroy(Request $request, string $id)
    {
        $messages = $request->session()->get('novo_messages', []);
        $messages = array_values(array_filter($messages, fn($message) => $message['id'] !== $id));
        $request->session()->put('novo_messages', $messages);

        if ($request->wantsTurboStream()) {
            $streams = [
                turbo_stream()->remove("novo-message-{$id}"),
            ];

            if (count($messages) === 0) {
                $streams[] = turbo_stream()->append('novo-messages', view('novo::turbo.partials.empty'));
            }

            return turbo_stream($streams);
        }

        return redirect()->route('novo.turbo');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('novo::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('novo::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('novo::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
