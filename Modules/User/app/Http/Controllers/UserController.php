<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $rows = $this->sampleUsers();

        $table = [
            'columns' => [
                ['key' => 'name', 'label' => 'Nome', 'width' => '240px'],
                ['key' => 'email', 'label' => 'E-mail', 'width' => '320px'],
                ['key' => 'group', 'label' => 'Grupo', 'width' => '200px'],
                ['key' => 'last_access', 'label' => 'Ultimo acesso', 'width' => '180px'],
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

        return view('user::users.index', compact('table', 'pagination'));
    }

    public function create()
    {
        return view('user::users.create', array_merge(
            $this->formOptions(),
            ['user' => null, 'mode' => 'create']
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'cpf' => ['required', 'string', 'max:20'],
            'rg' => ['nullable', 'string', 'max:20'],
            'issuing_agency' => ['nullable', 'string', 'max:30'],
            'birth_date' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'mobile' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:160'],
            'address_number' => ['required', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:80'],
            'neighborhood' => ['required', 'string', 'max:80'],
            'state' => ['required', 'string', 'size:2'],
            'city' => ['required', 'string', 'max:80'],
            'postal_code' => ['required', 'string', 'max:12'],
            'email' => ['required', 'email', 'max:160'],
            'group' => ['required', 'string', 'max:80'],
            'is_active' => ['required', 'in:1,0'],
            'password' => ['required', 'string', 'min:8', 'max:100'],
            'password_confirmation' => ['required', 'same:password'],
        ]);

        $userName = $validated['name'];

        return redirect()
            ->route('users.index')
            ->with('status', "Usuario '{$userName}' cadastrado com sucesso (mock).");
    }

    public function show($id)
    {
        $user = collect($this->sampleUsers())->firstWhere('code', (int) $id);

        abort_unless($user, 404);

        return view('user::users.show', array_merge(
            $this->formOptions(),
            ['user' => $user, 'mode' => 'show']
        ));
    }

    public function edit($id)
    {
        $user = collect($this->sampleUsers())->firstWhere('code', (int) $id);

        abort_unless($user, 404);

        return view('user::users.edit', array_merge(
            $this->formOptions(),
            ['user' => $user, 'mode' => 'edit']
        ));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'cpf' => ['required', 'string', 'max:20'],
            'rg' => ['nullable', 'string', 'max:20'],
            'issuing_agency' => ['nullable', 'string', 'max:30'],
            'birth_date' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'mobile' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:160'],
            'address_number' => ['required', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:80'],
            'neighborhood' => ['required', 'string', 'max:80'],
            'state' => ['required', 'string', 'size:2'],
            'city' => ['required', 'string', 'max:80'],
            'postal_code' => ['required', 'string', 'max:12'],
            'email' => ['required', 'email', 'max:160'],
            'group' => ['required', 'string', 'max:80'],
            'is_active' => ['required', 'in:1,0'],
            'password' => ['nullable', 'string', 'min:8', 'max:100'],
            'password_confirmation' => ['nullable', 'same:password'],
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', "Usuario '{$validated['name']}' atualizado com sucesso (mock).");
    }

    public function destroy($id) {}

    protected function formOptions(): array
    {
        return [
            'groups' => [
                'administradores' => 'Administradores',
                'financeiro' => 'Financeiro',
                'secretaria' => 'Secretaria',
                'suporte' => 'Suporte',
                'rh' => 'RH',
                'compras' => 'Compras',
                'ti' => 'TI',
                'comercial' => 'Comercial',
            ],
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
                'sao-paulo' => 'Sao Paulo',
                'rio-de-janeiro' => 'Rio de Janeiro',
                'belo-horizonte' => 'Belo Horizonte',
                'curitiba' => 'Curitiba',
                'porto-alegre' => 'Porto Alegre',
                'salvador' => 'Salvador',
                'fortaleza' => 'Fortaleza',
                'recife' => 'Recife',
                'goiania' => 'Goiania',
                'campinas' => 'Campinas',
            ],
        ];
    }

    protected function sampleUsers(): array
    {
        $rows = [
            ['name' => 'Ana Martins', 'email' => 'ana.martins@cms.test', 'group' => 'Administradores', 'last_access' => 'Hoje, 08:45', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Bruno Costa', 'email' => 'bruno.costa@cms.test', 'group' => 'Financeiro', 'last_access' => 'Hoje, 09:12', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Carla Souza', 'email' => 'carla.souza@cms.test', 'group' => 'Secretaria', 'last_access' => 'Ontem, 17:30', 'status' => ['label' => 'Revisao', 'tone' => 'warning']],
            ['name' => 'Diego Alves', 'email' => 'diego.alves@cms.test', 'group' => 'Suporte', 'last_access' => 'Hoje, 07:58', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Eduarda Lima', 'email' => 'eduarda.lima@cms.test', 'group' => 'RH', 'last_access' => 'Ontem, 14:05', 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['name' => 'Felipe Rocha', 'email' => 'felipe.rocha@cms.test', 'group' => 'Compras', 'last_access' => 'Hoje, 10:21', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Gabriela Melo', 'email' => 'gabriela.melo@cms.test', 'group' => 'TI', 'last_access' => 'Hoje, 06:47', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Henrique Nunes', 'email' => 'henrique.nunes@cms.test', 'group' => 'Comercial', 'last_access' => 'Ontem, 19:03', 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['name' => 'Isabela Fernandes', 'email' => 'isabela.fernandes@cms.test', 'group' => 'Administradores', 'last_access' => 'Hoje, 11:02', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Joao Pedro Ribeiro', 'email' => 'joao.ribeiro@cms.test', 'group' => 'Financeiro', 'last_access' => 'Hoje, 11:18', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Kamila Araujo', 'email' => 'kamila.araujo@cms.test', 'group' => 'Secretaria', 'last_access' => 'Ontem, 16:44', 'status' => ['label' => 'Revisao', 'tone' => 'warning']],
            ['name' => 'Leonardo Pires', 'email' => 'leonardo.pires@cms.test', 'group' => 'Suporte', 'last_access' => 'Hoje, 05:59', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Mariana Duarte', 'email' => 'mariana.duarte@cms.test', 'group' => 'RH', 'last_access' => 'Ontem, 13:20', 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['name' => 'Nicolas Vieira', 'email' => 'nicolas.vieira@cms.test', 'group' => 'Compras', 'last_access' => 'Hoje, 08:03', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Olivia Castro', 'email' => 'olivia.castro@cms.test', 'group' => 'TI', 'last_access' => 'Hoje, 09:41', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Paulo Henrique', 'email' => 'paulo.henrique@cms.test', 'group' => 'Comercial', 'last_access' => 'Ontem, 20:15', 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['name' => 'Quezia Moraes', 'email' => 'quezia.moraes@cms.test', 'group' => 'Administradores', 'last_access' => 'Hoje, 07:11', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Rafael Teixeira', 'email' => 'rafael.teixeira@cms.test', 'group' => 'Financeiro', 'last_access' => 'Hoje, 10:56', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Sabrina Lopes', 'email' => 'sabrina.lopes@cms.test', 'group' => 'Secretaria', 'last_access' => 'Ontem, 18:12', 'status' => ['label' => 'Revisao', 'tone' => 'warning']],
            ['name' => 'Thiago Barros', 'email' => 'thiago.barros@cms.test', 'group' => 'Suporte', 'last_access' => 'Hoje, 06:35', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Ursula Menezes', 'email' => 'ursula.menezes@cms.test', 'group' => 'RH', 'last_access' => 'Ontem, 12:09', 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['name' => 'Vinicius Freitas', 'email' => 'vinicius.freitas@cms.test', 'group' => 'Compras', 'last_access' => 'Hoje, 09:27', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Wesley Gomes', 'email' => 'wesley.gomes@cms.test', 'group' => 'TI', 'last_access' => 'Hoje, 08:16', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Yasmin Cardoso', 'email' => 'yasmin.cardoso@cms.test', 'group' => 'Comercial', 'last_access' => 'Ontem, 21:02', 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['name' => 'Zuleica Batista', 'email' => 'zuleica.batista@cms.test', 'group' => 'Administradores', 'last_access' => 'Hoje, 07:49', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Aline Fagundes', 'email' => 'aline.fagundes@cms.test', 'group' => 'Financeiro', 'last_access' => 'Hoje, 08:28', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Bernardo Siqueira', 'email' => 'bernardo.siqueira@cms.test', 'group' => 'Secretaria', 'last_access' => 'Ontem, 15:17', 'status' => ['label' => 'Revisao', 'tone' => 'warning']],
            ['name' => 'Cecilia Prado', 'email' => 'cecilia.prado@cms.test', 'group' => 'Suporte', 'last_access' => 'Hoje, 10:04', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Danilo Moreira', 'email' => 'danilo.moreira@cms.test', 'group' => 'RH', 'last_access' => 'Ontem, 11:31', 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['name' => 'Elaine Tavares', 'email' => 'elaine.tavares@cms.test', 'group' => 'Compras', 'last_access' => 'Hoje, 09:54', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Fabio Rezende', 'email' => 'fabio.rezende@cms.test', 'group' => 'TI', 'last_access' => 'Hoje, 06:58', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Giovana Peixoto', 'email' => 'giovana.peixoto@cms.test', 'group' => 'Comercial', 'last_access' => 'Ontem, 18:49', 'status' => ['label' => 'Inativo', 'tone' => 'neutral']],
            ['name' => 'Heitor Sales', 'email' => 'heitor.sales@cms.test', 'group' => 'Administradores', 'last_access' => 'Hoje, 11:33', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
            ['name' => 'Iris Albuquerque', 'email' => 'iris.albuquerque@cms.test', 'group' => 'Financeiro', 'last_access' => 'Hoje, 07:22', 'status' => ['label' => 'Ativo', 'tone' => 'success']],
        ];

        $states = array_keys($this->formOptions()['states']);
        $cities = array_values($this->formOptions()['cities']);

        return collect($rows)
            ->values()
            ->map(function (array $row, int $index) use ($states, $cities) {
                $code = $index + 1;
                $groupSlug = str($row['group'])->ascii()->lower()->slug('-')->toString();
                $active = ($row['status']['label'] ?? '') === 'Ativo' ? '1' : '0';

                return array_merge($row, [
                    'code' => $code,
                    'show_url' => route('users.show', $code),
                    'edit_url' => route('users.edit', $code),
                    'cpf' => sprintf('%03d.%03d.%03d-%02d', $code, ($code + 137) % 1000, ($code + 274) % 1000, ($code + 11) % 100),
                    'rg' => sprintf('%02d.%03d.%03d-%01d', ($code + 10) % 100, ($code + 200) % 1000, ($code + 500) % 1000, $code % 10),
                    'issuing_agency' => 'SSP',
                    'birth_date' => now()->subYears(22 + ($code % 18))->format('Y-m-d'),
                    'phone' => sprintf('(11) 3%03d-%04d', 100 + $code, 2000 + $code),
                    'mobile' => sprintf('(11) 9%04d-%04d', 1000 + $code, 3000 + $code),
                    'address' => 'Rua Exemplo ' . $code,
                    'address_number' => (string) (100 + $code),
                    'complement' => 'Sala ' . (($code % 15) + 1),
                    'neighborhood' => 'Centro',
                    'state' => $states[$index % count($states)],
                    'city' => $cities[$index % count($cities)],
                    'postal_code' => sprintf('%05d-%03d', 10000 + $code, 100 + ($code % 900)),
                    'group_key' => $groupSlug,
                    'is_active' => $active,
                ]);
            })
            ->all();
    }
}
