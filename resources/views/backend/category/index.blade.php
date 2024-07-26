@extends('backend.layout.theme')
@section('main')
     <!--  Content Begin Here -->
 <div class="container-fluid">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">Category Management</h5>
   
           

            <!-- Top Start -->
            @can('create categories')
            <div class="row justify-content-end align-item-center my-5">
                <div class="col-md-6 text-right">
                    <a href="{{ url('category/create') }}"><button class="btn btn-primary">Create</button></a>
                </div>
            </div>
            @endcan
              <!-- Top End-->
              <h5 class="text-center"><span style="border: 5px solid #000; border-radius: 2px;"><strong style="background: #000; color: #fff">Category </strong> List</span></h5>
              <div class="table-responsive">
                  <table class="table table-white">
                      <thead>
                          <tr>
                              <th scope="col">#Serial</th>
                              @canany(['publish categories', 'unpublish categories'])
                                <th scope="col">Status</th>
                              @endcanany
                              <th scope="col">Image</th>
                              <th scope="col">Parent Category</th>
                              <th scope="col">Name</th>
                   
                              @canany(['edit categories', 'delete categories'])
                              <th scope="col">Action</th>
                              @endcan

                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($categories as $key=>$category)
                          <tr class="">
                              <input class="vid" type="hidden" name="id" value="{{ $category->id }}">
                              <td scope="row">{{ $key+1 }}</td>
                              @canany(['publish categories','unpublish categories'])
                              <td>
                                @if($category->status == 1)
                                    @can('unpublish categories')
                                    <form action="{{ route('Category Status') }}" method="post" class="statusForm">
                                        @csrf
                                        <input type="hidden" name="hidden_id" value="{{ $category->id }}">
                                        <input type="hidden" name="status" value="0">
                                        <button style="border: 0px; background:transparent" type="submit" class="switchBtnCategory">
                                            <img src="../../../backend/images/button/switch-on.png" height="35px" />
                                        </button>
                                    </form>
                                    @endcan
                                    @else
                                     @can('publish categories')
                                     <form action="{{ route('Category Status') }}" method="post" class="statusForm">
                                        @csrf
                                        <input type="hidden" name="hidden_id" value="{{ $category->id }}">
                                        <input type="hidden" name="status" value="1">
                                        <button style="border: 0px; background:transparent" type="submit" class="switchBtnCategory">
                                            <img src="../../../backend/images/button/switch-off.png" height="35px" />
                                        </button>
                                    </form>
                                     @endcan
                                    @endif
                              </td>
                              @endcanany
                              <td><img src="{{ asset($category->image) }}" height="80px" width="80px"/> </td>
                              <td>{{ $category->getmaster_category->name ?? '' }} @if($category->master_id != null) > @endif {{ $category->getparent_category->name ?? 'n/A' }} </td>
                              <td>{{ $category->name }} </td>
                              <td>
                                @can('edit categories')
                                  <a href="{{ url('category/edit/'.$category->id) }}">
                                    <button class="btn btn-danger">
                                        Edit
                                    </button>
                                  </a>
                                @endcan
                       
                                @can('delete categories')
                                    <button class="btn btn-danger remove">
                                        Delete
                                    </button>
                                @endcan
                              
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                  </table>
                  {{ $categories->links('pagination::bootstrap-5') }}
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
            var url = "/category/delete/" + id;
            window.location.href = url;
        }
    });


</script>

@endsection