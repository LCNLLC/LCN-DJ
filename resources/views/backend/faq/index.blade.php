@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('FAQs')}}</h1>
        </div>
        @can('add_blog')
        <div class="col text-right">
            <a href="{{ route('faq.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New FAQ')}}</span>
            </a>
        </div>
        @endcan
    </div>
</div>
<br>

<div class="card">
    <form class="" id="sort_faq" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('FAQs') }}</h5>
            </div>
            
            <div class="col-md-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control form-control-sm" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type & Enter') }}">
                </div>
            </div>
        </div>
    </form>
    <div class="card-body">
        <table class="table mb-0 aiz-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Artical Code')}}</th>
                    <th >{{translate('Lang')}}</th>
                    <th >{{translate('Subject')}}</th>
                    <th>{{translate('Show/Hide')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($faqs as $key => $faq)
                <tr>
                    <td>
                        {{ ($key+1) + ($faqs->currentPage() - 1) * $faqs->perPage() }}
                    </td>
                    <td>{{ $faq->artical_code }}</td>
                    <td><b>{{ $faq->lang }}</b></td>
                    <td>{{ $faq->subject }}</td>
                    <td>
                        <label class="aiz-switch aiz-switch-success mb-0">
                          <input onchange="update_status(this)" value="{{ $faq->id }}" type="checkbox" <?php if($faq->status == 1) echo "checked";?> >
                          <span class="slider round"></span>
                      </label>
                  </td>
                  <td class="text-right">
                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('faq.edit', ['id'=>$faq->id, 'lang'=>env('DEFAULT_LANGUAGE')]) }}" title="{{ translate('Edit') }}">
                        <i class="las la-edit"></i>
                    </a>
                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('faq.destroy', $faq->id)}}" title="{{ translate('Delete') }}">
                        <i class="las la-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
    <div class="aiz-pagination">
        {{ $faqs->appends(request()->input())->links() }}
    </div>
</div>
</div>

@endsection

@section('modal')
@include('modals.delete_modal')
@endsection


@section('script')

    <script type="text/javascript">


        function update_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('faq.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

    </script>

@endsection
