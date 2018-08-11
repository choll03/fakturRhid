<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faktur;
use App\Detail;
use App\Item;
use Fpdf;

class FakturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('faktur');
    }

    public function get() {
        return Faktur::latest()->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $faktur = new Faktur();
         $data = $request->json()->all();

         $current_faktur = $faktur->max('no_faktur');

         if($current_faktur){
            if (substr($current_faktur, 0, 6) == date("Ym")) {
                $no_faktur = $current_faktur + 1;
            } else {
                 $no_faktur = date("Ym")."001";
            }
            
         }else {
            $no_faktur = date("Ym")."001";
         }

         $faktur->no_faktur = $no_faktur;
         $faktur->alamat = $data['alamat'];
         $faktur->total = $data['total'];
         $faktur->tujuan = $data['tujuan'];
         $faktur->tanggal = date("d-m-Y");

         $faktur->save();

         $faktur_id = $faktur->id;

         

         foreach ($data['cart'] as $item) {
             $detail = new Detail;

             $detail->faktur_id = $faktur_id;
             $detail->item_id = $item['id'];
             $detail->qty = $item['qty'];
             $detail->subtotal = $item['qty'] * $item['price'];

             $detail->save();

             $items = Item::find($detail->item_id);
             $stok = $items->stok;
             $items->update(['stok' => $stok-$detail->qty]);
         }

         return $faktur->no_faktur;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $faktur = Faktur::find($id);
        $detail = Detail::where('faktur_id', $faktur->id)
                    ->leftJoin('items', 'details.item_id', '=', 'items.id')
                    ->get();
        return $detail;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $faktur = Faktur::findOrFail($id);

        $faktur->update([
            'tujuan'=> $request->tujuan,
            'alamat'=> $request->alamat
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $faktur = Faktur::findOrFail($id);
        $faktur->delete();

        return 204;
    }

    public function cetak($id) {
        $faktur = Faktur::find($id);
        $details = Detail::where('faktur_id', $faktur->id)
                    ->leftJoin('items', 'details.item_id', '=', 'items.id')
                    ->get();

        $bulan = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        );

        Fpdf::AddPage();
        Fpdf::SetFont('Times', 'B', 14);
        Fpdf::Cell(140, 7, "PT. ASIA PRAKARSA METAL",0,0);
        Fpdf::Cell(50, 7, "FAKTUR",0,1);

        Fpdf::SetFont('Times', '', 10);
        Fpdf::Cell(190, 5, 'Jl.Raya Tajur Kp.Babakan Rt.05/05 No.40',0,1);
        Fpdf::Cell(190, 5, 'Ds.Tarikolot.Kec.Citeureup BOGOR 16810',0,1);
        Fpdf::Cell(190, 5, 'Telp./Fax : (021)87943483   Flexi : (021)70243670',0,1);
        Fpdf::Cell(190, 5, 'e-mail : asiaprakarsametal@gmail.com',0,1);

        Fpdf::Cell(190, 5, '',0,1);

        Fpdf::Cell(100, 5, '',0,0);
        Fpdf::Cell(70, 5, 'No Faktur : '.$faktur->no_faktur,0,1,'R');
        Fpdf::Cell(190, 2, '',0,1);


        Fpdf::Cell(190, 2, '','T',1);
        Fpdf::Cell(17, 5, ' Kepada',0,0);
        Fpdf::Cell(3, 5, ':');
        Fpdf::Cell(170, 5, $faktur->tujuan,0,1);
        Fpdf::Cell(17, 5, ' Alamat',0,0);
        Fpdf::Cell(3, 5, ':');
        Fpdf::MultiCell(70, 5, $faktur->alamat);
        Fpdf::setY(49);
        Fpdf::Cell(190, 23, '','B,L,R',1);

        Fpdf::Cell(10,7,'No','L,B',0,'C');
        Fpdf::Cell(50,7,'Nama Barang','L,B',0,'C');
        Fpdf::Cell(40,7,'Ukuran','L,B',0,'C');
        Fpdf::Cell(30,7,'Harga Satuan','L,B',0,'C');
        Fpdf::Cell(20,7,'Quantity','L,B',0,'C');
        Fpdf::Cell(40,7,'Jumlah','L,B,R',1,'C');

        $i=0;
        foreach ($details as $detail) { 
            Fpdf::Cell(10,5,$i+=1,'L',0,'C');
            Fpdf::Cell(50,5,$detail->nama_item,'L',0,'C');
            Fpdf::Cell(40,5,$detail->ukuran,'L',0,'C');
            Fpdf::Cell(5,5,'Rp.','L',0);
            Fpdf::Cell(25,5,number_format($detail->harga_item),0,0,'R');
            Fpdf::Cell(20,5,$detail->qty,'L',0,'C');
            Fpdf::Cell(5,5,'Rp.','L',0);
            Fpdf::Cell(30,5,number_format($detail->subtotal),0,0,'R');
            Fpdf::Cell(5,5,'','R',1);
        }
        Fpdf::Cell(10,5,'','L',0,'C');
        Fpdf::Cell(50,5,'','L',0,'C');
        Fpdf::Cell(40,5,'','L',0,'C');
        Fpdf::Cell(30,5,'','L',0,'C');
        Fpdf::Cell(20,5,'','L',0,'C');
        Fpdf::Cell(40,5,'','L,R',1,'C');

        $diskon = ($faktur->total*20)/100;

        Fpdf::Cell(135,7,'SubTotal','L,T,B',0,'R');
        Fpdf::Cell(15,7,'','T',0,'C');
        Fpdf::Cell(5,7,'Rp.','T,L',0);
        Fpdf::Cell(30,7,number_format($faktur->total),'T',0,'R');
        Fpdf::Cell(5,7,'','T,R',1);
        Fpdf::Cell(135,7,'Diskon 20%','L,B',0,'R');
        Fpdf::Cell(15,7,'','T,B',0,'C');
        Fpdf::Cell(5,7,'Rp.','T,L',0);
        Fpdf::Cell(30,7,number_format($diskon),'T',0,'R');
        Fpdf::Cell(5,7,'','T,R',1);
        Fpdf::Cell(135,7,'Total','L,B',0,'R');
        Fpdf::Cell(15,7,'',0,0,'C');
        Fpdf::Cell(5,7,'Rp.','T,L',0);
        Fpdf::Cell(30,7,number_format($faktur->total - $diskon),'T',0,'R');
        Fpdf::Cell(5,7,'','T,R',1);


        Fpdf::Cell(190,7,'','T,L,R',1);
        $waktu = explode('-', $faktur->tanggal);
        Fpdf::Cell(50,7,'Tanda Terima','L',0,'C');
        Fpdf::Cell(90,7,'');
        Fpdf::Cell(50,7,'Citeureup, '.$waktu[0].' '.$bulan[$waktu[1]].' '.$waktu[2],'R',1,'C');

        Fpdf::Cell(190,10,'','L,R',1);

        Fpdf::Cell(50,7,'( ________________ )','L',0,'C');
        Fpdf::Cell(90,7,'');
        Fpdf::Cell(50,7,'( Muhammad Farid )','R',1,'C');

        Fpdf::Cell(190,7,'','L,R,B',1);

        Fpdf::Output();
        exit;
    }
}
