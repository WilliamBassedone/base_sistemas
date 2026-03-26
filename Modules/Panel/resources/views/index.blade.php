@extends('panel::app')

@section('title', 'Dashboard')
@section('page_title', 'Painel')

@section('content')
    <div class="tw:space-y-6">
        <section class="tw:grid tw:grid-cols-1 md:tw:grid-cols-2 xl:tw:grid-cols-4 tw:gap-4">
            <div class="tw:bg-sky-500 tw:text-white tw:rounded-lg tw:p-4 tw:shadow-sm">
                <div class="tw:flex tw:items-start tw:justify-between">
                    <div>
                        <p class="tw:text-xs tw:uppercase tw:tracking-wide">Vendas Hoje</p>
                        <p class="tw:text-2xl tw:font-semibold">66,00</p>
                    </div>
                    <svg class="tw:w-8 tw:h-8 tw:opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 3h18v4H3z" />
                        <path d="M7 7v13" />
                        <path d="M17 7v13" />
                    </svg>
                </div>
                <p class="tw:text-xs tw:mt-3 tw:opacity-90">Ticket medio 5,00 - Real: 2 vendas</p>
            </div>

            <div class="tw:bg-amber-500 tw:text-white tw:rounded-lg tw:p-4 tw:shadow-sm">
                <div class="tw:flex tw:items-start tw:justify-between">
                    <div>
                        <p class="tw:text-xs tw:uppercase tw:tracking-wide">Vendas Periodo</p>
                        <p class="tw:text-2xl tw:font-semibold">68,00</p>
                    </div>
                    <svg class="tw:w-8 tw:h-8 tw:opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 3h18v18H3z" />
                        <path d="M7 14l3-3 3 2 4-5" />
                    </svg>
                </div>
                <p class="tw:text-xs tw:mt-3 tw:opacity-90">Ticket medio 5,27 - Real: 13 vendas</p>
            </div>

            <div class="tw:bg-emerald-500 tw:text-white tw:rounded-lg tw:p-4 tw:shadow-sm">
                <div class="tw:flex tw:items-start tw:justify-between">
                    <div>
                        <p class="tw:text-xs tw:uppercase tw:tracking-wide">Receber Hoje</p>
                        <p class="tw:text-2xl tw:font-semibold">30,00</p>
                    </div>
                    <svg class="tw:w-8 tw:h-8 tw:opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M12 3v18" />
                        <path d="M7 8l5-5 5 5" />
                    </svg>
                </div>
                <p class="tw:text-xs tw:mt-3 tw:opacity-90">Confirmado</p>
            </div>

            <div class="tw:bg-rose-500 tw:text-white tw:rounded-lg tw:p-4 tw:shadow-sm">
                <div class="tw:flex tw:items-start tw:justify-between">
                    <div>
                        <p class="tw:text-xs tw:uppercase tw:tracking-wide">Pagar Hoje</p>
                        <p class="tw:text-2xl tw:font-semibold">30,00</p>
                    </div>
                    <svg class="tw:w-8 tw:h-8 tw:opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M12 21V3" />
                        <path d="M7 16l5 5 5-5" />
                    </svg>
                </div>
                <p class="tw:text-xs tw:mt-3 tw:opacity-90">A vencer</p>
            </div>
        </section>

        <section class="tw:grid tw:grid-cols-1 xl:tw:grid-cols-3 tw:gap-6">
            <div class="tw:bg-white tw:rounded-lg tw:shadow-sm tw:border tw:border-slate-200">
                <div class="tw:px-4 tw:py-3 tw:border-b tw:border-slate-200">
                    <h2 class="tw:text-sm tw:font-semibold tw:text-slate-700">Clientes</h2>
                </div>
                <div class="tw:p-4">
                    <table class="tw:w-full tw:text-sm">
                        <thead class="tw:text-left tw:text-slate-500">
                            <tr>
                                <th class="tw:pb-2">Cliente</th>
                                <th class="tw:pb-2">Total</th>
                                <th class="tw:pb-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="tw:text-slate-700">
                            <tr class="tw:border-t tw:border-slate-100">
                                <td class="tw:py-2">Cliente 1</td>
                                <td class="tw:py-2">30,00</td>
                                <td class="tw:py-2">
                                    <span
                                        class="tw:inline-flex tw:items-center tw:rounded-full tw:bg-emerald-100 tw:text-emerald-700 tw:text-xs tw:px-2 tw:py-0.5">Venda</span>
                                </td>
                            </tr>
                            <tr class="tw:border-t tw:border-slate-100">
                                <td class="tw:py-2">Cliente 2</td>
                                <td class="tw:py-2">16,00</td>
                                <td class="tw:py-2">
                                    <span
                                        class="tw:inline-flex tw:items-center tw:rounded-full tw:bg-emerald-100 tw:text-emerald-700 tw:text-xs tw:px-2 tw:py-0.5">Venda</span>
                                </td>
                            </tr>
                            <tr class="tw:border-t tw:border-slate-100">
                                <td class="tw:py-2">Cliente 3</td>
                                <td class="tw:py-2">20,00</td>
                                <td class="tw:py-2">
                                    <span
                                        class="tw:inline-flex tw:items-center tw:rounded-full tw:bg-emerald-100 tw:text-emerald-700 tw:text-xs tw:px-2 tw:py-0.5">Venda</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tw:bg-white tw:rounded-lg tw:shadow-sm tw:border tw:border-slate-200 xl:tw:col-span-1">
                <div class="tw:px-4 tw:py-3 tw:border-b tw:border-slate-200">
                    <h2 class="tw:text-sm tw:font-semibold tw:text-slate-700">Vendas Diario</h2>
                </div>
                <div class="tw:p-6">
                    <div class="tw:flex tw:items-end tw:gap-4 tw:h-48">
                        <div class="tw:flex tw:flex-col tw:items-center tw:gap-2 tw:flex-1">
                            <div class="tw:w-full tw:bg-sky-200 tw:rounded tw:h-6"></div>
                            <span class="tw:text-xs tw:text-slate-500">31/08</span>
                        </div>
                        <div class="tw:flex tw:flex-col tw:items-center tw:gap-2 tw:flex-1">
                            <div class="tw:w-full tw:bg-sky-200 tw:rounded tw:h-10"></div>
                            <span class="tw:text-xs tw:text-slate-500">01/09</span>
                        </div>
                        <div class="tw:flex tw:flex-col tw:items-center tw:gap-2 tw:flex-1">
                            <div class="tw:w-full tw:bg-sky-300 tw:rounded tw:h-24"></div>
                            <span class="tw:text-xs tw:text-slate-500">02/09</span>
                        </div>
                        <div class="tw:flex tw:flex-col tw:items-center tw:gap-2 tw:flex-1">
                            <div class="tw:w-full tw:bg-sky-400 tw:rounded tw:h-36"></div>
                            <span class="tw:text-xs tw:text-slate-500">03/09</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tw:bg-white tw:rounded-lg tw:shadow-sm tw:border tw:border-slate-200">
                <div class="tw:px-4 tw:py-3 tw:border-b tw:border-slate-200">
                    <h2 class="tw:text-sm tw:font-semibold tw:text-slate-700">Resumo</h2>
                </div>
                <div class="tw:p-6 tw:flex tw:flex-col tw:items-center tw:gap-4">
                    <div
                        class="tw:w-32 tw:h-32 tw:rounded-full tw:border-8 tw:border-slate-200 tw:border-t-sky-500 tw:border-r-emerald-500 tw:border-b-amber-500 tw:border-l-rose-500">
                    </div>
                    <p class="tw:text-sm tw:text-slate-600 tw:text-center">Distribuicao de vendas por categoria</p>
                    <button
                        class="tw:inline-flex tw:items-center tw:rounded-md tw:bg-slate-900 tw:text-white tw:text-xs tw:px-3 tw:py-2 hover:tw:bg-slate-800">Ver detalhes</button>
                </div>
            </div>
        </section>
    </div>
@endsection
