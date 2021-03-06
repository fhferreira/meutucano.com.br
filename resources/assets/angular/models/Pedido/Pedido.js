(function() {
    'use strict';

    angular
        .module('MeuTucano')
        .service('Pedido', PedidoModel);

        function PedidoModel(Rest, Restangular) {
            var rest = angular.copy(Rest);
            rest.baseUrl = 'pedidos';

            angular.extend(rest, {
                /**
                 * Retorna os pedidos prontos para serem faturados
                 *
                 * @param  {Object} params
                 * @return {Object}
                 */
                faturamento: function(params) {
                    params = this.parseParams(params);

                    return Restangular.all('pedidos/faturamento').customGET("", params || {});
                },

                /**
                 * Altera o status do pedido
                 *
                 * @param  {int} pedido_id
                 * @param  {Object} params
                 * @return {Object}
                 */
                status: function(pedido_id, params) {
                    params = this.parseParams(params);

                    return Restangular.one('pedidos/status', pedido_id).customPOST(params || {});
                },

                /**
                 * Segura ou libera um pedido
                 * @param  {int} pedido_id
                 * @param  {boolean} segurar
                 * @return {Object}
                 */
                segurar: function(pedido_id, segurar) {
                    return Restangular.one('pedidos/segurar', pedido_id).customPUT({ 'segurar': segurar });
                },

                /**
                 * Altera prioridade do pedido
                 * @param  {int} pedido_id
                 * @param  {boolean} priorizado
                 * @return {Object}
                 */
                prioridade: function(pedido_id, priorizado) {
                    return Restangular.one('pedidos/prioridade', pedido_id).customPUT({ 'priorizado': priorizado });
                },

                /**
                 * Fatura um pedido
                 *
                 * @param  {int} pedido_id
                 * @return {Object}
                 */
                faturar: function(pedido_id) {
                    return Restangular.one('pedidos/faturar', pedido_id).get();
                },

                /**
                 * Retorna o total de pedidos por marketplace por status
                 *
                 * @return {Object}
                 */
                totalOrdersStatus: function() {
                    return Restangular.one('pedidos/total-orders-status').get();
                },

                /**
                 * Retorna o total de pedidos pagos, entregues e enviados no dia mes e ano atual, bem como seu anterior imediato
                 *
                 * @return {Object}
                 */
                totalOrdersDate: function() {
                    return Restangular.one('pedidos/total-orders-date').get();
                },

                /**
                 * Retorna o total de pedidos pagos, enviados e entregues por dia no mês/ano atual ou por parâmetro
                 *
                 * @param  {int} mes
                 * @param  {int} ano
                 * @return {Object}
                 */
                totalOrders: function(mes, ano) {
                    if (typeof mes !== 'undefined' && !isNaN(parseInt(mes))) {
                        mes = parseInt(mes);
                    } else {
                        mes = false;
                    }

                    if (typeof ano !== 'undefined' && !isNaN(parseInt(ano))) {
                        ano = parseInt(ano);
                    } else {
                        ano = false;
                    }

                    var string = '';
                    string = (mes) ? '/' + mes : '';
                    string += (ano) ? '/' + ano : '';

                    return Restangular.one('pedidos/total-orders' + string).get();
                },
            });

            return rest;
        }
})();
