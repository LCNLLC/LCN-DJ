@extends('backend.layouts.app')

@section('content')

<div class="card">
    <form class="" id="sort_support" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Evolution Idea') }}</h5>
            </div>
            <!-- <div class="col-md-2">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type ticket code & Enter') }}">
                </div>
            </div> -->
        </div>
    </form>

    <div class="card-body">
        <table class="aiz-table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th data-breakpoints="lg">{{ translate('Idea ID') }}</th>
                    <th data-breakpoints="lg">{{ translate('Sending Date') }}</th>
                    <th>{{ translate('Subject') }}</th>
                    <th data-breakpoints="lg">{{ translate('User') }}</th>
                    <th data-breakpoints="lg">{{ translate('Status') }}</th>
                    <th data-breakpoints="lg">{{ translate('Last reply') }}</th>
                    <th class="text-right">{{ translate('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                    @foreach ($ideas as $key => $idea)
                    @if ($idea->user != null)
                        <tr>
                            <td>#{{ $idea->code }}</td>
                            <td>{{ $idea->created_at }} @if($idea->viewed == 0) <span class="badge badge-inline badge-info">{{ translate('New') }}</span> @endif</td>
                            <td>{{ $idea->subject }}</td>
                            <td>{{ $idea->user->name }}</td>
                            <td>
                                @if ($idea->status == 'pending')
                                    <span class="badge badge-inline badge-danger">{{ translate('Pending') }}</span>
                                @elseif ($idea->status == 'open')
                                    <span class="badge badge-inline badge-secondary">{{ translate('Open') }}</span>
                                @else
                                    <span class="badge badge-inline badge-success">{{ translate('Solved') }}</span>
                                @endif
                            </td>
                            <td>
                                @if (count($idea->ideareplies) > 0)
                                    {{ $idea->ideareplies->last()->created_at }}
                                @else
                                    {{ $idea->created_at }}
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{route('idea.admin_show', encrypt($idea->id))}}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('View Details') }}">
                                    <i class="las la-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $ideas->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
