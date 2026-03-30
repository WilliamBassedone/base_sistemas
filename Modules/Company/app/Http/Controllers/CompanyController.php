<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyController extends Controller
{
    public function index()
    {
        $rows = $this->sampleCompanies();

        $table = [
            'columns' => [
                ['key' => 'company_name', 'label' => 'Empresa', 'width' => '240px'],
                ['key' => 'trade_name', 'label' => 'Nome Fantasia', 'width' => '220px'],
                ['key' => 'cnpj', 'label' => 'CNPJ', 'width' => '180px'],
                ['key' => 'city', 'label' => 'Cidade', 'width' => '180px'],
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

        return view('company::companies.index', compact('table', 'pagination'));
    }

    public function create()
    {
        return view('company::companies.create', array_merge(
            $this->formOptions(),
            ['company' => null]
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCompany($request, false);

        return redirect()
            ->route('companies.index')
            ->with('status', "Empresa '{$validated['company_name']}' cadastrada com sucesso (mock).");
    }

    public function show($id)
    {
        return view('company::show');
    }

    public function edit($id)
    {
        $company = collect($this->sampleCompanies())->firstWhere('code', (int) $id);

        abort_unless($company, 404);

        return view('company::companies.edit', array_merge(
            $this->formOptions(),
            ['company' => $company]
        ));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateCompany($request, true);

        return redirect()
            ->route('companies.index')
            ->with('status', "Empresa '{$validated['company_name']}' atualizada com sucesso (mock).");
    }

    public function destroy($id) {}

    protected function validateCompany(Request $request, bool $isEdit): array
    {
        return $request->validate([
            'company_name' => ['required', 'string', 'max:160'],
            'trade_name' => ['required', 'string', 'max:120'],
            'cnpj' => ['required', 'string', 'max:20'],
            'state_registration' => ['nullable', 'string', 'max:30'],
            'municipal_registration' => ['nullable', 'string', 'max:30'],
            'responsible_name' => ['required', 'string', 'max:120'],
            'responsible_email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:20'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:160'],
            'address_number' => ['required', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:80'],
            'neighborhood' => ['required', 'string', 'max:80'],
            'state' => ['required', 'string', 'size:2'],
            'city' => ['required', 'string', 'max:80'],
            'postal_code' => ['required', 'string', 'max:12'],
            'is_active' => ['required', 'in:1,0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);
    }

    protected function formOptions(): array
    {
        return [
            'states' => [
                'AC' => 'Acre',
                'AL' => 'Alagoas',
                'AP' => 'Amapa',
                'AM' => 'Amazonas',
                'BA' => 'Bahia',
                'CE' => 'Ceara',
                'DF' => 'Distrito Federal',
                'ES' => 'Espirito Santo',
                'GO' => 'Goias',
                'MA' => 'Maranhao',
                'MT' => 'Mato Grosso',
                'MS' => 'Mato Grosso do Sul',
                'MG' => 'Minas Gerais',
                'PA' => 'Para',
                'PB' => 'Paraiba',
                'PR' => 'Parana',
                'PE' => 'Pernambuco',
                'PI' => 'Piaui',
                'RJ' => 'Rio de Janeiro',
                'RN' => 'Rio Grande do Norte',
                'RS' => 'Rio Grande do Sul',
                'RO' => 'Rondonia',
                'RR' => 'Roraima',
                'SC' => 'Santa Catarina',
                'SP' => 'Sao Paulo',
                'SE' => 'Sergipe',
                'TO' => 'Tocantins',
            ],
            'cities' => [
                'Sao Paulo' => 'Sao Paulo',
                'Rio de Janeiro' => 'Rio de Janeiro',
                'Belo Horizonte' => 'Belo Horizonte',
                'Curitiba' => 'Curitiba',
                'Porto Alegre' => 'Porto Alegre',
                'Salvador' => 'Salvador',
                'Fortaleza' => 'Fortaleza',
                'Recife' => 'Recife',
                'Goiania' => 'Goiania',
                'Campinas' => 'Campinas',
            ],
        ];
    }

    protected function sampleCompanies(): array
    {
        $rows = [
            ['company_name' => 'Instituto Horizonte Educacional Ltda', 'trade_name' => 'Horizonte Educacional', 'cnpj' => '12.345.678/0001-90', 'city' => 'Sao Paulo', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['company_name' => 'Centro de Formacao Nova Era SA', 'trade_name' => 'Nova Era', 'cnpj' => '98.765.432/0001-10', 'city' => 'Rio de Janeiro', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['company_name' => 'Grupo Saber Integrado Ltda', 'trade_name' => 'Saber Integrado', 'cnpj' => '45.120.332/0001-77', 'city' => 'Belo Horizonte', 'status' => ['label' => 'Revisao', 'tone' => 'warning']],
            ['company_name' => 'Editora Caminhos do Futuro Ltda', 'trade_name' => 'Caminhos do Futuro', 'cnpj' => '27.444.890/0001-55', 'city' => 'Curitiba', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['company_name' => 'Colegio Viver Bem Ltda', 'trade_name' => 'Viver Bem', 'cnpj' => '63.908.110/0001-24', 'city' => 'Porto Alegre', 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['company_name' => 'Rede Alfa de Ensino Ltda', 'trade_name' => 'Rede Alfa', 'cnpj' => '33.210.987/0001-81', 'city' => 'Salvador', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['company_name' => 'Faculdade Conecta Brasil Ltda', 'trade_name' => 'Conecta Brasil', 'cnpj' => '54.901.776/0001-42', 'city' => 'Fortaleza', 'status' => ['label' => 'Revisao', 'tone' => 'warning']],
            ['company_name' => 'Associacao Aprender Mais', 'trade_name' => 'Aprender Mais', 'cnpj' => '07.884.223/0001-09', 'city' => 'Recife', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['company_name' => 'Fundacao Polo Tecnico Educacional', 'trade_name' => 'Polo Tecnico', 'cnpj' => '81.443.920/0001-37', 'city' => 'Goiania', 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['company_name' => 'Universo Cursos Livres Ltda', 'trade_name' => 'Universo Cursos', 'cnpj' => '19.220.144/0001-66', 'city' => 'Campinas', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['company_name' => 'Nucleo Integrado de Ensino Ltda', 'trade_name' => 'Nucleo Integrado', 'cnpj' => '20.650.880/0001-13', 'city' => 'Sao Paulo', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['company_name' => 'Academia Criativa de Formacao Ltda', 'trade_name' => 'Academia Criativa', 'cnpj' => '74.511.201/0001-08', 'city' => 'Rio de Janeiro', 'status' => ['label' => 'Revisao', 'tone' => 'warning']],
            ['company_name' => 'Instituto Progresso Social Ltda', 'trade_name' => 'Progresso Social', 'cnpj' => '14.321.000/0001-95', 'city' => 'Belo Horizonte', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['company_name' => 'Escola Livre do Conhecimento Ltda', 'trade_name' => 'Livre do Conhecimento', 'cnpj' => '88.700.550/0001-71', 'city' => 'Curitiba', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['company_name' => 'Plataforma Saber Digital Ltda', 'trade_name' => 'Saber Digital', 'cnpj' => '39.006.430/0001-58', 'city' => 'Porto Alegre', 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['company_name' => 'Editora Educar para Todos Ltda', 'trade_name' => 'Educar para Todos', 'cnpj' => '61.203.114/0001-20', 'city' => 'Salvador', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
        ];

        return collect($rows)
            ->values()
            ->map(function (array $row, int $index) {
                $code = $index + 1;
                $state = $index % 2 === 0 ? 'SP' : 'RJ';
                $city = $row['city'];

                return array_merge($row, [
                    'code' => $code,
                    'edit_url' => route('companies.edit', $code),
                    'state_registration' => '123.456.789.000',
                    'municipal_registration' => '556677',
                    'responsible_name' => 'Responsavel ' . $code,
                    'responsible_email' => "contato{$code}@empresa.test",
                    'phone' => '(11) 3333-4455',
                    'mobile' => '(11) 98888-7766',
                    'address' => 'Avenida Central',
                    'address_number' => (string) (100 + $code),
                    'complement' => $code % 3 === 0 ? 'Sala ' . $code : '',
                    'neighborhood' => 'Centro',
                    'state' => $state,
                    'postal_code' => '01001-000',
                    'is_active' => ($row['status']['label'] ?? '') === 'Ativo' ? '1' : '0',
                    'notes' => 'Cadastro de exemplo para construcao inicial do layout de empresas.',
                ]);
            })
            ->all();
    }
}
