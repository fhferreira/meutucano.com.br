<?php namespace Tests\Core;

use Core\Models\Pedido\FaturamentoCodigo;

trait CreateFaturamentoCodigo
{

  /**
   * Cria um objeto de fataturamento codigo
   *
   * @return Core\Models\Produto\Produto
   */
  public function createFaturamentoCodigo($data = [])
  {
    return factory(FaturamentoCodigo::class)->create($data);
  }

  /**
   * Gera os dois tipos de codigo de FaturamentoCodigo
   * @return void
   */
  public function generateFaturamentoCodigo()
  {
      $this->createFaturamentoCodigo([
          'servico' => 0,
      ]);

      $this->createFaturamentoCodigo([
          'servico' => 1,
      ]);
  }
}
