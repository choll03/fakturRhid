<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use app\Faktur;
use Indonesia;

class FakturTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Faktur $faktur)
    {
        $indonesia  = Indonesia::findVillage($faktur->customer->village_id, ['district.city.province']);
        $desa       = " Kp. " . $indonesia->name;
        $kecamatan  = " Kec. " . $indonesia->district->name;
        $kabupaten  = " Kab. " . $indonesia->district->city->name;
        $provinsi  = " - " . $indonesia->district->city->province->name;

        return [
            'id'        => $faktur->id,
            'no_faktur' => $faktur->no_faktur,
            'tanggal'   => $faktur->tanggal,
            'total'     => $faktur->total,
            'tujuan'    => $faktur->customer->nama,
            'edit_mode' => $faktur->edit_mode,
            'alamat'    => $faktur->customer->alamat . $desa . $kecamatan . $kabupaten . $provinsi
        ];
    }
}
