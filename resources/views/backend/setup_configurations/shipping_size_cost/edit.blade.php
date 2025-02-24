@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
  <h5 class="mb-0 h6">{{translate('Cost & Size Information')}}</h5>
</div>

<div class="row">
  <div class="col-lg-12 mx-auto">
    <div class="card">
      <div class="card-body p-0">
       <form class="p-4" action="{{ route('shipping_cost_size.update', $Shipping_cost_size->id) }}" method="POST" enctype="multipart/form-data">
        <input name="_method" type="hidden" value="PATCH">
        <input type="hidden" name="lang" value="{{ $lang }}">
        @csrf
        <div class="card-header">
          <h5 class="mb-0 h6">{{ translate('From') }}</h5>
        </div>
        <br>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="country">{{translate('State')}}</label>
              <select class="select2 form-control aiz-selectpicker" name="from_state_id" data-selected="{{ $Shipping_cost_size->from_state_id }}" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
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
              <option value="{{ $Shipping_cost_size->from_city_id }}" >{{ $Shipping_cost_size->from_city->name }}</option>
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
            <select class="select2 form-control aiz-selectpicker" name="to_state_id" data-selected="{{ $Shipping_cost_size->to_state_id }}" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
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
              <option value="{{ $Shipping_cost_size->to_city_id }}" >{{ $Shipping_cost_size->to_city->name }}</option>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group mb-3">
            <label for="name">{{translate('Size')}}</label>
            <input type="number" min="0" step="0.01" placeholder="{{translate('Size')}}" name="size" class="form-control" value="{{ $Shipping_cost_size->size }}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group mb-3">
            <label for="name">{{translate('Cost')}}</label>
            <input type="number" min="0" step="0.01" placeholder="{{translate('Cost')}}" name="cost" class="form-control" value="{{ $Shipping_cost_size->cost }}" required>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group mb-3 text-right">
             <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
          </div>
        </div>
      </div>



    </form>
  </div>
</div>
</div>
</div>

@endsection
@section('script')

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
