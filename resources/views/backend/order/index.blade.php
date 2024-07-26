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
          <h5 class="card-title fw-semibold mb-4">Order Management</h5>

              <!-- Top End-->
              <h5 class="text-center"><span style="border: 5px solid #000; border-radius: 2px;"><strong style="background: #000; color: #fff">Order </strong> List</span></h5>
              <div class="table-responsive">
                  <table class="table table-white" id="myTable">
                      <thead>
                          <tr>
                              <th scope="col">#Serial</th>

                              <th scope="col">Name</th>
                              <th scope="col">Order State</th>
                              <th scope="col">Phone</th>
                              <th scope="col">Order Info</th>
                              <th scope="col">Total Amount</th>
                              <th scope="col">Action</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($orders as $key=>$order)
                          <tr class="">
                              <td scope="row">{{ $key+1 }}</td>
                              <td>{{ $order->getCustomer->name }} 
                                <br>
                                @if($order->getPayment->status == 1)
                                  <span class="bg-success text-white rounded px-2 py-1 w-25" style="font-size: 10px">Paid</span>
                                @else
                                <span class="bg-danger text-white rounded px-2 py-1 w-25" style="font-size: 10px">Unpaid</span>
                                @endif
                              </td>
                              <td>
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
                              </td>
                              <td>{{ $order->getCustomer->phone }} </td>
                              <td>
                                <div class="card px-2 py-2">
                                   @foreach($order->getOrderDetails as $item)
                                     {{ $item->product_name }} <br>
                                   @endforeach
                                </div>
                              </td>
                              <td class="text-center">
                                {{ $order->total_amount }}
                              </td>
                              <td>
                                <a href="{{ url('/order/'.$order->id) }}">
                                    <button class="btn btn-primary">
                                        View
                                    </button>
                                </a>
                              </td>
                 
               
                          </tr>
                          @endforeach
                      </tbody>
                  </table>
                  {{$orders->links('pagination::bootstrap-5') }}
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