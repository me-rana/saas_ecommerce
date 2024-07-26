@extends('backend.layout.theme')
@section('main')
<style>
    .circle {
  width: 25px;
  height: 25px;
  line-height: 25px;
  border-radius: 50%; /* the magic */
  -moz-border-radius: 50%;
  -webkit-border-radius: 50%;
  text-align: center;
  color: white;
  text-transform: uppercase;

}
</style>
 <!--  Content Begin Here -->

    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">Order Details</h5>
              <div class="row">
                <div class="col-md-6">
                   User Info <br>
                   Name : {{ $order->getCustomer->name }} <br>
                   Address : {{ $order->getCustomer->address }} <br>
                   Phone : {{ $order->getCustomer->phone }} <br>
                </div>
                <div class="col-md-6">
                  Shipping Address <br>
                   Name : {{ $order->getShipping->name }} <br>
                   Address : {{ $order->getShipping->address }} <br>
                   Phone : {{ $order->getShipping->phone }} <br>
                </div>
              </div>
              <!-- Top End-->
              <h5 class="text-center mt-5"><span style="border: 5px solid #000; border-radius: 2px;"><strong style="background: #000; color: #fff">Order </strong> Products</span></h5>
              <div class="table-responsive">
                  <table class="table table-white">
                      <thead>
                          <tr>
                              <th scope="col">#Serial</th>

                              <th scope="col">Product Name</th>
                              <th scope="col">Quantity</th>
                              <th scope="col">Price</th>
                              <th scope="col">Total Amount</th>
            
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($order->getOrderDetails as $key=>$item)
                          <tr class="">
                              <td scope="row">{{ $key+1 }}</td>
                              <td>{{ $item->product_name}} </td>
                              <td>{{ $item->quantity}} </td>
                              <td>{{ $item->sale_price}} </td>
                              <td>{{ $item->sub_total}} </td>
                            
                 
               
                          </tr>
                          @endforeach
                          <tr>
                            <td>Total : {{ $order->total_amount }}</td>
                          </tr>
                      </tbody>
                  </table>

              </div>

              <div class="row align-items-center justify-content-center">
                <div class="col-md-6">
                  <div class="card text-center">
                    @if($order->getPayment->status == 1)
                    <span class="bg-success text-white rounded px-2 py-1" style="font-size: 10px">Paid</span>
                  @else
                  <span class="bg-danger text-white rounded px-2 py-1" style="font-size: 10px">Unpaid</span>
                  @endif
                    Payment Type : {{ $order->getPayment->payment_type }} <br>
                    Amount : {{ $order->getPayment->amount }} <br>
                    TransactionID : {{ $order->getPayment->transaction_id }} <br>
                    Received : {{ $order->getPayment->store_amount ?? 'n/A'}} <br>
                    
                    
                    <div class="my-5 mx-5">
                      <form action="{{ route('Order Status') }}" method="get">
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="mb-3">
                          <select
                            class="form-control form-control-sm status-manage"
                            name="status"
                            id=""
                          >
                          @foreach($order_statuses as $status)
                            <option value="{{ $status->id }}" @if($order->status == $status->id) selected @endif>{{ $status->name }}</option>
                          @endforeach
                          </select>
                        </div>
                        
                      </form>
                    </div>
                  </div>
                </div>
              </div>

          </div>


      </div>
    </div>
  </div>

 <!--  Content End Here -->

@endsection


@section('scripts')
<script type="text/javascript">
    $(".remove").click(function(){
        var id = $(this).parents('tr').find('.vid').val();
        console.log(id);
        if(confirm('Are you sure to remove this record ?'))
        {
            var url = "product/delete/" + id;
            window.location.href = url;
        }
    });


</script>

<script>
  $(document).ready(function(){
      $('.status-manage').on('change',function(){
        $(this).closest('form').submit();
      })
  });
</script>

@endsection