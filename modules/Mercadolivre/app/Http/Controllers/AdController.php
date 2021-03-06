<?php namespace Mercadolivre\Http\Controllers;

use Core\Models\Produto;
use Mercadolivre\Models\Ad;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Mercadolivre\Http\Services\Api;
use Illuminate\Support\Facades\Input;
use Mercadolivre\Transformers\AdTransformer;
use Magento\Http\Controllers\MagentoController;
use App\Http\Controllers\Rest\RestControllerTrait;
use Mercadolivre\Http\Requests\AdRequest as Request;
use Mercadolivre\Http\Controllers\TemplateController;
use Mercadolivre\Http\Controllers\Traits\CheckExpiredToken;

class AdController extends Controller
{
    use RestControllerTrait,
        CheckExpiredToken;

    const MODEL = Ad::class;

    /**
     * List products with ads
     * @return Response
     */
    public function groupedList()
    {
        $list = Produto::with('mercadolivreAds')
            ->orderBy('produtos.created_at', 'DESC');

        $list = $this->handleRequest($list);

        return $this->listResponse(AdTransformer::tableList($list));
    }

    /**
     * Create a new resource
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request, Api $api)
    {
        try {
            $ad = Ad::create(Input::all());

            if ($mercadolivreAd = $this->publishAd($api, $ad)) {
                $ad->status    = 1;
                $ad->code      = $mercadolivreAd['code'];
                $ad->permalink = $mercadolivreAd['permalink'];
                $ad->save();

                if ($ad->product->estoque < 1) {
                    $api->syncStock($ad->code, 0);
                }
            }

            return $this->createdResponse($ad);
        } catch (\Exception $exception) {
            \Log::error(logMessage($exception, 'Erro ao salvar recurso'), ['model' => self::MODEL]);

            return $this->clientErrorResponse([
                'error'     => true,
                'message'   => 'Não foi possível salvar o anúncio!',
                'exception' => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Publish an ad
     *
     * @param  Api    $api
     * @param  integer $id
     * @return Response
     */
    public function publish(Api $api, $id)
    {
        try {
            $ad = Ad::findOrFail($id);

            if ($mercadolivreAd = $this->publishAd($api, $ad)) {
                $ad->status    = 1;
                $ad->code      = $mercadolivreAd['code'];
                $ad->permalink = $mercadolivreAd['permalink'];
                $ad->save();

                if ($ad->product->estoque < 1) {
                    $api->syncStock($ad->code, 0);
                }
            }

            return $this->createdResponse($ad);
        } catch (\Exception $exception) {
            \Log::error(logMessage($exception, 'Erro ao publicar anúncio'));

            return $this->clientErrorResponse([
                'error'     => true,
                'message'   => 'Não foi possível publicar o anúncio!',
                'exception' => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Publish Ad to Mercado Livre
     *
     * @param  Ad     $ad
     * @return array|bool
     */
    protected function publishAd(Api $api, Ad $ad)
    {
        try {
            $mercadolivreAd = [
                "title"              => $ad->title,
                "category_id"        => $ad->category_id,
                "price"              => $ad->price,
                "currency_id"        => "BRL",
                "available_quantity" => $ad->product->estoque,
                "buying_mode"        => "buy_it_now",
                "listing_type_id"    => ($ad->type == 0) ? 'gold_special' : 'gold_pro',
                "condition"          => ($ad->product->estado == 0) ? 'new' : 'used',
                "description"        => $this->getAdDescription($ad),
                "video_id"           => $ad->video,
                "warranty"           => $ad->product->warranty,
                "shipping"           => [
                    "mode"          => "me2",
                    "local_pick_up" => false,
                    "free_shipping" => ($ad->shipping == 1),
                    "free_methods"  => ($ad->shipping == 1) ? [
                        [
                            "id"   => 100009,
                            "rule" => [
                                "free_mode" => "country",
                                "value"     => null
                            ]
                        ]
                    ] : []
                ],
                "pictures" => $this->getAdImages($ad)
            ];

            $response = $api->publishAd($mercadolivreAd);

            if ($response['httpCode'] !== 201) {
                throw new \Exception($response['body']->message, 1);
            }

            return [
                'code'      => $response['body']->id,
                'permalink' => $response['body']->permalink
            ];
        } catch (\Exception $e) {
            \Log::warning(logMessage($e, 'Não foi possível cadastrar o anúncio no Mercado Livre'));

            return false;
        }
    }

    /**
     * Sync Ad to Mercado Livre
     *
     * @param  Api    $api
     * @param  Ad     $ad
     * @return boolean
     */
    public function syncAd(Api $api, Ad $ad)
    {
        try {
            $mercadolivreAd = [
                "title"              => $ad->title,
                "price"              => $ad->price,
                "video_id"           => $ad->video,
                "warranty"           => $ad->product->warranty,
                "shipping"           => [
                    "mode"          => "me2",
                    "local_pick_up" => false,
                    "free_shipping" => ($ad->shipping == 1),
                    "free_methods"  => ($ad->shipping == 1) ? [
                        [
                            "id"   => 100009,
                            "rule" => [
                                "free_mode" => "country",
                                "value"     => null
                            ]
                        ]
                    ] : []
                ],
                "pictures" => $this->getAdImages($ad)
            ];

            $response = $api->syncAd($ad->code, $mercadolivreAd);

            return true;
        } catch (\Exception $e) {
            \Log::warning(logMessage($e, 'Não foi possível atualizar o anúncio no Mercado Livre'));

            return false;
        }
    }

    /**
     * Update a resource
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Api $api, $id)
    {
        try {
            $ad = Ad::findOrFail($id);
            $ad->fill(Input::all());

            if ($ad->code) {
                if (!$this->syncAd($api, $ad)) {
                    throw new \Exception("Não foi possível atualizar o anúncio");
                }

                if ($ad->isDirty('type')) {
                    sleep(1);
                    if (!$api->syncType($ad->code, $ad->type_description)) {
                        throw new \Exception("Não foi possível atualizar o tipo do anúncio");
                    }
                }

                if ($ad->isDirty('template_id') || $ad->isDirty('template_custom')) {
                    sleep(1);
                    if (!$api->syncDescription($ad->code, $this->getAdDescription($ad))) {
                        throw new \Exception("Não foi possível atualizar a descrição do anúncio");
                    }
                }
            }

            $ad->save();

            return $this->showResponse($ad);
        } catch (\Exception $exception) {
            \Log::error(logMessage($exception, 'Erro ao atualizar recurso'), ['model' => self::MODEL]);

            return $this->clientErrorResponse([
                'exception' => '[' . $exception->getLine() . '] ' . $exception->getMessage()
            ]);
        }
    }

    /**
     * Deletes a resource
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Api $api, $id)
    {
        try {
            $ad = Ad::findOrFail($id);

            if ($ad->code) {
                if (!$api->syncStatus($ad->code, 'paused')) {
                    throw new \Exception("Não foi possível pausar o anúncio");
                }
            }

            $ad->delete();
            return $this->deletedResponse();
        } catch (\Exception $exception) {
            \Log::error(logMessage($exception, 'Erro ao excluir recurso'), ['model' => self::MODEL]);

            return $this->clientErrorResponse([
                'exception' => '[' . $exception->getLine() . '] ' . $exception->getMessage()
            ]);
        }
    }

    /**
     * Update stock based on product SKU
     *
     * @param  Api     $api
     * @param  Produto $product
     * @return boolean
     */
    public function updateStockByProduct(Produto $product)
    {
        try {
            $api = new Api();

            foreach ($product->mercadolivreAds as $ad) {
                $api->syncStock($ad->code, $product->estoque);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error(logMessage($e, 'Erro ao atualizar estoque do anúncio'));
            return false;
        }
    }

    /**
     * Update ad status
     *
     * @param  Api    $api
     * @param  string $id
     * @param  string $status
     * @return boolean
     */
    protected function updateAdStatus(Api $api, $id, $status)
    {
        try {
            $ad = Ad::findOrFail($id);

            if (!$api->syncStatus($ad->code, $status)) {
                throw new \Exception("Não foi alterar o status do anúncio para {$status}");
            }

            $ad->status = ($status == 'active') ? 1 : 2;
            $ad->save();

            return $this->showResponse($ad);
        } catch (\Exception $e) {
            \Log::error(logMessage($e, 'Erro ao atualizar status do anúncio'));
            return $this->clientErrorResponse([
                'error' => true,
                'message' => 'Erro ao atualizar status do anúncio',
                'exception' => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Set ad as paused
     *
     * @param  Api    $api
     * @param  string $id
     * @return void
     */
    public function pauseAd(Api $api, $id)
    {
        return $this->updateAdStatus($api, $id, 'paused');
    }

    /**
     * Set ad as active
     *
     * @param  Api    $api
     * @param  string $id
     * @return void
     */
    public function activateAd(Api $api, $id)
    {
        return $this->updateAdStatus($api, $id, 'active');
    }


    /**
     * Sync full ad information with Mercado Livre
     *
     * @param  Api    $api [description]
     * @param  int $id
     * @return boolean
     */
    public function fullSyncAd(Api $api, $id)
    {

    }

    /**
     * Synchronize ad by its code
     *
     * @param  Api    $api
     * @param  int    $sku
     * @param  string $code
     * @return Response
     */
    public function manualSync(Api $api, $sku, $code)
    {
        try {
            $code = (substr($code, 0, 3) == 'MLB') ? $code : ('MLB' . $code);

            $item = $api->getItem($code);

            $ad = Ad::firstOrNew(['code' => $code]);
            $ad->product_sku = $sku;
            $ad->permalink   = $item->permalink;
            $ad->title       = $item->title;
            $ad->price       = $item->price;
            $ad->video       = $item->video_id;
            $ad->category_id = $item->category_id;
            $ad->type        = ($item->listing_type_id == 'gold_special') ? 0 : 1;
            $ad->shipping    = ($item->shipping->free_shipping) ? 1 : 0;
            $ad->status      = ($item->status == 'active') ? 1 : 2;

            if ($ad->save()) {
                \Log::info("ML: Item {$item->id} sincronizado manualmente para o anúncio {$ad->id}");
            } else {
                \Log::warning("ML: Não foi possivel sincronizar o item {$item->id} para o anúncio {$ad->id}");
                throw new \Exception("Não foi possivel sincronizar o item");
            }

            return $this->showResponse($ad);
        } catch (\Exception $e) {
            \Log::error(logMessage($e, 'Erro ao sincronizar anúncio manualmente'));

            return $this->clientErrorResponse([
                'error' => true,
                'message' => 'Erro ao atualizar status do anúncio',
                'exception' => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Return ideal description from ad
     *
     * @param  Ad     $ad
     * @return string
     */
    protected function getAdDescription(Ad $ad)
    {
        return ($ad->template_id)
            ? with(new TemplateController())->generateTemplate($ad)
            : $ad->template_custom;
    }

    /**
     * Return images ad
     *
     * @param  Ad     $ad
     * @return array
     */
    protected function getAdImages(Ad $ad)
    {
        $images = with (new MagentoController())
            ->getProductImages($ad->product->sku);

        $adImages = [];
        foreach ($images as $image) {
            $adImages[] = ['source' => $image];
        }

        return $adImages;
    }
}
