<form action="{{ route('commissions.pay_to_seller') }}" method="POST">
    @csrf
    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
    <div class="modal-header">
    	<h5 class="modal-title h6">{{translate('Pay to Seller')}}</h5>
    	<button type="button" class="close" data-dismiss="modal">
    	</button>
    </div>
    <div class="modal-body">
      <table class="table table-striped table-bordered" >
          <tbody>
              <tr>
                  @if($customer->admin_to_pay >= 0)
                      <td>{{ translate('Due to customer') }}</td>
                      <td>{{ single_price($customer->admin_to_pay) }}</td>
                  @else
                      <td>{{ translate('Due to admin') }}</td>
                      <td>{{ single_price(abs($customer->admin_to_pay)) }}</td>
                  @endif
              </tr>
              @if ($customer->bank_payment_status == 1)
                  <tr>
                      <td>{{ translate('Bank Name') }}</td>
                      <td>{{ $customer->bank_name }}</td>
                  </tr>
                  <tr>
                      <td>{{ translate('Bank Account Name') }}</td>
                      <td>{{ $customer->bank_acc_name }}</td>
                  </tr>
                  <tr>
                      <td>{{ translate('Bank Account Number') }}</td>
                      <td>{{ $customer->bank_acc_no }}</td>
                  </tr>
                  <tr>
                      <td>{{ translate('Bank Routing Number') }}</td>
                      <td>{{ $customer->bank_routing_no }}</td>
                  </tr>
              @endif
          </tbody>
      </table>

      @if ($customer->admin_to_pay >= 0)
          <div class="form-group row">
              <label class="col-md-3 col-from-label" for="amount">{{translate('Amount')}}</label>
              <div class="col-md-9">
                  <input type="number" lang="en" min="0" step="0.01" name="amount" id="amount" value="{{ $customer->admin_to_pay }}" class="form-control" required>
              </div>
          </div>

          <div class="form-group row">
              <label class="col-md-3 col-from-label" for="payment_option">{{translate('Payment Method')}}</label>
              <div class="col-md-9">
                  <select name="payment_option" id="payment_option" class="form-control aiz-selectpicker" required>
                      <option value="">{{translate('Select Payment Method')}}</option>
                      @if($customer->cash == 1)
                          <option value="cash">{{translate('Cash')}}</option>
                      @endif
                      @if($customer->bank_payment_status == 1)
                          <option value="bank_payment">{{translate('Bank Payment')}}</option>
                      @endif
                  </select>
              </div>
          </div>
          <div class="form-group row" id="txn_div">
              <label class="col-md-3 col-from-label" for="txn_code">{{translate('Txn Code')}}</label>
              <div class="col-md-9">
                  <input type="text" name="txn_code" id="txn_code" class="form-control">
              </div>
          </div>
      @else
          <div class="form-group row">
              <label class="col-md-3 col-from-label" for="amount">{{translate('Amount')}}</label>
              <div class="col-md-9">
                  <input type="number" lang="en" min="0" step="0.01" name="amount" id="amount" value="{{ abs($customer->admin_to_pay) }}" class="form-control" required>
              </div>
          </div>
          <div class="form-group row" id="txn_div">
              <label class="col-md-3 col-from-label" for="txn_code">{{translate('Txn Code')}}</label>
              <div class="col-md-9">
                  <input type="text" name="txn_code" id="txn_code" class="form-control">
              </div>
          </div>
      @endif
    </div>
    <div class="modal-footer">
      @if ($customer->admin_to_pay > 0)
          <button type="submit" class="btn btn-primary">{{translate('Pay')}}</button>
      @else
          <button type="submit" class="btn btn-primary">{{translate('Clear due')}}</button>
      @endif
      <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
    </div>
</form>

<script>
  $(document).ready(function(){
      $('#payment_option').on('change', function() {
        if ( this.value == 'bank_payment')
        {
          $("#txn_div").show();
        }
        else
        {
          $("#txn_div").hide();
        }
      });
      $("#txn_div").hide();
      AIZ.plugins.bootstrapSelect('refresh');
  });
</script>
