@extends('backend.layout.theme')
@section('main')
<!--  Content Begin Here -->

    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">User Management</h5>
                <!-- Top Start -->
                @can('create users')
                <div class="row justify-content-end align-item-centre">
                    <div class="col-md-6 text-right">
                        <a href="{{ url('user/create') }}"><button class="btn btn-primary">Create</button></a>
                    </div>
                </div>
                @endcan
                <!-- Top End-->
                <h5 class="text-center"><span style="border: 5px solid #000; border-radius: 2px;"><strong style="background: #000; color: #fff">User </strong> List</span></h5>
                <div class="table-responsive">
                    <table class="table table-white">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 10%">#Serial</th>
                                <th scope="col" style="width: 10%">Profile</th>
                                <th scope="col" style="width: 20%">Name</th>
                                <th scope="col" style="width: 40%">Email</th>
                                @canany(['edit users','delete users'])
                                <th scope="col" style="width: 20%">Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key=>$admin)
                            <tr class="">
                                <input class="vid" type="hidden" name="id" value="{{ $admin->id }}">
                                <td scope="row">{{ $key+1 }}</td>
                                <td><img src="{{ asset($admin->profile_photo_path ?? 'assets/backend/images/profile/user-1.jpg')  }}" height="35px" width="35px" style="border-radius: 50%" /></td>
                                <td>{{ $admin->name }} @if($admin->email_verified_at != null) <img src="{{ asset('assets/images/menu/quality.png') }}" alt="Verified Admin" height="20px" width="20px" /> @endif </td>
                                <td>{{ $admin->email }}</td>
                                @canany(['edit users','delete users'])
                                <td>
                                    @can('edit users')
                                    <a href="{{ url('user/edit/'.$admin->id) }}">
                                        <button class="btn btn-danger">
                                            Edit
                                        </button>
                                    </a>
                                    @endcan
                         
                                    @can('delete users')
                                        <button class="btn btn-danger remove" @if(Auth::user()->id == $admin->id) disabled @endif>
                                            Delete
                                        </button>
                                    @endcan
                                
                                </td>
                                @endcanany
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
            var url = "../../../../user/delete/" + id;
            window.location.href = url;
        }
    });


</script>
@endsection