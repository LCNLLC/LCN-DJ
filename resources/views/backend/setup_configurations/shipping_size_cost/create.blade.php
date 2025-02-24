@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
 <div class="row align-items-center">
  <div class="col-md-12">
   <h1 class="h3">{{translate('Shipping Cost & Size')}}</h1>
</div>
</div>
</div>
<div class="row">
    <div class="col-md-12">
      <div class="card">
       <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Add New Cost') }}</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('shipping_cost_size.store') }}" method="POST">
         @csrf
         <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('From') }}</h5>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="country">{{translate('State')}}</label>
                    <select class="select2 form-control aiz-selectpicker" name="from_state_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                        @foreach ($states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="country">{{translate('City')}}</label>
                      <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="from_city_id" required>

               </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('To') }}</h5>
                </div>
                <br>

            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="country">{{translate('State')}}</label>
                <select class="select2 form-control aiz-selectpicker" name="to_state_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                    @foreach ($states as $state)
                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="country">{{translate('City')}}</label>
             <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="to_city_id" required>

               </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="name">{{translate('Size')}}</label>
                <input type="number" min="0" step="0.01" placeholder="{{translate('Size')}}" name="size" class="form-control" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
              <label for="name">{{translate('Cost')}}</label>
              <input type="number" min="0" step="0.01" placeholder="{{translate('Cost')}}" name="cost" class="form-control" required>
          </div>
      </div>
      <div class="col-md-12">
          <div class="form-group mb-3 text-right">
              <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
          </div>
      </div>
  </div>
</form>
</div>

</div>
</div>
</div>

@endsection

@section('modal')
@include('modals.delete_modal')
@endsection


@section('script')
<script type="text/javascript">
    function sort_cities(el){
        $('#sort_cities').submit();
    }

    function update_status(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('{{ route('shipping_cost_size.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
            if(data == 1){
                AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }

</script>
<script>
    $(document).ready(function () {

        $(document).on('change', '[name=from_state_id]', function() {
            var state_id = $(this).val();
            get_from_city(state_id);
        });
        
  
        function get_from_city(state_id) {
            $('[name="from_city_id"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-all-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="from_city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

         $(document).on('change', '[name=to_state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });
        
  
        function get_city(state_id) {
            $('[name="to_city_id"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-all-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="to_city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }


    });
</script>

@endsection
