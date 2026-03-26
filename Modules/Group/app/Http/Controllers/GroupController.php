<?php

namespace Modules\Group\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = [
            ['group' => 'Administradores', 'description' => 'Acesso completo ao sistema', 'users' => 3, 'permissions' => 25, 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['group' => 'Financeiro', 'description' => 'Acesso a cobrancas e relatorios', 'users' => 8, 'permissions' => 12, 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['group' => 'Secretaria', 'description' => 'Acesso a cadastros academicos', 'users' => 14, 'permissions' => 10, 'status' => ['label' => 'Revisao', 'tone' => 'warning']],
            ['group' => 'Suporte', 'description' => 'Acesso ao atendimento interno', 'users' => 5, 'permissions' => 7, 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['group' => 'RH', 'description' => 'Acesso aos modulos de pessoas', 'users' => 4, 'permissions' => 11, 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['group' => 'Compras', 'description' => 'Acesso a solicitacoes e aprovacoes', 'users' => 6, 'permissions' => 9, 'status' => ['label' => 'Revisao', 'tone' => 'warning']],
            ['group' => 'TI', 'description' => 'Acesso aos recursos de infraestrutura', 'users' => 7, 'permissions' => 18, 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['group' => 'Comercial', 'description' => 'Acesso a propostas e clientes', 'users' => 9, 'permissions' => 8, 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['group' => 'Comercial', 'description' => 'Acesso a propostas e clientes', 'users' => 9, 'permissions' => 8, 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['group' => 'Comercial', 'description' => 'Acesso a propostas e clientes', 'users' => 9, 'permissions' => 8, 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['group' => 'Comercial', 'description' => 'Acesso a propostas e clientes', 'users' => 9, 'permissions' => 8, 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['group' => 'Comercial', 'description' => 'Acesso a propostas e clientes', 'users' => 9, 'permissions' => 8, 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['group' => 'Comercial', 'description' => 'Acesso a propostas e clientes', 'users' => 9, 'permissions' => 8, 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['group' => 'Comercial', 'description' => 'Acesso a propostas e clientes', 'users' => 9, 'permissions' => 8, 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['group' => 'Comercial', 'description' => 'Acesso a propostas e clientes', 'users' => 9, 'permissions' => 8, 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['group' => 'Comercial', 'description' => 'Acesso a propostas e clientes', 'users' => 9, 'permissions' => 8, 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
        ];

        $table = [
            'columns' => [
                ['key' => 'group', 'label' => 'Grupo', 'width' => '220px'],
                ['key' => 'description', 'label' => 'Descricao', 'width' => '380px'],
                ['key' => 'users', 'label' => 'Usuarios', 'width' => '140px'],
                ['key' => 'permissions', 'label' => 'Permissoes', 'width' => '160px'],
                ['key' => 'status', 'label' => 'Status', 'width' => '140px', 'variant' => 'badge'],
            ],
        ];

        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagination = new LengthAwarePaginator(
            array_slice($rows, ($currentPage - 1) * $perPage, $perPage),
            count($rows),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ],
        );

        $table['rows'] = $pagination->items();

        return view('group::groups.index', compact('table', 'pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('group::groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'in:1,0'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);

        // Mock de cadastro por enquanto (sem persistencia)
        $groupName = $validated['name'];

        return redirect()
            ->route('groups.index')
            ->with('status', "Grupo '{$groupName}' cadastrado com sucesso (mock).");
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('group::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('group::edit');
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
