<?php

namespace Modules\Token\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TokenController extends Controller
{
    public function index()
    {
        $rows = $this->sampleTokens();

        $table = [
            'columns' => [
                ['key' => 'company_name', 'label' => 'Empresa', 'width' => '220px'],
                ['key' => 'name', 'label' => 'Nome', 'width' => '180px'],
                ['key' => 'token_secret_masked', 'label' => 'Token Secreto', 'width' => '320px', 'variant' => 'reveal-code', 'masked_key' => 'token_secret_masked', 'full_key' => 'token_secret'],
                ['key' => 'monthly_usage', 'label' => 'Uso no Mes', 'width' => '140px'],
                ['key' => 'usage_limit_label', 'label' => 'Limite de Uso', 'width' => '160px'],
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

        return view('token::tokens.index', compact('table', 'pagination'));
    }

    public function create()
    {
        return view('token::tokens.create', [
            'token' => null,
            'companies' => $this->companyOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateToken($request);

        return redirect()
            ->route('tokens.index')
            ->with('status', "Token '{$validated['name']}' cadastrado com sucesso (mock).");
    }

    public function show($id)
    {
        return view('token::show');
    }

    public function edit($id)
    {
        $token = collect($this->sampleTokens())->firstWhere('code', (int) $id);

        abort_unless($token, 404);

        return view('token::tokens.edit', [
            'token' => $token,
            'companies' => $this->companyOptions(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateToken($request);

        return redirect()
            ->route('tokens.index')
            ->with('status', "Token '{$validated['name']}' atualizado com sucesso (mock).");
    }

    public function destroy($id) {}

    protected function validateToken(Request $request): array
    {
        return $request->validate([
            'company_id' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:120'],
            'has_monthly_limit' => ['nullable', 'in:1'],
            'monthly_limit' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['required', 'in:1,0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);
    }

    protected function companyOptions(): array
    {
        return [
            '1' => 'Horizonte Educacional',
            '2' => 'Nova Era',
            '3' => 'Saber Integrado',
            '4' => 'Caminhos do Futuro',
            '5' => 'Viver Bem',
            '6' => 'Rede Alfa',
            '7' => 'Conecta Brasil',
            '8' => 'Aprender Mais',
        ];
    }

    protected function sampleTokens(): array
    {
        $rows = [
            ['company_id' => '1', 'company_name' => 'Horizonte Educacional', 'name' => 'ERP Principal', 'token_secret' => 'RH8C-3F91-APIK-7D22-9LQ5BE', 'monthly_usage' => 'R$ 49,40', 'usage_limit_label' => 'Sem limite', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'has_monthly_limit' => '0', 'monthly_limit' => null],
            ['company_id' => '2', 'company_name' => 'Nova Era', 'name' => 'Integracao Site', 'token_secret' => 'NW1A-8P20-SYNC-4M55-6TR91F', 'monthly_usage' => 'R$ 112,80', 'usage_limit_label' => 'R$ 500,00', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'has_monthly_limit' => '1', 'monthly_limit' => '500.00'],
            ['company_id' => '3', 'company_name' => 'Saber Integrado', 'name' => 'BI Comercial', 'token_secret' => 'SB4D-1K88-DATA-2F30-1PX7KT', 'monthly_usage' => 'R$ 18,10', 'usage_limit_label' => 'R$ 200,00', 'status' => ['label' => 'Revisao', 'tone' => 'warning'], 'has_monthly_limit' => '1', 'monthly_limit' => '200.00'],
            ['company_id' => '4', 'company_name' => 'Caminhos do Futuro', 'name' => 'Chatbot', 'token_secret' => 'CF6P-5N42-BOTX-6A14-8HG3XM', 'monthly_usage' => 'R$ 0,00', 'usage_limit_label' => 'Sem limite', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '0', 'monthly_limit' => null],
            ['company_id' => '5', 'company_name' => 'Viver Bem', 'name' => 'Aplicativo', 'token_secret' => 'VB2L-0Q73-APPM-9J61-5KR4QS', 'monthly_usage' => 'R$ 310,55', 'usage_limit_label' => 'R$ 800,00', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'has_monthly_limit' => '1', 'monthly_limit' => '800.00'],
            ['company_id' => '6', 'company_name' => 'Rede Alfa', 'name' => 'Portal Parceiro', 'token_secret' => 'RA7N-6W11-PART-3C29-7BV8YU', 'monthly_usage' => 'R$ 75,00', 'usage_limit_label' => 'R$ 150,00', 'status' => ['label' => 'Revisao', 'tone' => 'warning'], 'has_monthly_limit' => '1', 'monthly_limit' => '150.00'],
            ['company_id' => '7', 'company_name' => 'Conecta Brasil', 'name' => 'Robos Internos', 'token_secret' => 'CB5V-4T66-AUTO-8X44-0MN1ZH', 'monthly_usage' => 'R$ 12,90', 'usage_limit_label' => 'Sem limite', 'status' => ['label' => 'Ativo', 'tone' => 'success'], 'has_monthly_limit' => '0', 'monthly_limit' => null],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
            ['company_id' => '8', 'company_name' => 'Aprender Mais', 'name' => 'Webhook CRM', 'token_secret' => 'AM3R-9Y27-WEBH-5D90-2CL6PL', 'monthly_usage' => 'R$ 210,35', 'usage_limit_label' => 'R$ 300,00', 'status' => ['label' => 'Inativo', 'tone' => 'neutral'], 'has_monthly_limit' => '1', 'monthly_limit' => '300.00'],
        ];

        return collect($rows)
            ->values()
            ->map(function (array $row, int $index) {
                $code = $index + 1;

                return array_merge($row, [
                    'code' => $code,
                    'edit_url' => route('tokens.edit', $code),
                    'token_secret_masked' => $this->maskToken($row['token_secret']),
                    'is_active' => ($row['status']['label'] ?? '') === 'Ativo' ? '1' : '0',
                    'notes' => 'Token de exemplo para construcao do fluxo visual de integracoes via API.',
                ]);
            })
            ->all();
    }

    protected function maskToken(string $token): string
    {
        $prefix = substr($token, 0, 4);
        $suffix = substr($token, -3);

        return $prefix . str_repeat('*', max(strlen($token) - 7, 8)) . $suffix;
    }
}
