<?php

return array(

    // Main menu
    'main'          => array(
        // Dashboard
        array(
            'title' => 'Dashboard',
            'path'  => 'index',
            'icon'  => theme()->getSvgIcon("demo1/media/icons/duotune/art/art002.svg", "svg-icon-2"),
        ),

        // Cadastros
        array(
            'title'      => 'Cadastros',
            'icon'       => theme()->getSvgIcon("demo1/media/icons/duotune/abstract/abs025.svg", "svg-icon-2"),
            'classes'    => array('item' => 'menu-accordion'),
            'attributes' => array(
                "data-kt-menu-trigger" => "click",
            ),
            'sub'        => array(
                'class' => 'menu-sub-accordion menu-active-bg',
                'items' => array(
                    array(
                        'title'  => 'Categorias de Gastos',
                        'path'   => 'expense-categories',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ),
                    array(
                        'title'  => 'Categorias de Recebimento',
                        'path'   => 'income-categories',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ),
                ),
            ),
        ),

        // Previsão Mensal
        array(
            'title' => 'Previsão Mensal',
            'path'  => 'monthly-forecasts',
            'icon'  => theme()->getSvgIcon("demo1/media/icons/duotune/finance/fin008.svg", "svg-icon-2"),
        ),

        // Lançamentos Mensais
        array(
            'title' => 'Lançamentos Mensais',
            'path'  => 'monthly-periods',
            'icon'  => theme()->getSvgIcon("demo1/media/icons/duotune/finance/fin002.svg", "svg-icon-2"),
        ),

        // Relatórios
        array(
            'title'      => 'Relatórios',
            'icon'       => theme()->getSvgIcon("demo1/media/icons/duotune/general/gen022.svg", "svg-icon-2"),
            'classes'    => array('item' => 'menu-accordion'),
            'attributes' => array(
                "data-kt-menu-trigger" => "click",
            ),
            'sub'        => array(
                'class' => 'menu-sub-accordion menu-active-bg',
                'items' => array(
                    array(
                        'title'  => 'Gastos x Previsão Mensal',
                        'path'   => 'reports/expenses-vs-forecast',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ),
                ),
            ),
        ),

    ),

    // Quick links (topbar)
    'quick_links'   => array(
        array(
            'title'    => 'Dashboard',
            'subtitle' => 'Visão geral',
            'path'     => 'index',
            'icon'     => theme()->getSvgIcon('demo1/media/icons/duotune/art/art002.svg', 'svg-icon-3x svg-icon-primary mb-2'),
        ),
        array(
            'title'    => 'Categorias de Gastos',
            'subtitle' => 'Cadastros',
            'path'     => 'expense-categories',
            'icon'     => theme()->getSvgIcon('demo1/media/icons/duotune/finance/fin006.svg', 'svg-icon-3x svg-icon-primary mb-2'),
        ),
        array(
            'title'    => 'Categorias de Recebimento',
            'subtitle' => 'Cadastros',
            'path'     => 'income-categories',
            'icon'     => theme()->getSvgIcon('demo1/media/icons/duotune/finance/fin007.svg', 'svg-icon-3x svg-icon-primary mb-2'),
        ),
        array(
            'title'    => 'Previsão Mensal',
            'subtitle' => 'Planejamento',
            'path'     => 'monthly-forecasts',
            'icon'     => theme()->getSvgIcon('demo1/media/icons/duotune/finance/fin008.svg', 'svg-icon-3x svg-icon-primary mb-2'),
        ),
        array(
            'title'    => 'Lançamentos Mensais',
            'subtitle' => 'Movimentação',
            'path'     => 'monthly-periods',
            'icon'     => theme()->getSvgIcon('demo1/media/icons/duotune/finance/fin002.svg', 'svg-icon-3x svg-icon-primary mb-2'),
        ),
        array(
            'title'    => 'Gastos x Previsão',
            'subtitle' => 'Relatórios',
            'path'     => 'reports/expenses-vs-forecast',
            'icon'     => theme()->getSvgIcon('demo1/media/icons/duotune/general/gen022.svg', 'svg-icon-3x svg-icon-primary mb-2'),
        ),
    ),

    // Horizontal menu (mesmos itens do menu principal)
    'horizontal'    => array(
        array(
            'title'   => 'Dashboard',
            'path'    => 'index',
            'classes' => array('item' => 'me-lg-1'),
        ),
        array(
            'title'   => 'Previsão Mensal',
            'path'    => 'monthly-forecasts',
            'classes' => array('item' => 'me-lg-1'),
        ),
        array(
            'title'   => 'Lançamentos Mensais',
            'path'    => 'monthly-periods',
            'classes' => array('item' => 'me-lg-1'),
        ),
        array(
            'title'   => 'Gastos x Previsão',
            'path'    => 'reports/expenses-vs-forecast',
            'classes' => array('item' => 'me-lg-1'),
        ),
    ),
);
