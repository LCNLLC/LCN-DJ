<div class="modal-body">

  <div class="text-center">
      <span class="avatar avatar-xxl mb-3">
          <img src="{{ uploaded_asset($courier->user->avatar_original) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
      </span>
      <h1 class="h5 mb-1">{{ $courier->user->name }}</h1>
      <p class="text-sm text-muted">{{ $courier->name }}</p>

      <div class="pad-ver btn-groups">
          <a href="{{ $courier->facebook }}" class="btn btn-icon demo-pli-facebook icon-lg add-tooltip" data-original-title="Facebook" data-container="body"></a>
          <a href="{{ $courier->twitter }}" class="btn btn-icon demo-pli-twitter icon-lg add-tooltip" data-original-title="Twitter" data-container="body"></a>
          <a href="{{ $courier->google }}" class="btn btn-icon demo-pli-google-plus icon-lg add-tooltip" data-original-title="Google+" data-container="body"></a>
      </div>
  </div>
  <hr>

  <!-- Profile Details -->
  <h6 class="mb-4">{{translate('About')}} {{ $courier->user->name }}</h6>
  <p><i class="demo-pli-map-marker-2 icon-lg icon-fw mr-1"></i>{{ $courier->address }}</p>
  <p><a href="{{ route('shop.visit', $courier->slug) }}" class="btn-link"><i class="demo-pli-internet icon-lg icon-fw mr-1"></i>{{ $courier->name }}</a></p>
  <p><i class="demo-pli-old-telephone icon-lg icon-fw mr-1"></i>{{ $courier->user->phone }}</p>

  <h6 class="mb-4">{{translate('Payout Info')}}</h6>
  <p>{{translate('Bank Name')}} : {{ $courier->bank_name }}</p>
  <p>{{translate('Bank Acc Name')}} : {{ $courier->bank_acc_name }}</p>
  <p>{{translate('Bank Acc Number')}} : {{ $courier->bank_acc_no }}</p>
  <p>{{translate('Bank Routing Number')}} : {{ $courier->bank_routing_no }}</p>

  <br>

  <div class="table-responsive">
      <table class="table table-striped mar-no">
          <tbody>
    
          <tr>
              <td>{{ translate('Total Orders') }}</td>
              <td>{{ App\Models\Order::where('courier_id', $courier->user->id)->get()->count() }}</td>
          </tr>
          <tr>
              <td>{{ translate('Wallet Balance') }}</td>
              <td>{{ single_price($courier->user->balance) }}</td>
          </tr>
          </tbody>
      </table>
  </div>
</div>
