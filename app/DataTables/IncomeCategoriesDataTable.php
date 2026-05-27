<?php

namespace App\DataTables;

use App\Models\IncomeCategory;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class IncomeCategoriesDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->rawColumns(['is_active', 'action'])
            ->editColumn('description', function (IncomeCategory $model) {
                return $model->description
                    ? e(\Illuminate\Support\Str::limit($model->description, 80))
                    : '<span class="text-muted">—</span>';
            })
            ->editColumn('is_active', function (IncomeCategory $model) {
                return $model->is_active
                    ? '<span class="badge badge-light-success">'.__('Ativo').'</span>'
                    : '<span class="badge badge-light-danger">'.__('Inativo').'</span>';
            })
            ->editColumn('created_at', function (IncomeCategory $model) {
                return optional($model->created_at)->format('d/m/Y H:i');
            })
            ->addColumn('action', function (IncomeCategory $model) {
                return view('pages.income-categories._action-menu', compact('model'));
            })
            ->rawColumns(['description', 'is_active', 'action']);
    }

    public function query(IncomeCategory $model): Builder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('income-categories-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->stateSave(true)
            ->orderBy(0, 'desc')
            ->responsive()
            ->autoWidth(false)
            ->parameters([
                'scrollX'  => true,
                'language' => [
                    'emptyTable'     => __('Nenhuma categoria cadastrada'),
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
            Column::make('name')->title(__('Nome')),
            Column::make('description')->title(__('Descrição')),
            Column::make('is_active')->title(__('Status')),
            Column::make('created_at')->title(__('Criada em')),
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
        return 'CategoriasRecebimento_'.date('YmdHis');
    }
}
