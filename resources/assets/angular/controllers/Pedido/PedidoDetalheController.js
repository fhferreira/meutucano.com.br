(function() {
    'use strict';

    angular
        .module('MeuTucano')
        .controller('PedidoDetalheController', PedidoDetalheController);

    function PedidoDetalheController($rootScope, $state, $stateParams, ngDialog, SweetAlert, toaster, Pedido, RastreioHelper, NotaHelper, ClienteEnderecoHelper, PedidoHelper, ClienteHelper) {
        var vm = this;

        vm.pedido_id  = $stateParams.id;
        vm.pedido     = {};
        vm.loading    = false;

        /**
         * @type {Object}
         */
        vm.notaHelper = NotaHelper;

        /**
         * @type {Object}
         */
        vm.rastreioHelper = RastreioHelper.init(vm);

        /**
         * @type {Object}
         */
        vm.clienteEnderecoHelper = ClienteEnderecoHelper.init(vm);

        /**
         * @type {Object}
         */
        vm.pedidoHelper = PedidoHelper.init(vm);

        /**
         * @type {Object}
         */
        vm.clienteHelper = ClienteHelper.init(vm);

        vm.load = function() {
            vm.loading = true;

            Pedido.get(vm.pedido_id).then(function(pedido) {
                vm.pedido  = pedido;
                vm.loading = false;
            });
        };
        vm.load();

        /**
         * Mudar o status do pedido para segurado
         * @return {void}
         */
        vm.toggleHold = function() {
            Pedido.segurar(vm.pedido.id, !(vm.pedido.segurado)).then(function(pedido) {
                vm.load();
                vm.loading = false;
                toaster.pop('success', 'Sucesso!', 'Pedido ' + (vm.pedido.segurado ? 'segurado' : 'liberado') + ' com sucesso!');
            });
        };

        /**
         * Priorizar pedido
         */
        vm.togglePriority = function() {
            vm.loading = true;

            Pedido.prioridade(vm.pedido.id, !(vm.pedido.priorizado)).then(function(pedido) {
                vm.load();
                vm.loading = false;
                toaster.pop('success', 'Sucesso!', 'Prioridade do pedido alterada com sucesso!');
            });
        };

        /**
         * Retorna a classe de status do pedido
         *
         * @return {string}
         */
        vm.parseStatusClass = function() {
            switch (vm.pedido.status) {
                case '1':
                case '2':
                    return 'info';
                case '3':
                    return 'success';
                case '4':
                case '5':
                    return 'danger';
            }
        };

        /**
         * Retorna a classe de status do rastreio
         *
         * @return {string}
         */
        vm.parseRastreioStatusClass = function(rastreio) {
            switch (rastreio.status) {
                case '1':
                case '7':
                case '8':
                    return 'info';
                case '2':
                    return 'warning';
                case '4':
                    return 'success';
                case '3':
                case '5':
                case '6':
                    return 'danger';
            }
        };
    }
})();