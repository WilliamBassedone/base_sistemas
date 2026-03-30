<?php

namespace Modules\Group\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $rows = $this->sampleGroups();

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

    public function create()
    {
        return view('group::groups.create', [
            'group' => null,
            'menus' => $this->menus(),
            'actions' => $this->actions(),
        ]);
    }

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

    public function show($id)
    {
        return view('group::show');
    }

    public function edit($id)
    {
        $group = collect($this->sampleGroups())->firstWhere('code', (int) $id);

        abort_unless($group, 404);

        return view('group::groups.edit', [
            'group' => $group,
            'menus' => $this->menus(),
            'actions' => $this->actions(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'in:1,0'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);

        return redirect()
            ->route('groups.index')
            ->with('status', "Grupo '{$validated['name']}' atualizado com sucesso (mock).");
    }

    public function destroy($id) {}

    protected function sampleGroups(): array
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
            ['group' => 'Marketing', 'description' => 'Acesso a campanhas, midias e relatorios', 'users' => 6, 'permissions' => 13, 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['group' => 'Jurídico', 'description' => 'Acesso a contratos e documentos legais', 'users' => 4, 'permissions' => 9, 'status' => ['label' => 'Revisao', 'tone' => 'warning']],
            ['group' => 'Logística', 'description' => 'Acesso a entregas, estoque e expedição', 'users' => 11, 'permissions' => 15, 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['group' => 'Operações', 'description' => 'Acesso a fluxos operacionais do sistema', 'users' => 10, 'permissions' => 17, 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['group' => 'Qualidade', 'description' => 'Acesso a auditorias e conformidades', 'users' => 5, 'permissions' => 11, 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['group' => 'Atendimento', 'description' => 'Acesso ao suporte ao cliente e chamados', 'users' => 12, 'permissions' => 14, 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['group' => 'Produto', 'description' => 'Acesso ao catalogo, backlog e indicadores', 'users' => 7, 'permissions' => 16, 'status' => ['label' => 'Revisao', 'tone' => 'warning']],
            ['group' => 'Compliance', 'description' => 'Acesso a trilhas de auditoria e governança', 'users' => 3, 'permissions' => 10, 'status' => ['label' => 'Ativo', 'tone' => 'success']],
        ];

        return collect($rows)
            ->values()
            ->map(function (array $row, int $index) {
                $code = $index + 1;
                $groupKey = str($row['group'])->ascii()->lower()->slug('-')->toString();
                $active = ($row['status']['label'] ?? '') === 'Ativo' ? '1' : '0';
                $defaultPermissions = [
                    "menu.dashboard.view",
                    "menu.usuarios.view",
                    "menu.grupos.view",
                    "menu.{$groupKey}.view",
                ];

                if ($active === '1') {
                    $defaultPermissions[] = 'menu.grupos.update';
                    $defaultPermissions[] = 'menu.usuarios.toggle';
                }

                return array_merge($row, [
                    'code' => $code,
                    'edit_url' => route('groups.edit', $code),
                    'name' => $row['group'],
                    'slug' => $groupKey,
                    'is_active' => $active,
                    'permissions_list' => $defaultPermissions,
                ]);
            })
            ->all();
    }

    protected function menus(): array
    {
        return [
            'dashboard' => 'Dashboard',
            'professores' => 'Professores',
            'grupos' => 'Grupos',
            'usuarios' => 'Usuários',
        ];
    }

    protected function actions(): array
    {
        return [
            'view' => 'Visualizar',
            'create' => 'Cadastrar',
            'update' => 'Modificar',
            'delete' => 'Excluir',
            'toggle' => 'Ativar/Inativar',
            'download' => 'Download',
            'upload' => 'Upload',
        ];
    }
}
