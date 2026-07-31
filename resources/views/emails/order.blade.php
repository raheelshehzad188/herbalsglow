<?php 
use App\Models\Admins\Product;
?>
<?php $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
$cate = DB::table('categories')->get();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
</style>
</head> 
<body>
     <?php foreach ($order as $edit){?>
        <h2>Your Order Tracking No: {{$edit->order_no}}</h2>
        Thank you for your purchase [<?=$edit->customer_name?>]
        Thank you for purchasing our products, we will contact you via phone [<?=$edit->phone?>] to confirm order!
        
        Your order is being processed and should arrive at your destination within 1-4 days. Thank you again for your purchase. We would love to hear from you once you receive your items.
   <table class="table" id="customers">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Product Name</th>
      <th scope="col">Qutantity</th>
      <th scope="col">Amount</th>
    </tr>
  </thead>
  <tbody>
       
      <?php $pro = json_decode($edit->product_detail); 
      $no = 1;
      $tot = 0;
       foreach($pro as $v){
       
       $product = Product::where(['id'=>$v->id])->first();
       $tot = $tot + ($v->qty*$product->discount_price);
       
       ?>
    <tr>
      <th scope="row">{{$no++}}</th>
      <td>{{$product->product_name}}({{$product->discount_price}})</td>
      <td>{{$v->qty}}</td>
      <td>Rs:{{$v->qty*$product->discount_price}}</td>
    </tr>
    <?php } ?>
   
  </tbody>
  <tfoot>
        <tr>
            <td></td>
            <td></td>
            <th scope="row">Shpping charges</th>
            <td>Rs:{{$setting->shipping_charges}}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <th scope="row">Totals</th>
            <td>Rs:<?= $tot + $setting->shipping_charges ?></td>
        </tr>
    </tfoot>
    <?php }?>
</table>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>