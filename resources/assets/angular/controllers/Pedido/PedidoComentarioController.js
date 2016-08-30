(function() {
    'use strict';

    angular
        .module('MeuTucano')
        .controller('PedidoComentarioController', PedidoComentarioController);

    function PedidoComentarioController($rootScope, $stateParams, Restangular, toaster, ngDialog, Comentario) {
        var vm = this;

        vm.comentarios = [];
        vm.comentario = null;
        vm.pedido_id = $stateParams.id;
        vm.loading = false;

        /**
         * Load comentarios
         */
        vm.load = function() {
            vm.loading = true;

            Comentario.getFromOrder(vm.pedido_id).then(function(comentarios) {
                vm.loading = false;
                vm.comentarios = comentarios;
            });
        };

        vm.load();

        /**
         * Save comentario
         */
        vm.save = function(pedido) {
            vm.loading = true;

            Comentario.save({
                    'pedido_id': pedido,
                    'comentario': vm.comentario
                }).then(function() {
                    vm.loading = false;
                    vm.comentario = null;
                    vm.formComentario.$setPristine();
                    vm.load();
                    toaster.pop('success', 'Sucesso!', 'Comentário cadastrado com sucesso!');
            });
        };

        /**
         * Destroy comentário
         */
        vm.destroy = function(comentario) {
            vm.loading = true;

            Comentario.delete(comentario).then(function() {
                vm.loading = false;
                vm.comentario = null;
                vm.load();
                toaster.pop('info', 'Sucesso!', 'Comentário excluído com sucesso!');
            });
        };
    }
})();