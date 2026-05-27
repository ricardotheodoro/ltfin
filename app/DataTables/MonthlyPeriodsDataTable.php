<?php

namespace App\DataTables;

use App\Models\MonthlyEntry;
use App\Models\MonthlyPeriod;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MonthlyPeriodsDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('period_label', function (MonthlyPeriod $model) {
                return '<span class="fw-bold text-gray-800">'.e($model->label).'</span>';
            })
            ->editColumn('expense_sum', function (MonthlyPeriod $model) {
                return '<span class="text-danger fw-bold">'
                    .e(MonthlyPeriod::formatMoney((float) ($model->expense_sum ?? 0)))
                    .'</span>';
            })
            ->editColumn('income_sum', function (MonthlyPeriod $model) {
                return '<span class="text-success fw-bold">'
                    .e(MonthlyPeriod::formatMoney((float) ($model->income_sum ?? 0)))
                    .'</span>';
            })
            ->addColumn('balance', function (MonthlyPeriod $model) {
                $balance = (float) ($model->income_sum ?? 0) - (float) ($model->expense_sum ?? 0);
                $class   = $balance >= 0 ? 'text-success' : 'text-danger';

                return '<span class="fw-bolder '.$class.'">'.e(MonthlyPeriod::formatMoney($balance)).'</span>';
            })
            ->editColumn('entries_count', function (MonthlyPeriod $model) {
                return (int) ($model->entries_count ?? 0);
            })
            ->editColumn('is_closed', function (MonthlyPeriod $model) {
                return $model->is_closed
                    ? '<span class="badge badge-light-warning">'.__('Fechado').'</span>'
                    : '<span class="badge badge-light-primary">'.__('Aberto').'</span>';
            })
            ->editColumn('created_at', function (MonthlyPeriod $model) {
                return optional($model->created_at)->format('d/m/Y H:i');
            })
            ->addColumn('action', function (MonthlyPeriod $model) {
                return view('pages.monthly-periods._action-menu', compact('model'));
            })
            ->rawColumns(['period_label', 'expense_sum', 'income_sum', 'balance', 'is_closed', 'action']);
    }

    public function query(MonthlyPeriod $model): Builder
    {
        return $model->newQuery()
            ->withCount('entries')
            ->withSum(['entries as expense_sum' => function ($query) {
                $query->where('type', MonthlyEntry::TYPE_EXPENSE);
            }], 'amount')
            ->withSum(['entries as income_sum' => function ($query) {
                $query->where('type', MonthlyEntry::TYPE_INCOME);
            }], 'amount');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('monthly-periods-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(true)
            ->orderBy(0, 'desc')
            ->responsive()
            ->autoWidth(false)
            ->parameters([
                'scrollX'  => true,
                'language' => [
                    'emptyTable'     => __('Nenhum mês cadastrado'),
                    'info'           => __('Mostrando _START_ a _END_ de _TOTAL_ registros'),
                    'infoEmpty'      => __('Mostrando 0 a 0 de 0 registros'),
                    'infoFiltered'   => __('(filtrado de _MAX_ registros)'),
                    'lengthMenu'     => __('Mostrar _MENU_ registros'),
                    'loadingRecords' => __('Carregando...'),
                    'processing'     => __('Processando...'),
                    'search'         => __('Buscar:'),
                    'zeroRecords'    => __('Nenhum registro encontrado'),
                    'paginate'       => [
                        'first'    => __('Primeira'),
                        'last'     => __('Última'),
                        'next'     => __('Próxima'),
                        'previous' => __('Anterior'),
                    ],
                ],
            ])
            ->addTableClass('align-middle table-row-dashed fs-6 gy-5');
    }

    protected function getColumns(): array
    {
        return [
            Column::make('id')->title('#'),
            Column::computed('period_label')->title(__('Período'))->orderable(false)->searchable(false),
            Column::make('expense_sum')->title(__('Gastos'))->orderable(false)->searchable(false),
            Column::make('income_sum')->title(__('Recebíveis'))->orderable(false)->searchable(false),
            Column::computed('balance')->title(__('Saldo'))->orderable(false)->searchable(false),
            Column::make('entries_count')->title(__('Lançamentos'))->orderable(false)->searchable(false),
            Column::make('is_closed')->title(__('Status')),
            Column::make('created_at')->title(__('Criado em')),
            Column::computed('action')
                ->title(__('Ações'))
                ->exportable(false)
                ->printable(false)
                ->addClass('text-end')
                ->responsivePriority(-1),
        ];
    }

    protected function filename(): string
    {
        return 'LancamentosMensais_'.date('YmdHis');
    }
}
