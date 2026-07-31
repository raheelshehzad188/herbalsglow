@extends($layout)

<?php
use App\Models\Admins\Category;
use App\Models\Admins\SubCategory;
use App\Models\Childcatagorie;
use App\Models\product;
use App\Models\Admins\Gallerie;
  ?>
  <?php $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
$cate = DB::table('categories')->get();
?>


@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

@if (Session::has('cart'))
<div class="tf-page-cart-wrap">
                    <div class="tf-page-cart-item">
                        <form>
                            <table class="tf-table-page-cart">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                        $tot = 0;
                        @endphp
                        @foreach (App\Helpers\Cart::products() as $product)
                        @php
                        $tot = $tot+ ($product->discount_price* $product->qty);
                        @endphp
                                                            <tr class="tf-cart-item file-delete">
                                        <td class="tf-cart-item_product">
                                            <a href="product-detail.html" class="img-box">
                                                <img src="{{env('IMG_URL')}}/{{$product->image_one}}" alt="img-product">
                                            </a>
                                            <div class="cart-info">
                                                <a href="product-detail.html" class="cart-title link">{{$product->product_name}}</a>
                                                <span class="remove-cart link remove ion-close" productId="{{$product->id}}">Remove</span>
                                            </div>
                                        </td>
                                        <td class="tf-cart-item_price" cart-data-title="Price">
                                            <div class="cart-price">Rs {{$product->discount_price}}</div>
                                        </td>
                                        <td class="tf-cart-item_quantity" cart-data-title="Quantity">
                                            
                                    <div class="cart-quantity">
                                                
                                                <div class="wg-quantity">
                                                    <span class="btn-quantity minus-btn minus" productId="{{$product->id}}" productprice="{{$product->price}}" >
                                                        <svg class="d-inline-block btn-minus minus" type="button" width="9" height="1" viewBox="0 0 9 1" fill="currentColor"><path d="M9 1H5.14286H3.85714H0V1.50201e-05H3.85714L5.14286 0L9 1.50201e-05V1Z"></path></svg>
                                                    </span>
                                                    <input id="qty{{$product->id}}" name="qty" value="{{$product['qty']}}" type="number">
                                                    <span class="btn-quantity plus-btn plus" productId="{{$product->id}}" productprice="{{$product->price}}">
                                                        <svg class="d-inline-block btn-plus plus" type="button" width="9" height="9" viewBox="0 0 9 9" fill="currentColor"><path d="M9 5.14286H5.14286V9H3.85714V5.14286H0V3.85714H3.85714V0H5.14286V3.85714H9V5.14286Z"></path></svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="tf-cart-item_total" cart-data-title="Total">
                                            <div class="cart-total">Rs {{$product->discount_price}}</div>
                                        </td>
                                    </tr>
                        @endforeach
                                </tbody>
                            </table>
                            <div class="tf-page-cart-note">
                                <label for="cart-note">Add Order Note</label>
                                <textarea name="note" id="cart-note" placeholder="How can we help you?"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="tf-page-cart-footer">
                        <div class="tf-cart-footer-inner">
                            <div class="tf-free-shipping-bar">
                                <div class="tf-progress-bar">
                                    <span style="width: 50%;">
                                        <div class="progress-car">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="14" viewBox="0 0 21 14" fill="currentColor">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0.875C0 0.391751 0.391751 0 0.875 0H13.5625C14.0457 0 14.4375 0.391751 14.4375 0.875V3.0625H17.3125C17.5867 3.0625 17.845 3.19101 18.0104 3.40969L20.8229 7.12844C20.9378 7.2804 21 7.46572 21 7.65625V11.375C21 11.8582 20.6082 12.25 20.125 12.25H17.7881C17.4278 13.2695 16.4554 14 15.3125 14C14.1696 14 13.1972 13.2695 12.8369 12.25H7.72563C7.36527 13.2695 6.39293 14 5.25 14C4.10706 14 3.13473 13.2695 2.77437 12.25H0.875C0.391751 12.25 0 11.8582 0 11.375V0.875ZM2.77437 10.5C3.13473 9.48047 4.10706 8.75 5.25 8.75C6.39293 8.75 7.36527 9.48046 7.72563 10.5H12.6875V1.75H1.75V10.5H2.77437ZM14.4375 8.89937V4.8125H16.8772L19.25 7.94987V10.5H17.7881C17.4278 9.48046 16.4554 8.75 15.3125 8.75C15.0057 8.75 14.7112 8.80264 14.4375 8.89937ZM5.25 10.5C4.76676 10.5 4.375 10.8918 4.375 11.375C4.375 11.8582 4.76676 12.25 5.25 12.25C5.73323 12.25 6.125 11.8582 6.125 11.375C6.125 10.8918 5.73323 10.5 5.25 10.5ZM15.3125 10.5C14.8293 10.5 14.4375 10.8918 14.4375 11.375C14.4375 11.8582 14.8293 12.25 15.3125 12.25C15.7957 12.25 16.1875 11.8582 16.1875 11.375C16.1875 10.8918 15.7957 10.5 15.3125 10.5Z"></path>
                                            </svg>
                                        </div>
                                    </span>
                                </div>
                                <div class="tf-progress-msg">
                                    Buy <span class="price fw-6">$75.00</span> more to enjoy <span class="fw-6">Free Shipping</span>
                                </div>
                            </div>
                            <div class="tf-page-cart-checkout">
                                <div class="shipping-calculator">
                                    <summary class="accordion-shipping-header d-flex justify-content-between align-items-center collapsed" data-bs-target="#shipping" data-bs-toggle="collapse" aria-controls="shipping">
                                        <h3 class="shipping-calculator-title">Estimate Shipping</h3>
                                        <span class="shipping-calculator_accordion-icon"></span>
                                    </summary>
                                    <div class="collapse" id="shipping">
                                        <div class="accordion-shipping-content">
                                            <fieldset class="field">
                                                <label class="label">Country</label>
                                                <select class="tf-select w-100" id="ShippingCountry_CartDrawer-Form" name="address[country]" data-default="">
                                                    <option value="---" data-provinces="[]">---</option>
                                                    <option value="Australia" data-provinces="[['Australian Capital Territory','Australian Capital Territory'],['New South Wales','New South Wales'],['Northern Territory','Northern Territory'],['Queensland','Queensland'],['South Australia','South Australia'],['Tasmania','Tasmania'],['Victoria','Victoria'],['Western Australia','Western Australia']]">Australia</option>
                                                    <option value="Austria" data-provinces="[]">Austria</option>
                                                    <option value="Belgium" data-provinces="[]">Belgium</option>
                                                    <option value="Canada" data-provinces="[['Alberta','Alberta'],['British Columbia','British Columbia'],['Manitoba','Manitoba'],['New Brunswick','New Brunswick'],['Newfoundland and Labrador','Newfoundland and Labrador'],['Northwest Territories','Northwest Territories'],['Nova Scotia','Nova Scotia'],['Nunavut','Nunavut'],['Ontario','Ontario'],['Prince Edward Island','Prince Edward Island'],['Quebec','Quebec'],['Saskatchewan','Saskatchewan'],['Yukon','Yukon']]">Canada</option>
                                                    <option value="Czech Republic" data-provinces="[]">Czechia</option>
                                                    <option value="Denmark" data-provinces="[]">Denmark</option>
                                                    <option value="Finland" data-provinces="[]">Finland</option>
                                                    <option value="France" data-provinces="[]">France</option>
                                                    <option value="Germany" data-provinces="[]">Germany</option>
                                                    <option value="Hong Kong" data-provinces="[['Hong Kong Island','Hong Kong Island'],['Kowloon','Kowloon'],['New Territories','New Territories']]">Hong Kong SAR</option>
                                                    <option value="Ireland" data-provinces="[['Carlow','Carlow'],['Cavan','Cavan'],['Clare','Clare'],['Cork','Cork'],['Donegal','Donegal'],['Dublin','Dublin'],['Galway','Galway'],['Kerry','Kerry'],['Kildare','Kildare'],['Kilkenny','Kilkenny'],['Laois','Laois'],['Leitrim','Leitrim'],['Limerick','Limerick'],['Longford','Longford'],['Louth','Louth'],['Mayo','Mayo'],['Meath','Meath'],['Monaghan','Monaghan'],['Offaly','Offaly'],['Roscommon','Roscommon'],['Sligo','Sligo'],['Tipperary','Tipperary'],['Waterford','Waterford'],['Westmeath','Westmeath'],['Wexford','Wexford'],['Wicklow','Wicklow']]">Ireland</option>
                                                    <option value="Israel" data-provinces="[]">Israel</option>
                                                    <option value="Italy" data-provinces="[['Agrigento','Agrigento'],['Alessandria','Alessandria'],['Ancona','Ancona'],['Aosta','Aosta Valley'],['Arezzo','Arezzo'],['Ascoli Piceno','Ascoli Piceno'],['Asti','Asti'],['Avellino','Avellino'],['Bari','Bari'],['Barletta-Andria-Trani','Barletta-Andria-Trani'],['Belluno','Belluno'],['Benevento','Benevento'],['Bergamo','Bergamo'],['Biella','Biella'],['Bologna','Bologna'],['Bolzano','South Tyrol'],['Brescia','Brescia'],['Brindisi','Brindisi'],['Cagliari','Cagliari'],['Caltanissetta','Caltanissetta'],['Campobasso','Campobasso'],['Carbonia-Iglesias','Carbonia-Iglesias'],['Caserta','Caserta'],['Catania','Catania'],['Catanzaro','Catanzaro'],['Chieti','Chieti'],['Como','Como'],['Cosenza','Cosenza'],['Cremona','Cremona'],['Crotone','Crotone'],['Cuneo','Cuneo'],['Enna','Enna'],['Fermo','Fermo'],['Ferrara','Ferrara'],['Firenze','Florence'],['Foggia','Foggia'],['Forlì-Cesena','Forlì-Cesena'],['Frosinone','Frosinone'],['Genova','Genoa'],['Gorizia','Gorizia'],['Grosseto','Grosseto'],['Imperia','Imperia'],['Isernia','Isernia'],['L'Aquila','L’Aquila'],['La Spezia','La Spezia'],['Latina','Latina'],['Lecce','Lecce'],['Lecco','Lecco'],['Livorno','Livorno'],['Lodi','Lodi'],['Lucca','Lucca'],['Macerata','Macerata'],['Mantova','Mantua'],['Massa-Carrara','Massa and Carrara'],['Matera','Matera'],['Medio Campidano','Medio Campidano'],['Messina','Messina'],['Milano','Milan'],['Modena','Modena'],['Monza e Brianza','Monza and Brianza'],['Napoli','Naples'],['Novara','Novara'],['Nuoro','Nuoro'],['Ogliastra','Ogliastra'],['Olbia-Tempio','Olbia-Tempio'],['Oristano','Oristano'],['Padova','Padua'],['Palermo','Palermo'],['Parma','Parma'],['Pavia','Pavia'],['Perugia','Perugia'],['Pesaro e Urbino','Pesaro and Urbino'],['Pescara','Pescara'],['Piacenza','Piacenza'],['Pisa','Pisa'],['Pistoia','Pistoia'],['Pordenone','Pordenone'],['Potenza','Potenza'],['Prato','Prato'],['Ragusa','Ragusa'],['Ravenna','Ravenna'],['Reggio Calabria','Reggio Calabria'],['Reggio Emilia','Reggio Emilia'],['Rieti','Rieti'],['Rimini','Rimini'],['Roma','Rome'],['Rovigo','Rovigo'],['Salerno','Salerno'],['Sassari','Sassari'],['Savona','Savona'],['Siena','Siena'],['Siracusa','Syracuse'],['Sondrio','Sondrio'],['Taranto','Taranto'],['Teramo','Teramo'],['Terni','Terni'],['Torino','Turin'],['Trapani','Trapani'],['Trento','Trentino'],['Treviso','Treviso'],['Trieste','Trieste'],['Udine','Udine'],['Varese','Varese'],['Venezia','Venice'],['Verbano-Cusio-Ossola','Verbano-Cusio-Ossola'],['Vercelli','Vercelli'],['Verona','Verona'],['Vibo Valentia','Vibo Valentia'],['Vicenza','Vicenza'],['Viterbo','Viterbo']]">Italy</option>
                                                    <option value="Japan" data-provinces="[['Aichi','Aichi'],['Akita','Akita'],['Aomori','Aomori'],['Chiba','Chiba'],['Ehime','Ehime'],['Fukui','Fukui'],['Fukuoka','Fukuoka'],['Fukushima','Fukushima'],['Gifu','Gifu'],['Gunma','Gunma'],['Hiroshima','Hiroshima'],['Hokkaidō','Hokkaido'],['Hyōgo','Hyogo'],['Ibaraki','Ibaraki'],['Ishikawa','Ishikawa'],['Iwate','Iwate'],['Kagawa','Kagawa'],['Kagoshima','Kagoshima'],['Kanagawa','Kanagawa'],['Kumamoto','Kumamoto'],['Kyōto','Kyoto'],['Kōchi','Kochi'],['Mie','Mie'],['Miyagi','Miyagi'],['Miyazaki','Miyazaki'],['Nagano','Nagano'],['Nagasaki','Nagasaki'],['Nara','Nara'],['Niigata','Niigata'],['Okayama','Okayama'],['Okinawa','Okinawa'],['Saga','Saga'],['Saitama','Saitama'],['Shiga','Shiga'],['Shimane','Shimane'],['Shizuoka','Shizuoka'],['Tochigi','Tochigi'],['Tokushima','Tokushima'],['Tottori','Tottori'],['Toyama','Toyama'],['Tōkyō','Tokyo'],['Wakayama','Wakayama'],['Yamagata','Yamagata'],['Yamaguchi','Yamaguchi'],['Yamanashi','Yamanashi'],['Ōita','Oita'],['Ōsaka','Osaka']]">Japan</option>
                                                    <option value="Malaysia" data-provinces="[['Johor','Johor'],['Kedah','Kedah'],['Kelantan','Kelantan'],['Kuala Lumpur','Kuala Lumpur'],['Labuan','Labuan'],['Melaka','Malacca'],['Negeri Sembilan','Negeri Sembilan'],['Pahang','Pahang'],['Penang','Penang'],['Perak','Perak'],['Perlis','Perlis'],['Putrajaya','Putrajaya'],['Sabah','Sabah'],['Sarawak','Sarawak'],['Selangor','Selangor'],['Terengganu','Terengganu']]">Malaysia</option>
                                                    <option value="Netherlands" data-provinces="[]">Netherlands</option>
                                                    <option value="New Zealand" data-provinces="[['Auckland','Auckland'],['Bay of Plenty','Bay of Plenty'],['Canterbury','Canterbury'],['Chatham Islands','Chatham Islands'],['Gisborne','Gisborne'],['Hawke's Bay','Hawke’s Bay'],['Manawatu-Wanganui','Manawatū-Whanganui'],['Marlborough','Marlborough'],['Nelson','Nelson'],['Northland','Northland'],['Otago','Otago'],['Southland','Southland'],['Taranaki','Taranaki'],['Tasman','Tasman'],['Waikato','Waikato'],['Wellington','Wellington'],['West Coast','West Coast']]">New Zealand</option>
                                                    <option value="Norway" data-provinces="[]">Norway</option>
                                                    <option value="Poland" data-provinces="[]">Poland</option>
                                                    <option value="Portugal" data-provinces="[['Aveiro','Aveiro'],['Açores','Azores'],['Beja','Beja'],['Braga','Braga'],['Bragança','Bragança'],['Castelo Branco','Castelo Branco'],['Coimbra','Coimbra'],['Faro','Faro'],['Guarda','Guarda'],['Leiria','Leiria'],['Lisboa','Lisbon'],['Madeira','Madeira'],['Portalegre','Portalegre'],['Porto','Porto'],['Santarém','Santarém'],['Setúbal','Setúbal'],['Viana do Castelo','Viana do Castelo'],['Vila Real','Vila Real'],['Viseu','Viseu'],['Évora','Évora']]">Portugal</option>
                                                    <option value="Singapore" data-provinces="[]">Singapore</option>
                                                    <option value="South Korea" data-provinces="[['Busan','Busan'],['Chungbuk','North Chungcheong'],['Chungnam','South Chungcheong'],['Daegu','Daegu'],['Daejeon','Daejeon'],['Gangwon','Gangwon'],['Gwangju','Gwangju City'],['Gyeongbuk','North Gyeongsang'],['Gyeonggi','Gyeonggi'],['Gyeongnam','South Gyeongsang'],['Incheon','Incheon'],['Jeju','Jeju'],['Jeonbuk','North Jeolla'],['Jeonnam','South Jeolla'],['Sejong','Sejong'],['Seoul','Seoul'],['Ulsan','Ulsan']]">South Korea</option>
                                                    <option value="Spain" data-provinces="[['A Coruña','A Coruña'],['Albacete','Albacete'],['Alicante','Alicante'],['Almería','Almería'],['Asturias','Asturias Province'],['Badajoz','Badajoz'],['Balears','Balears Province'],['Barcelona','Barcelona'],['Burgos','Burgos'],['Cantabria','Cantabria Province'],['Castellón','Castellón'],['Ceuta','Ceuta'],['Ciudad Real','Ciudad Real'],['Cuenca','Cuenca'],['Cáceres','Cáceres'],['Cádiz','Cádiz'],['Córdoba','Córdoba'],['Girona','Girona'],['Granada','Granada'],['Guadalajara','Guadalajara'],['Guipúzcoa','Gipuzkoa'],['Huelva','Huelva'],['Huesca','Huesca'],['Jaén','Jaén'],['La Rioja','La Rioja Province'],['Las Palmas','Las Palmas'],['León','León'],['Lleida','Lleida'],['Lugo','Lugo'],['Madrid','Madrid Province'],['Melilla','Melilla'],['Murcia','Murcia'],['Málaga','Málaga'],['Navarra','Navarra'],['Ourense','Ourense'],['Palencia','Palencia'],['Pontevedra','Pontevedra'],['Salamanca','Salamanca'],['Santa Cruz de Tenerife','Santa Cruz de Tenerife'],['Segovia','Segovia'],['Sevilla','Seville'],['Soria','Soria'],['Tarragona','Tarragona'],['Teruel','Teruel'],['Toledo','Toledo'],['Valencia','Valencia'],['Valladolid','Valladolid'],['Vizcaya','Biscay'],['Zamora','Zamora'],['Zaragoza','Zaragoza'],['Álava','Álava'],['Ávila','Ávila']]">Spain</option>
                                                    <option value="Sweden" data-provinces="[]">Sweden</option>
                                                    <option value="Switzerland" data-provinces="[]">Switzerland</option>
                                                    <option value="United Arab Emirates" data-provinces="[['Abu Dhabi','Abu Dhabi'],['Ajman','Ajman'],['Dubai','Dubai'],['Fujairah','Fujairah'],['Ras al-Khaimah','Ras al-Khaimah'],['Sharjah','Sharjah'],['Umm al-Quwain','Umm al-Quwain']]">United Arab Emirates</option>
                                                    <option value="United Kingdom" data-provinces="[['British Forces','British Forces'],['England','England'],['Northern Ireland','Northern Ireland'],['Scotland','Scotland'],['Wales','Wales']]">United Kingdom</option>
                                                    <option value="United States" data-provinces="[['Alabama','Alabama'],['Alaska','Alaska'],['American Samoa','American Samoa'],['Arizona','Arizona'],['Arkansas','Arkansas'],['Armed Forces Americas','Armed Forces Americas'],['Armed Forces Europe','Armed Forces Europe'],['Armed Forces Pacific','Armed Forces Pacific'],['California','California'],['Colorado','Colorado'],['Connecticut','Connecticut'],['Delaware','Delaware'],['District of Columbia','Washington DC'],['Federated States of Micronesia','Micronesia'],['Florida','Florida'],['Georgia','Georgia'],['Guam','Guam'],['Hawaii','Hawaii'],['Idaho','Idaho'],['Illinois','Illinois'],['Indiana','Indiana'],['Iowa','Iowa'],['Kansas','Kansas'],['Kentucky','Kentucky'],['Louisiana','Louisiana'],['Maine','Maine'],['Marshall Islands','Marshall Islands'],['Maryland','Maryland'],['Massachusetts','Massachusetts'],['Michigan','Michigan'],['Minnesota','Minnesota'],['Mississippi','Mississippi'],['Missouri','Missouri'],['Montana','Montana'],['Nebraska','Nebraska'],['Nevada','Nevada'],['New Hampshire','New Hampshire'],['New Jersey','New Jersey'],['New Mexico','New Mexico'],['New York','New York'],['North Carolina','North Carolina'],['North Dakota','North Dakota'],['Northern Mariana Islands','Northern Mariana Islands'],['Ohio','Ohio'],['Oklahoma','Oklahoma'],['Oregon','Oregon'],['Palau','Palau'],['Pennsylvania','Pennsylvania'],['Puerto Rico','Puerto Rico'],['Rhode Island','Rhode Island'],['South Carolina','South Carolina'],['South Dakota','South Dakota'],['Tennessee','Tennessee'],['Texas','Texas'],['Utah','Utah'],['Vermont','Vermont'],['Virgin Islands','U.S. Virgin Islands'],['Virginia','Virginia'],['Washington','Washington'],['West Virginia','West Virginia'],['Wisconsin','Wisconsin'],['Wyoming','Wyoming']]">United States</option>
                                                    <option value="Vietnam" data-provinces="[]">Vietnam</option>
                                                </select>
                                            </fieldset>
                                            <fieldset class="field">
                                                <label class="label">Zip code</label>
                                                <input type="text" name="text" placeholder="">
                                            </fieldset>
                                            <button class="tf-btn btn-fill animate-hover-btn radius-3 justify-content-center">
                                                <span>Estimate</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tf-cart-totals-discounts">
                                    <h3>Subtotal</h3>
                                    Rs<span class="total-value" id="cartTotal1">{{$tot}}</span>
                                </div>
                                <div class="tf-cart-totals-discounts">
                                    <h3>Shipping Fee</h3>
                                    Rs<span class="total-value">{{ Session::has('cart') ? $setting->shipping_charges : 0 }}</span>
                                </div>
                                <div class="tf-cart-totals-discounts">
                                    <h3>Total</h3>
                                    Rs<span class="total-value" id="cartTotal">{{$tot +$setting->shipping_charges}}</span>
                                </div>
                                <div class="cart-checkout-btn">
                                    <a href="/checkout"><button class="tf-btn w-100 btn-fill animate-hover-btn radius-3 justify-content-center">
                                        <span>Check out</span>
                                    </button></a>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            @else
            <h3 class="text-center">Your Cart Is Empty!</h3>
        @endif
    
  
<script>
    let id,qty,price,productTotal;
    $(document).ready(function(){

        $('.ion-close').click(function(e){
        e.preventDefault();
           id = $(this).attr('productId');
          $.ajax({
              url : "{{url('cart/remove')}}",
              type : "POST",
              data : {
                  id : id,
                  "_token": "{{ csrf_token() }}",
              },
              success:function(response){
                  location.reload();
                  console.log(id);
                  removeFromView(id,response);
                  updateView(response);
                  location.reload();
              }
          });
      }); 
      

        $('.clear').click(function(e){
        e.preventDefault();
        //   id = $(this).attr('productId');
          $.ajax({
              url : "{{url('cart/clear')}}",
              type : "POST",
              data : {
                  
                  "_token": "{{ csrf_token() }}"
              },
              success:function(response){
                  location.reload();
                  
              }
          });
      }); 
      
      $('.plus').click(function(){
         id = $(this).attr('productid');
           var qty = $('#qty'+id).val();
           price = $(this).attr('productprice');
          $.ajax({
              url : "{{url('cart/increment')}}",
              type : "POST",
              data : {
                  id : id,
                  "_token": "{{ csrf_token() }}",
              },
              success:function(response){
                 qty++;
                  $('#qty'+id).val(qty);
                  updateView(response,price);
              }
          });
      });

      $('.minus').click(function(){
           id = $(this).attr('productid');
           var qty = $('#qty'+id).val();
           price = $(this).attr('productprice');
          $.ajax({
              url : "{{url('cart/decrement')}}",
              type : "POST",
              data : {
                  id : id,
                  "_token": "{{ csrf_token() }}",
              },
              success:function(response){
                  qty--;
                  $('#qty'+id).val(qty);
                  updateView(response,price);
                 
              }
          });
      });

      function updateView(response){
        productTotal=parseInt(qty*price);
          $('#cartValue').html(response.cart.qty);
          $('#price').html(response.cart.ship);
          $('#cartTotal').html(response.cart.amount+{{ Session::has('cart') ? $setting->shipping_charges : 0 }});
          $('#cartTotal1').html(response.cart.amount);
          $('#productTotal'+id).html(productTotal);
      }
      
    });
</script>



@endsection