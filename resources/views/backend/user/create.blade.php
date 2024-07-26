@extends('backend.layout.theme')
@section('main')
 <!--  Content Begin Here -->
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">User Create</h5>

                
                <form action="{{ route('User Store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" name="name" id="" class="form-control" placeholder="" aria-describedby="helpId"/>
                    </div>

                    <div class="mb-3">
                      <label for="" class="form-label">Choose file</label>
                      <input type="file" class="form-control" name="image" id="" placeholder="" aria-describedby="fileHelpId"/>
                    </div>
                    

                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="text" name="email" id="" class="form-control" placeholder="" aria-describedby="helpId"/>
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label">Phone</label>
                      <input type="text" name="phone" id="" class="form-control" placeholder="" aria-describedby="helpId"/>
                  </div>

                    <div class="mb-3">
                        <label for="" class="form-label">User Type</label>
                        <select class="form-select" name="role_id" id="" required>
                            <option selected>Select One</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    

                    <div class="mb-3">
                        <label for="" class="form-label">Password</label>
                        <input type="text" name="password" id="" class="form-control" placeholder="" aria-describedby="helpId"/>
                    </div>

                    <button type="submit" class="btn btn-primary"> Submit
                    </button>
                     
                </form>
                
                

    </div>
  </div>
</div>
</div>
 <!--  Content End Here -->

@endsection
