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
          <h5 class="card-title fw-semibold mb-4">Product Management</h5>
        <p><strong>To Import Recommandation CSV</strong></p>
          <form action="{{ route('import') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        <div class="row justify-content-center align-items-center">
            <div class="col-6">
                <input type="file" name="file"
               class="w-50 mb-2">
        
            </div>
            <div class="col-6">
                <button class="btn btn-success w-50">
                    Import User Data
                 </button>
            </div>
        </div>
    </form>

                <!-- Top Start -->
                <div class="row justify-content-center align-item-center mb-5 mt-3">
                   
                   <div class="col-md-6 d-flex">
                    @can('manage products')
                    <form action="{{ url('product/search') }}" method="get">
                        <div class=" d-inline-block">
                              <input
                                  type="text"
                                  name="product_name"
                                  id=""
                                  class="form-control"
                                  placeholder="{{ $_GET['product_name'] ?? 'Product Name Or Code' }}"
                                  aria-describedby="helpId"
                              />
                          </div>
                              <button
                                  type="submit"
                                  class="btn btn-outline-dark d-inline-block"
                              >
                                  Search
                              </button>
                    </form>
                    @endcan
                </div>
                  
                    
          
                  <div class="col-md-6 text-right">
                    @can('create products')
                    <a href="{{ url('product/create') }}"><button class="btn btn-primary">Create</button></a>
                    @endcan
                </div>
                
              </div>
              <!-- Top End-->
              <h5 class="text-center"><span style="border: 5px solid #000; border-radius: 2px;"><strong style="background: #000; color: #fff">Product </strong> List</span></h5>
              <div class="table-responsive">
                  <table class="table table-white">
                      <thead>
                          <tr>
                              <th scope="col">#Serial</th>
                              @canany(['publish products', 'unpublish products'])
                              <th scope="col">Status</th>
                              @endcanany
                              <th scope="col">Image</th>
                              <th scope="col">Name</th>
                              <th scope="col">Quantity</th>
                              <th scope="col">Price</th>
                              @canany(['edit products', 'delete products'])
                              <th scope="col">Action</th>
                              @endcanany
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($products as $key=>$product)
                          <tr class="">
                              <input class="vid" type="hidden" name="id" value="{{ $product->id }}">
                              <td scope="row">{{ $key+1 }}</td>
                              @canany(['publish products', 'unpublish products'])
                              <td>
                                @if($product->status == 1)
                                    <form action="{{ route('Product Status') }}" method="post" class="statusForm">
                                        @csrf
                                        <input type="hidden" name="hidden_id" value="{{ $product->id }}">
                                        <input type="hidden" name="status" value="0">
                                        <button style="border: 0px; background:transparent" type="submit" class="switchBtnproduct">
                                            <img src="../../../backend/images/button/switch-on.png" height="35px" />
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('Product Status') }}" method="post" class="statusForm">
                                        @csrf
                                        <input type="hidden" name="hidden_id" value="{{ $product->id }}">
                                        <input type="hidden" name="status" value="1">
                                        <button style="border: 0px; background:transparent" type="submit" class="switchBtnproduct">
                                            <img src="../../../backend/images/button/switch-off.png" height="35px" />
                                        </button>
                                    </form>
                                    @endif
                              </td>
                              @endcanany
                              <td>
          
                                <img src="{{ asset($product->image ?? '') }}" height="40px" width="80px"/>
                    
                                </td>
                              <td>{{ $product->name }} </td>
                              <td>
                           
                                    {{ $product->quantity }}
                         
                              </td>
                              
                              <td>
                                <div style="display: flex; align-items: center;">
                                    <div style="display: inline-block; vertical-align: middle;"><strong>{{ $product->sale_price }}</strong></div>
                                </div>
                     
                              </td>
                              @canany(['edit products', 'delete products'])
                              <td>
                                @can('edit products')
                                <a href="{{ url('product/edit/'.$product->id) }}">
                                  <button class="btn btn-danger">
                                      Edit
                                  </button>
                                </a>
                                @endcan
                                @can('delete products')
                                  <button class="btn btn-danger remove">
                                      Delete
                                  </button>
                                @endcan
                              
                              </td>
                              @endcanany
                          </tr>
                          @endforeach
                      </tbody>
                  </table>
                  {{$products->links('pagination::bootstrap-5') }}
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

@endsection