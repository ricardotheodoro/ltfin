<?php

use App\Core\Adapters\Theme;

return array(
    // Refer to config/global/menu.php

    // Horizontal menu
    'horizontal' => array(
        // Dashboard
        array(
            'title'   => 'Dashboard',
            'path'    => 'index',
            'classes' => array('item' => 'me-lg-1'),
        ),

        // Lançamentos mensais
        array(
            'title'   => 'Lançamentos Mensais',
            'path'    => 'monthly-periods',
            'classes' => array('item' => 'me-lg-1'),
        ),

        // Previsão mensal
        array(
            'title'   => 'Previsão Mensal',
            'path'    => 'monthly-forecasts',
            'classes' => array('item' => 'me-lg-1'),
        ),

        // Relatórios
        array(
            'title'      => 'Relatórios',
            'classes'    => array('item' => 'menu-lg-down-accordion me-lg-1', 'arrow' => 'd-lg-none'),
            'attributes' => array(
                'data-kt-menu-trigger'   => 'click',
                'data-kt-menu-placement' => 'bottom-start',
            ),
            'sub'        => array(
                'class' => 'menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-250px',
                'items' => array(
                    array(
                        'title' => 'Gastos x Previsão Mensal',
                        'icon'  => theme()->getSvgIcon('demo1/media/icons/duotune/general/gen022.svg', 'svg-icon-2'),
                        'path'  => 'reports/expenses-vs-forecast',
                    ),
                ),
            ),
        ),

        // Cadastros
        array(
            'title'      => 'Cadastros',
            'classes'    => array('item' => 'menu-lg-down-accordion me-lg-1', 'arrow' => 'd-lg-none'),
            'attributes' => array(
                'data-kt-menu-trigger'   => 'click',
                'data-kt-menu-placement' => 'bottom-start',
            ),
            'sub'        => array(
                'class' => 'menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px',
                'items' => array(
                    array(
                        'title' => 'Categorias de Gastos',
                        'icon'  => theme()->getSvgIcon('demo1/media/icons/duotune/finance/fin006.svg', 'svg-icon-2'),
                        'path'  => 'expense-categories',
                    ),
                    array(
                        'title' => 'Categorias de Recebimento',
                        'icon'  => theme()->getSvgIcon('demo1/media/icons/duotune/finance/fin007.svg', 'svg-icon-2'),
                        'path'  => 'income-categories',
                    ),
                ),
            ),
        ),

    ),
);
