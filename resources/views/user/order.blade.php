@extends('layout.base')


@section('styleCSS')
  <title>Orders</title>
   <link href="/assets/css/order.css" rel="stylesheet">
@endsection


@section('Page')  
  <section class="table__body">
    <table>
        <thead>
            <tr>
                <th>Orders List</th>
                <th>Date</th>
                <th>Price</th>
                <th>Number of item</th>
            </tr>
        </thead>
        <tbody>

            @foreach($orderListArticle as $orderList)
                <tr>
                <td><div style="display : flex;">Details <div class="chevron" onclick="toggleDetails('{{$orderList['reference']}}')"><i id="c{{$orderList['reference']}}" class="bi bi-chevron-right"></i></div></div></td>
                <td>{{$orderList['listArticle'][0]['date']}}</td>
                <td>
                    @php $totalPrice = 0; @endphp 
                    @foreach ($orderList['listArticle'] as $article) 
                      @php $totalPrice += $article['price']* $article['quantity']; @endphp
                    @endforeach
                    <p style="background-color: #d5d2d2;"><span class='status delivered'>{{$totalPrice}} €</span></p>
                </td>
                <td>{{count($orderList['listArticle'])}} Items</td>           
                </tr>

                <tr id="d{{$orderList['reference']}}" class='desactive' style="background-color:white !important;">
                <td><p>Order</p></td>
                <td>
                <div>
                     @foreach($orderList['listArticle'] as $article)
                       <p>{{$article['article']}}</p>
                     @endforeach
                </div>
                </td>
                <td>
                    <div>
                         @foreach($orderList['listArticle'] as $article)
                           <p>{{$article['price']}} €</p>
                         @endforeach
                    </div>
                    </td>
                    <td>
                        <div>
                             @foreach($orderList['listArticle'] as $article)
                               <p>Qt: {{$article['quantity']}}</p>
                             @endforeach
                        </div>
                        </td>        
                </tr>    
            @endforeach
        </tbody>
    </table>
</section>



  <script>
    function toggleDetails(id)
    {
      let details = document.getElementById("d"+id);
	  details.classList.toggle("desactive");

      let chevron = document.getElementById("c"+id);
      chevron.classList.toggle("bi-chevron-down");
      chevron.classList.toggle("bi-chevron-right");
      
    }
  </script>
@endsection