<?php

namespace Modules\Development\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class DevelopmentController extends Controller
{
    public function index()
    {
        $rows = [
            ['code' => 'CMP_001', 'name' => 'Tabela customizavel com cabecalho fixo', 'module' => 'Development', 'category' => 'Listagem', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'updated_at' => '25/03/2026 14:30', 'owner' => 'Equipe CMS'],
            ['code' => 'CMP_002', 'name' => 'Formulario de cadastro de componentes', 'module' => 'Development', 'category' => 'Cadastro', 'status' => ['label' => 'Em revisao', 'tone' => 'warning'], 'updated_at' => '25/03/2026 13:10', 'owner' => 'Backoffice'],
            ['code' => 'CMP_003', 'name' => 'Drawer lateral para filtros rapidos', 'module' => 'Panel', 'category' => 'Layout', 'status' => ['label' => 'Planejado', 'tone' => 'neutral'], 'updated_at' => '24/03/2026 18:45', 'owner' => 'UX'],
            ['code' => 'CMP_004', 'name' => 'Bloco de indicadores com suporte a atalhos', 'module' => 'Dashboard', 'category' => 'Resumo', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'updated_at' => '24/03/2026 11:20', 'owner' => 'Equipe Dados'],
            ['code' => 'CMP_005', 'name' => 'Tabela com montagem 100% orientada pelo backend', 'module' => 'Development', 'category' => 'Infra UI', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'updated_at' => '23/03/2026 16:00', 'owner' => 'Equipe CMS'],
            ['code' => 'CMP_006', 'name' => 'Tela de configuracao de colunas e comportamentos', 'module' => 'Development', 'category' => 'Configuracao', 'status' => ['label' => 'Em revisao', 'tone' => 'warning'], 'updated_at' => '22/03/2026 09:40', 'owner' => 'Produto'],
            ['code' => 'CMP_007', 'name' => 'Wizard de criacao com passos guiados', 'module' => 'Development', 'category' => 'Cadastro', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'updated_at' => '21/03/2026 16:15', 'owner' => 'Equipe CMS'],
            ['code' => 'CMP_008', 'name' => 'Grade com acoes em massa e selecao', 'module' => 'Panel', 'category' => 'Listagem', 'status' => ['label' => 'Planejado', 'tone' => 'neutral'], 'updated_at' => '21/03/2026 10:05', 'owner' => 'Operacoes'],
            ['code' => 'CMP_009', 'name' => 'Banner de feedback para acoes do usuario', 'module' => 'UI', 'category' => 'Feedback', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'updated_at' => '20/03/2026 17:48', 'owner' => 'UX'],
            ['code' => 'CMP_010', 'name' => 'Modal de confirmacao padronizado', 'module' => 'UI', 'category' => 'Overlay', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'updated_at' => '20/03/2026 11:12', 'owner' => 'Equipe Front'],
            ['code' => 'CMP_011', 'name' => 'Tabela com colunas fixas e resumo lateral', 'module' => 'Development', 'category' => 'Infra UI', 'status' => ['label' => 'Em revisao', 'tone' => 'warning'], 'updated_at' => '19/03/2026 15:35', 'owner' => 'Produto'],
            ['code' => 'CMP_012', 'name' => 'Painel de filtros combinados', 'module' => 'Dashboard', 'category' => 'Filtro', 'status' => ['label' => 'Planejado', 'tone' => 'neutral'], 'updated_at' => '19/03/2026 09:50', 'owner' => 'Dados'],
            ['code' => 'CMP_013', 'name' => 'Listagem com destaque para status criticos', 'module' => 'Panel', 'category' => 'Monitoramento', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'updated_at' => '18/03/2026 18:00', 'owner' => 'NOC'],
            ['code' => 'CMP_014', 'name' => 'Cards de configuracao por contexto', 'module' => 'Development', 'category' => 'Configuracao', 'status' => ['label' => 'Em revisao', 'tone' => 'warning'], 'updated_at' => '18/03/2026 14:22', 'owner' => 'Produto'],
            ['code' => 'CMP_015', 'name' => 'Editor de schema para componentes', 'module' => 'Development', 'category' => 'Builder', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'updated_at' => '17/03/2026 13:37', 'owner' => 'Equipe CMS'],
            ['code' => 'CMP_016', 'name' => 'Tabela de auditoria com filtros rapidos', 'module' => 'Security', 'category' => 'Historico', 'status' => ['label' => 'Planejado', 'tone' => 'neutral'], 'updated_at' => '17/03/2026 09:18', 'owner' => 'Compliance'],
            ['code' => 'CMP_017', 'name' => 'Resumo de publicacoes pendentes', 'module' => 'Content', 'category' => 'Resumo', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'updated_at' => '16/03/2026 19:02', 'owner' => 'Editorial'],
            ['code' => 'CMP_018', 'name' => 'Timeline de alteracoes por registro', 'module' => 'Development', 'category' => 'Historico', 'status' => ['label' => 'Em revisao', 'tone' => 'warning'], 'updated_at' => '16/03/2026 12:46', 'owner' => 'Equipe CMS'],
            ['code' => 'CMP_019', 'name' => 'Widget de alertas por prioridade', 'module' => 'Dashboard', 'category' => 'Feedback', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'updated_at' => '15/03/2026 16:28', 'owner' => 'Operacoes'],
            ['code' => 'CMP_020', 'name' => 'Conjunto de acoes rapidas na linha', 'module' => 'Panel', 'category' => 'Acao', 'status' => ['label' => 'Planejado', 'tone' => 'neutral'], 'updated_at' => '15/03/2026 10:11', 'owner' => 'Equipe Front'],
        ];

        $table = [
            'title' => 'Componentes cadastrados',
            'description' => 'Estrutura montada dinamicamente a partir do backend.',
            'columns' => [
                ['key' => 'code', 'label' => 'Codigo', 'width' => '160px', 'variant' => 'code'],
                ['key' => 'name', 'label' => 'Nome do componente', 'width' => '340px'],
                ['key' => 'module', 'label' => 'Modulo', 'width' => '180px'],
                ['key' => 'category', 'label' => 'Categoria', 'width' => '180px'],
                ['key' => 'status', 'label' => 'Status', 'width' => '140px', 'variant' => 'badge'],
                ['key' => 'updated_at', 'label' => 'Atualizado em', 'width' => '160px'],
                ['key' => 'owner', 'label' => 'Responsavel', 'width' => '180px'],
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

        return view('development::index', compact('table', 'pagination'));
    }

    public function create()
    {
        return view('development::create');
    }

    public function builder()
    {
        $schema = [
            ['label' => 'Codigo', 'key' => 'code', 'type' => 'text', 'sticky' => true],
            ['label' => 'Nome do componente', 'key' => 'name', 'type' => 'text', 'sticky' => false],
            ['label' => 'Modulo', 'key' => 'module', 'type' => 'text', 'sticky' => false],
            ['label' => 'Categoria', 'key' => 'category', 'type' => 'text', 'sticky' => false],
            ['label' => 'Status', 'key' => 'status', 'type' => 'badge', 'sticky' => false],
            ['label' => 'Atualizado em', 'key' => 'updated_at', 'type' => 'datetime', 'sticky' => false],
        ];

        return view('development::builder', compact('schema'));
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
        return view('development::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('development::edit');
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
