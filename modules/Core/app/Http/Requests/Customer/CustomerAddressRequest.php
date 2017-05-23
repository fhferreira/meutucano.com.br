<?php namespace Core\Http\Requests\Customer;

use App\Http\Requests\JsonResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class CustomerAddressRequest extends FormRequest
{
    use JsonResponseTrait;

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'street'   => 'required',
            'number'   => 'required|numeric',
            'district' => 'required',
            'zipcode'  => 'required',
            'city'     => 'required',
            'state'    => 'required|size:2',
        ];
    }
}