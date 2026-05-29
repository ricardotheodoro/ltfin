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

    // Horizontal menu
    'horizontal'    => array(
        // Dashboard
        array(
            'title'   => 'Dashboard',
            'path'    => 'index',
            'classes' => array('item' => 'me-lg-1'),
        ),

        // Resources
        array(
            'title'      => 'Resources',
            'classes'    => array('item' => 'menu-lg-down-accordion me-lg-1', 'arrow' => 'd-lg-none'),
            'attributes' => array(
                'data-kt-menu-trigger'   => "click",
                'data-kt-menu-placement' => "bottom-start",
            ),
            'sub'        => array(
                'class' => 'menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px',
                'items' => array(
                    // Documentation
                    array(
                        'title' => 'Documentation',
                        'icon'  => theme()->getSvgIcon("demo1/media/icons/duotune/abstract/abs027.svg", "svg-icon-2"),
                        'path'  => 'documentation/getting-started/overview',
                    ),

                    // Changelog
                    array(
                        'title' => 'Changelog v'.theme()->getVersion(),
                        'icon'  => theme()->getSvgIcon("demo1/media/icons/duotune/general/gen005.svg", "svg-icon-2"),
                        'path'  => 'documentation/getting-started/changelog',
                    ),
                ),
            ),
        ),

        // Account
        array(
            'title'      => 'Account',
            'classes'    => array('item' => 'menu-lg-down-accordion me-lg-1', 'arrow' => 'd-lg-none'),
            'attributes' => array(
                'data-kt-menu-trigger'   => "click",
                'data-kt-menu-placement' => "bottom-start",
            ),
            'sub'        => array(
                'class' => 'menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px',
                'items' => array(
                    array(
                        'title'  => 'Overview',
                        'path'   => 'account/overview',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ),
                    array(
                        'title'  => 'Settings',
                        'path'   => 'account/settings',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ),
                    array(
                        'title'      => 'Security',
                        'path'       => '#',
                        'bullet'     => '<span class="bullet bullet-dot"></span>',
                        'attributes' => array(
                            'link' => array(
                                "title"             => "Coming soon",
                                "data-bs-toggle"    => "tooltip",
                                "data-bs-trigger"   => "hover",
                                "data-bs-dismiss"   => "click",
                                "data-bs-placement" => "right",
                            ),
                        ),
                    ),
                ),
            ),
        ),

        // System
        array(
            'title'      => 'System',
            'classes'    => array('item' => 'menu-lg-down-accordion me-lg-1', 'arrow' => 'd-lg-none'),
            'attributes' => array(
                'data-kt-menu-trigger'   => "click",
                'data-kt-menu-placement' => "bottom-start",
            ),
            'sub'        => array(
                'class' => 'menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px',
                'items' => array(
                    array(
                        'title'      => 'Settings',
                        'path'       => '#',
                        'bullet'     => '<span class="bullet bullet-dot"></span>',
                        'attributes' => array(
                            'link' => array(
                                "title"             => "Coming soon",
                                "data-bs-toggle"    => "tooltip",
                                "data-bs-trigger"   => "hover",
                                "data-bs-dismiss"   => "click",
                                "data-bs-placement" => "right",
                            ),
                        ),
                    ),
                    array(
                        'title'  => 'Audit Log',
                        'path'   => 'log/audit',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ),
                    array(
                        'title'  => 'System Log',
                        'path'   => 'log/system',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ),
                ),
            ),
        ),
    ),
);
