<?php

return [
    'name' => 'Rastreio',

    'servico' => [
        0       => 'pac',
        1       => 'sedex',
        'pac'   => 0,
        'sedex' => 1,
    ],

    'status' => [
        0 => 'Pendente',
        1 => 'Normal',
        2 => 'Atrasado',
        3 => 'Extraviado',
        4 => 'Entregue',
        5 => 'Devolvido',
        6 => 'Retirada',
        7 => 'Tratado',
        8 => 'Solucionado',
        9 => 'Desconhecido',
    ],

    'devolucao_status' => [
        0 => 'Endereço insuficiente',
        1 => 'Não procurado (Retirada)',
        2 => 'Endereço incorreto',
        3 => 'Destinatário desconhecido',
        4 => 'Recusado',
        5 => 'Defeito',
        6 => 'Mudança',
        7 => 'Outro'
    ],

    /**
     * Configurações de contrato com correios
     */
    'correios' => [
        'accessData' => [
            'usuario'           => '9912380773',
            'senha'             => 'eymos8',
            'codAdministrativo' => '15248798',
            'numeroContrato'    => '9912380773',
            'cartaoPostagem'    => '71351612',
            'cnpjEmpresa'       => '14619408000150',
            'anoContrato'       => null,
            'diretoria'         => new \PhpSigep\Model\Diretoria(\PhpSigep\Model\Diretoria::DIRETORIA_DR_SANTA_CATARINA),
        ],
        'remetente' => [
            'nome'        => 'CARIOCA',
            'telefone'    => '(47) 3521-3280',
            'rua'         => 'RUA CARLOS GOMES',
            'numero'      => '238',
            'complemento' => 'SALA 1',
            'bairro'      => 'CENTRO',
            'cep'         => '89160-051',
            'uf'          => 'SC',
            'cidade'      => 'RIO DO SUL',
        ]
    ],
];
