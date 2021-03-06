(function() {
    'use strict';

    angular
        .module('MeuTucano')
        .component('menuTucano', {
            templateUrl: 'views/components/menu.html',
            controller: function($state) {
                var vm = this;

                /**
                 * Open submenu
                 *
                 * @param menu
                 */
                vm.openSub = function(menu) {
                    angular.forEach(vm.items, function(item) {
                        if (item !== menu)
                            item.subOpen = false;
                    });

                    menu.subOpen = !menu.subOpen;
                };

                /**
                 * Open inferior menu
                 *
                 * @param menu
                 * @param sub
                 */
                vm.openInf = function(menu, sub) {
                    angular.forEach(menu.sub, function(item) {
                        if (item !== sub)
                            item.subOpen = false;
                    });

                    sub.subOpen = !sub.subOpen;
                };

                /**
                 * Retrieve menu itens
                 * @type {*[]}
                 */
                vm.items = [
                    {
                        title: 'Painel',
                        sref: $state.href('app.dashboard'),
                        icon: 'fa-dashboard'
                    },
                    {
                        title: 'Pedidos',
                        sref: $state.href('app.pedidos.index'),
                        icon: 'fa-cubes',
                        roles: ['admin', 'gestor', 'atendimento', 'faturamento']
                    },
                    {
                        title: 'Faturamento',
                        sref: $state.href('app.faturamento.index'),
                        icon: 'fa-barcode',
                        roles: ['admin', 'faturamento']
                    },
                    {
                        title: 'Clientes',
                        sref: $state.href('app.clientes.index'),
                        icon: 'fa-users',
                        roles: ['admin', 'gestor']
                    },
                    {
                        title: 'Produtos',
                        icon: 'fa-dropbox',
                        sub: [
                            { title: 'Produtos', icon: 'fa-list', sref: $state.href('app.produtos.index') },
                            { title: 'Linhas', icon: 'fa-list-alt', sref: $state.href('app.produtos.linhas.index') },
                            { title: 'Marcas', icon: 'fa-list-alt', sref: $state.href('app.produtos.marcas.index') },
                            {
                                title: 'Mercado Livre',
                                icon: 'fa-share-alt',
                                sub: [
                                    { title: 'Anúncios', icon: 'fa-list', sref: $state.href('app.produtos.mercadolivre.ads.index') },
                                    { title: 'Templates', icon: 'fa-list', sref: $state.href('app.produtos.mercadolivre.templates.index') }
                                ]
                            },
                        ],
                        roles: ['admin', 'gestor']
                    },
                    // {
                    //     title: 'Movimentações',
                    //     icon: 'fa-exchange',
                    //     sub: [
                    //         { title: 'Entrada', icon: 'fa-mail-reply' },
                    //         { title: 'Saída', icon: 'fa-mail-forward' },
                    //         { title: 'Defeito', icon: 'fa-chain-broken' },
                    //         { title: 'Transportadoras', icon: 'fa-truck' },
                    //         { title: 'Fornecedores', icon: 'fa-building' },
                    //         { title: 'Formas de pagamento', icon: 'fa-money' },
                    //         { title: 'Operação fiscal', icon: 'fa-percent' }
                    //     ]
                    // },
                    // {
                    //     title: 'Financeiro',
                    //     icon: 'fa-money',
                    //     sub: [
                    //         { title: 'Contas a pagar/receber', icon: 'fa-credit-card' },
                    //         { title: 'Plano de contas', icon: 'fa-list' },
                    //     ]
                    // },
                    {
                        title: 'Estoque',
                        icon: 'fa-archive',
                        roles: ['admin', 'gestor'],
                        sub: [
                            {
                                title: 'Estoques',
                                icon: 'fa-archive',
                                sref: $state.href('app.estoque.index')
                            },
                            {
                                title: 'Retirada',
                                icon: 'fa-cart-arrow-down',
                                sref: $state.href('app.estoque.retirada.index')
                            },
                            {
                                title: 'Entrada',
                                icon: 'fa-arrow-up',
                                sref: $state.href('app.estoque.entrada.index')
                            },
                            {
                                title: 'Baixa',
                                icon: 'fa-arrow-down',
                                sref: $state.href('app.estoque.baixa.index')
                            },
                            {
                                title: 'Gerar IMEI',
                                icon: 'fa-barcode',
                                sref: $state.href('app.estoque.imei.gerar')
                            },
                            {
                                title: 'Consultar IMEI',
                                icon: 'fa-search',
                                sref: $state.href('app.estoque.imei.consultar')
                            },
                            {
                                title: 'Defeitos',
                                icon: 'fa-bug',
                                sref: $state.href('app.estoque.defeitos.index')
                            }
                        ]
                    },
                    {
                        title: 'Fornecedores',
                        icon: 'fa-building',
                        sref: $state.href('app.suppliers.index'),
                        roles: ['admin', 'gestor'],
                    },
                    {
                        title: 'Rastreios',
                        icon: 'fa-truck',
                        roles: ['admin', 'atendimento'],
                        sub: [
                            {
                                title: 'Rastreios importantes',
                                icon: 'fa-truck',
                                sref: $state.href('app.rastreios.importantes')
                            },
                            {
                                title: 'PI\'s pendentes' ,
                                icon: 'fa-warning',
                                sref: $state.href('app.rastreios.pis.pendentes')
                            },
                            {
                                title: 'Devoluções pendentes',
                                icon: 'fa-undo',
                                sref: $state.href('app.rastreios.devolucoes')
                            },
                            {
                                title: 'Rastreios monitorados',
                                icon: 'fa-video-camera ',
                                sref: $state.href('app.rastreios.monitorados')
                            }
                        ]
                    },
                    {
                        title: 'Relatórios',
                        icon: 'fa-pie-chart',
                        sub: [
                            {
                                title: 'Pedidos',
                                icon: 'fa-cubes',
                                sref: $state.href('app.relatorios.pedidos')
                            },
                            {
                                title: 'Produtos',
                                icon: 'fa-dropbox',
                                sref: $state.href('app.relatorios.produtos')
                            },
                            {
                                title: 'Retirada de estoque',
                                icon: 'fa-cart-arrow-down',
                                sref: $state.href('app.relatorios.retirada-estoque')
                            },
                            {
                                title: 'ICMS mensal',
                                icon: 'fa-file-pdf-o',
                                sref: $state.href('app.admin.icms')
                            },
                            {
                                title: 'Inventário',
                                icon: 'fa-archive',
                                sref: $state.href('app.relatorios.inventario')
                            }
                        ],
                        roles: ['admin', 'gestor']
                    },
                    // {
                    //     title: 'Configurações',
                    //     icon: 'fa-cog',
                    //     roles: ['admin'],
                    // },
                    {
                        title: 'Marketing',
                        icon: 'fa-bullhorn',
                        sub: [
                            {title: 'Template ML', icon: 'fa-clipboard', sref: $state.href('app.marketing.templateml')}
                        ],
                        roles: ['admin', 'marketing']
                    },
                    /*{
                        title: 'Integrações',
                        icon: 'fa-cogs',
                        sub: [
                        ],
                        roles: ['admin']
                    },*/
                    {
                        title: 'Interno',
                        icon: 'fa-desktop',
                        sub: [
                            // {
                            //     title: 'Dados da empresa',
                            //     icon: 'fa-info'
                            // },
                            // {
                            //     title: 'Impostos da nota',
                            //     icon: 'fa-percent'
                            // },
                            {
                                title: 'Usuários',
                                icon: 'fa-users',
                                sref: $state.href('app.interno.usuarios.index'),
                                roles: ['admin']
                            },
                            {
                                title: 'Minhas senhas',
                                icon: 'fa-key',
                                sref: $state.href('app.interno.senhas.minhas')
                            },
                            {
                                title: 'Sugestões',
                                icon: 'fa-comments-o',
                                sref: $state.href('app.interno.sugestoes.index'),
                                roles: ['admin']
                            }
                        ]
                    },
                    /**
                     * Gamification
                     */
                    {
                        title: 'Gamification',
                        icon: 'fa-gamepad',
                        sub: [
                            {
                                title: 'Ranking',
                                icon: 'fa-list-ol',
                                sref: $state.href('app.gamification.ranking'),
                                roles: ['admin|gestor|faturamento']
                            },
                            {
                                title: 'Perfil',
                                icon: 'fa-user',
                                sref: $state.href('app.gamification.perfil'),
                                roles: ['admin|gestor|faturamento']
                            },
                            {
                                title: 'Tarefas',
                                icon: 'fa-trophy',
                                sref: $state.href('app.gamification.tarefas'),
                                roles: ['admin']
                            },
                            {
                                title: 'Conquistas',
                                icon: 'fa-heart',
                                sref: $state.href('app.gamification.conquistas'),
                                roles: ['admin']
                            },
                            {
                                title: 'Recompensas',
                                icon: 'fa-cubes',
                                sref: $state.href('app.gamification.recompensas'),
                                roles: ['admin']
                            },
                            {
                                title: 'Trocas',
                                icon: 'fa-retweet',
                                sref: $state.href('app.gamification.trocas'),
                                roles: ['admin']
                            },
                            {
                                title: 'Solicitações',
                                icon: 'fa-question',
                                sref: $state.href('app.gamification.solicitacoes'),
                                roles: ['admin']
                            }
                        ]
                    }
                ];
            },
            controllerAs: 'Menu'
        });
})();
