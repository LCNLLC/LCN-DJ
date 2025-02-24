@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('Email Language Manager')}}</h1>
        </div>
        @can('add_blog')
        <div class="col text-right">
            <a href="{{ route('create.lang.template') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New Template')}}</span>
            </a>
        </div>
        @endcan
    </div>
</div>
<br>

<div class="card">
    <form class="" id="sort_blogs" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Email Template in Different Languages
') }}</h5>
            </div>
            
            <div class="col-md-4">
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
                    <th>{{translate('Template')}}</th>
                    <th >{{translate('Lang')}}</th>
                    <th >{{translate('Subject')}}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($langs as $key => $lang)
                <tr>
                    <td>
                        {{ ($key+1) + ($langs->currentPage() - 1) * $langs->perPage() }}
                    </td>
                    <td>{{ $lang->name }}</td>
                    <td><b>{{ $lang->lang }}</b></td>
                    <td>{{ $lang->subject }}</td>
                   <td class="text-right">
                        @can('test_preview_email')
                       <a class="btn btn-primary btn-md" href="#" onclick="sendTestEmail(event, {{ $lang->id }})" title="{{ translate('Test') }}">
                            Send Test Email
                        </a>
                        @endcan
                        @can('edit_blog')
                        <a class="btn btn-soft-primary btn-icon  btn-sm" href="{{ route('email.language.edit', $lang->id) }}" title="{{ translate('Edit') }}">
                            <i class="las la-pen"></i>
                        </a>
                        @endcan
                       
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $langs->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
@include('modals.delete_modal')
@endsection


@section('script')

<script type="text/javascript">

    function sendTestEmail(event, id) {
    event.preventDefault();
    event.stopPropagation();

    AIZ.plugins.notify('success', '{{ translate('Please wait, email is being sent...') }}');

    $.post('{{ route('email.template.preview.send') }}', {
        _token: '{{ csrf_token() }}',
        id: id
    }, function (data) {
        if (data.success) {
            AIZ.plugins.notify('success', '{{ translate('Test email sent successfully') }}');
        } else {
            AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
        }
    }).fail(function () {
        AIZ.plugins.notify('danger', '{{ translate('Request failed. Please try again.') }}');
    });
}

</script>

@endsection
