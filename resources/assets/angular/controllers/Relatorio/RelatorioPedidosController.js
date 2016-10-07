(function() {
    'use strict';

    angular
        .module('MeuTucano')
        .controller('RelatorioPedidosController', RelatorioPedidosController);

    function RelatorioPedidosController($location, $anchorScroll, Restangular) {
        var vm = this;

        vm.result = {};

        vm.gerar = function() {
            console.log(vm.params);

            /*Restangular.one('relatorios/pedido').customPOST({
                filter: vm.filter,
                group: vm.group,
                fields: vm.fields,
                order: vm.order,
                relation: vm.relation
            }).then(function(response) {
                vm.result = response;
                $location.hash('relatorio-resultado');
                $anchorScroll();
            });*/
        };

        vm.defaults = function() {
            vm.list = {};
            vm.list.cities = {};

            vm.params = {
                filter: [],
                group: '',
                fields: [],
                order: [],
                relation: []
            };

            vm.list.status = {
                0: 'Pendente',
                1: 'Pago',
                2: 'Enviado',
                3: 'Entregue',
                5: 'Cancelado'
            };

            vm.list.marketplace = {
                b2w: 'B2W',
                cnova: 'CNOVA',
                mercadolivre: 'Mercado Livre',
                walmart: 'Walmart',
                site: 'Site'
            };

            vm.list.fields = {};

            vm.list.fields.pedido = {
                codigo_api: 'Código',
                frete_valor: 'Frete valor',
                frete_metodo: 'Frete método',
                pagamento_metodo: 'Pagamento método',
                codigo_marketplace: 'Código marketplace',
                marketplace: 'Marketplace',
                operacao: 'Operação',
                total: 'Total',
                estimated_delivery: 'Entrega estimada',
                status: 'Status',
                protocolo: 'Protocolo cancelamento',
                segurado: 'Segurado',
                reembolso: 'Reembolso',
                priorizado: 'Priorizado',
                created_at: 'Data'
            };

            vm.list.fields.cliente = {
                'cliente.nome': 'Nome',
                'cliente.taxvat': 'Documento',
                'cliente.fone': 'Telefone',
                'cliente.email': 'E-mail',
                'cliente.created_at': 'Data'
            };

            vm.list.fields.endereco = {
                'endereco.rua': 'Rua',
                'endereco.numero': 'Número',
                'endereco.bairro': 'Bairro',
                'endereco.complemento': 'Complemento',
                'endereco.cidade': 'Cidade',
                'endereco.uf': 'UF',
                'endereco.cep': 'CEP'
            };

            vm.list.fields.produto = {
                'produtos.titulo': 'Título',
                'pedido_produtos.produto_sku': 'SKU',
                'pedido_produtos.imei': 'IMEI',
                'pedido_produtos.quantidade': 'Quantidade',
                'pedido_produtos.valor': 'Valor'
            };

            vm.list.fields.all = vm.list.fields.pedido;
            vm.list.fields.all = _.extend(vm.list.fields.all, vm.list.fields.cliente);
            vm.list.fields.all = _.extend(vm.list.fields.all, vm.list.fields.endereco);
            vm.list.fields.all = _.extend(vm.list.fields.all, vm.list.fields.produto);

            vm.params.filter = {
                total: {operator: 'BETWEEN'},
                'pedidos.created_at': {operator: 'BETWEEN'},
                estimated_delivery: {operator: 'BETWEEN'},
                status: {operator: 'IN', value: {}},
                marketplace: {operator: 'IN', value: {}},
                'cliente_enderecos.uf': {},
                'cliente_enderecos.cidade': {},
                'pedido_produtos.produto_sku': {operator: 'LIKE'},
                'pedido_produtos.imei': {operator: 'LIKE'},
                'pedido_produtos.quantidade': {operator: 'BETWEEN'},
                'pedido_produtos.valor': {operator: 'BETWEEN'}
            };

            vm.setFilters = {
                status: '',
                marketplace: ''
            };

            vm.relation = {
                cliente: false,
                endereco: false,
                produto: false
            };

            vm.setField = '';
            vm.setOrder = '';
        };

        vm.limpar = function() {
            vm.defaults();
            $location.hash('');
            $anchorScroll();
        };

        vm.load = function() {
            vm.defaults();
        };

        vm.load();

        vm.loadCities = function() {
            if (vm.filter['cliente_enderecos.uf'].value) {
                Restangular.one('pedidos/cidades', vm.filter['cliente_enderecos.uf'].value).getList().then(function(response) {
                    vm.list.cities = response;
                });
            }
        };

        /*vm.changeRelation = function() {
            var fields = _.clone(vm.listFieldsPedido);

            if (vm.relation.cliente === true) {
                fields = _.extend(fields, vm.listFieldsCliente);
            }

            if (vm.relation.endereco === true) {
                fields = _.extend(fields, vm.listFieldsEndereco);
            } else {
                vm.filter['cliente_enderecos.uf'] = {};
                vm.filter['cliente_enderecos.cidade'] = {};
            }

            if (vm.relation.produto === true) {
                fields = _.extend(fields, vm.listFieldsProduto);
            }

            vm.list.fields = fields;
        };*/

        vm.addFilter = function(key) {
            vm.params.filter[key].value[vm.setFilters[key]] = vm.list[key][vm.setFilters[key]];
            vm.setFilters[key] = '';
        };

        vm.removeFilter = function(key, index) {
            delete vm.params.filter[key].value[index];
        };

        vm.addOrder = function() {
            vm.params.order.push({
                label: vm.list.fields.all[vm.setOrder],
                name: vm.setOrder,
                order: 'asc'
            });

            vm.setOrder = '';
        };

        vm.changeOrder = function(index) {
            vm.params.order[index].order = (vm.params.order[index].order == 'asc') ? 'desc' : 'asc';
        };

        vm.removeOrder = function(index) {
            delete vm.params.order[index];
            vm.params.order = _.compact(vm.params.order);
        };

        vm.addField = function() {
            vm.params.fields.push({
                label: vm.list.fields.all[vm.setField],
                name: vm.setField
            });

            vm.setField = '';
        };

        vm.removeField = function(index) {
            delete vm.params.fields[index];
            vm.params.fields = _.compact(vm.params.fields);
        };
    }

})();