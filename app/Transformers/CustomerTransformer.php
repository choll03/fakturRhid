<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Customer;

class CustomerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Customer $customers)
    {
        return [
            'id'    => $customers->id,
            'nama'  => $customers->nama, 
        ];
    }
}
