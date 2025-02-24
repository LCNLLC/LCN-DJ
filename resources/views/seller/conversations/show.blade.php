@extends('seller.layouts.app')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="h6">
        <span>{{ translate('Conversations With ')}}</span>
        @if ($conversation->sender_id == Auth::user()->id && $conversation->receiver->shop != null)
        <a href="{{ route('shop.visit', $conversation->receiver->shop->slug) }}" class="">{{ $conversation->receiver->shop->name }}</a>
        @endif
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h5 class="card-title fs-16 fw-600 mb-0">#{{ $conversation->title }}
            (
            {{ translate('Between you and') }}
            @if ($conversation->sender_id == Auth::user()->id)
            {{ $conversation->receiver->name }}
            @else
            {{ $conversation->sender->name }}
            @endif
            )
        </h5>
    </div>

    <div class="card-body">
        <ul class="list-group list-group-flush">
            @foreach($conversation->messages as $message)
            <li class="list-group-item px-0">
                <div class="media mb-2">
                  <img class="avatar avatar-xs mr-3" @if($message->user != null) src="{{ uploaded_asset($message->user->avatar_original) }}" @endif onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                  <div class="media-body">
                    <h6 class="mb-0 fw-600">
                        @if ($message->user != null)
                        {{ $message->user->name }}
                        @endif
                    </h6>
                    <p class="opacity-50">{{$message->created_at}}</p>
                    <p class="fs-12 text-secondary text-right">
                        @if (Auth::user()->id == $message->user_id )
                           @if ($message->deleted != 'Yes')
                                <a href="javascript:void(0)" class="btn btn-soft-danger btn-icon btn-circle btn-sm hov-svg-white confirm-delete pull-right" data-href="{{route('messages.destroy', $message->id)}}" title="{{ translate('Delete') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="9.202" height="12" viewBox="0 0 9.202 12">
                                        <path id="Path_28714" data-name="Path 28714" d="M15.041,7.608l-.193,5.85a1.927,1.927,0,0,1-1.933,1.864H9.243A1.927,1.927,0,0,1,7.31,13.46L7.117,7.608a.483.483,0,0,1,.966-.032l.193,5.851a.966.966,0,0,0,.966.929h3.672a.966.966,0,0,0,.966-.931l.193-5.849a.483.483,0,1,1,.966.032Zm.639-1.947a.483.483,0,0,1-.483.483H6.961a.483.483,0,1,1,0-.966h1.5a.617.617,0,0,0,.615-.555,1.445,1.445,0,0,1,1.442-1.3h1.126a1.445,1.445,0,0,1,1.442,1.3.617.617,0,0,0,.615.555h1.5a.483.483,0,0,1,.483.483ZM9.913,5.178h2.333a1.6,1.6,0,0,1-.123-.456.483.483,0,0,0-.48-.435H10.516a.483.483,0,0,0-.48.435,1.6,1.6,0,0,1-.124.456ZM10.4,12.5V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Zm2.326,0V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Z" transform="translate(-6.478 -3.322)" fill="#d43533"/>
                                    </svg>
                                </a>
                            @endif 
                        @else

                        @endif        
                    </p>
                </div>
            </div>
            <p>
                {{ $message->message }}
            </p>

        </li>
        @endforeach
    </ul>
    <form class="pt-4" action="{{ route('seller.conversations.message_store') }}" method="POST">
        @csrf
        <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
        <div class="form-group">
            <textarea class="form-control" rows="4" name="message" placeholder="{{ translate('Type your reply') }}" required></textarea>
        </div>
        <div class="form-group mb-0 text-right">
            <button type="submit" class="btn btn-primary">{{ translate('Send') }}</button>
        </div>
    </form>
</div>
</div>
@endsection
@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')

@endsection

@section('script')
<script type="text/javascript">
    function refresh_messages(){
        $.post('{{ route('seller.conversations.refresh') }}', {_token:'{{ @csrf_token() }}', id:'{{ encrypt($conversation->id) }}'}, function(data){
            $('#messages').html(data);
        })
    }

    refresh_messages(); // This will run on page load
    setInterval(function(){
        refresh_messages() // this will run after every 5 seconds
    }, 4000);
</script>
@endsection
