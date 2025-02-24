@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="aiz-titlebar mb-4">
        <div class="h6 fw-700">
            <span>{{ translate('Conversations With ')}}</span>
            @if ($conversation->sender_id == Auth::user()->id && $conversation->receiver->shop != null)
                <a href="{{ route('shop.visit', $conversation->receiver->shop->slug) }}" class="">{{ $conversation->receiver->shop->name }}</a>
            @endif
        </div>
    </div>
    <div class="card rounded-0 shadow-none border">
        <div class="card-header bg-light">
            <div>
                <!-- Conversation title -->
                <h5 class="card-title fs-14 fw-700 mb-1">{{ $conversation->title }}</h5>
                <!-- Conversation Woth -->
                <p class="mb-0 fs-14 text-secondary fw-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" class="mr-2">
                        <g id="Group_24976" data-name="Group 24976" transform="translate(1053.151 256.688)">
                            <path id="Path_3012" data-name="Path 3012" d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z" transform="translate(-1178 -341)" fill="#b5b5bf"/>
                            <path id="Path_3013" data-name="Path 3013" d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1" transform="translate(-1182 -337)" fill="#b5b5bf"/>
                            <path id="Path_3014" data-name="Path 3014" d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1" transform="translate(-1181 -343.5)" fill="#b5b5bf"/>
                            <path id="Path_3015" data-name="Path 3015" d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1" transform="translate(-1181 -346.5)" fill="#b5b5bf"/>
                        </g>
                    </svg>
                    {{ translate('Between you and') }}
                    @if ($conversation->sender_id == Auth::user()->id)
                        {{ $conversation->receiver->shop ? $conversation->receiver->shop->name : $conversation->receiver->name }}
                    @else
                        {{ $conversation->sender->name }}
                    @endif
                </p>
            </div>
        </div>

        <div class="card-body">
            <!-- Conversations -->
            <ul class="list-group list-group-flush">
                @foreach($conversation->messages as $message)
                    <li class="list-group-item px-0">
                        <div class="media mb-2">
                            @if (Auth::user()->id != $message->user_id && $message->user->shop != null)
                                <a href="{{ route('shop.visit', $message->user->shop->slug) }}" class="">
                                    <img  class="avatar avatar-sm mr-3" src="{{ uploaded_asset($message->user->shop->logo) }}" 
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                </a>
                            @else
                                <img class="avatar avatar-sm mr-3" @if($message->user != null) src="{{ uploaded_asset($message->user->avatar_original) }}" @endif 
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                    
                            @endif
                            <div class="media-body">
                                <h6 class="mb-0 fw-600 mb-2">
                                    @if (Auth::user()->id != $message->user_id && $message->user->shop != null)
                                        <a href="{{ route('shop.visit', $message->user->shop->slug) }}" class="text-reset hov-text-primary">{{ $message->user->shop->name }}</a>
                                    @else
                                        {{ $message->user ? $message->user->name : '' }}

                                    @endif
                                </h6>
                                <p class="fs-12 text-secondary">
                                    {{ date('d.m.Y h:i:m', strtotime($message->created_at)) }}
                                </p>
                                    <p class="fs-12 text-secondary text-right">
                                        @if (Auth::user()->id != $message->user_id && $message->user->shop != null)
                                          @else
                                            @if ($message->deleted != 'Yes')
                                            <a href="javascript:void(0)" class="btn btn-soft-danger btn-icon btn-circle btn-sm hov-svg-white confirm-delete pull-right" data-href="{{route('messages.destroy', $message->id)}}" title="{{ translate('Delete') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="9.202" height="12" viewBox="0 0 9.202 12">
                                                <path id="Path_28714" data-name="Path 28714" d="M15.041,7.608l-.193,5.85a1.927,1.927,0,0,1-1.933,1.864H9.243A1.927,1.927,0,0,1,7.31,13.46L7.117,7.608a.483.483,0,0,1,.966-.032l.193,5.851a.966.966,0,0,0,.966.929h3.672a.966.966,0,0,0,.966-.931l.193-5.849a.483.483,0,1,1,.966.032Zm.639-1.947a.483.483,0,0,1-.483.483H6.961a.483.483,0,1,1,0-.966h1.5a.617.617,0,0,0,.615-.555,1.445,1.445,0,0,1,1.442-1.3h1.126a1.445,1.445,0,0,1,1.442,1.3.617.617,0,0,0,.615.555h1.5a.483.483,0,0,1,.483.483ZM9.913,5.178h2.333a1.6,1.6,0,0,1-.123-.456.483.483,0,0,0-.48-.435H10.516a.483.483,0,0,0-.48.435,1.6,1.6,0,0,1-.124.456ZM10.4,12.5V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Zm2.326,0V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Z" transform="translate(-6.478 -3.322)" fill="#d43533"/>
                                            </svg>
                                        </a>
                                        @endif 
                                    @endif        
                                </p>
                            </div>
                        </div>
                        <p class="fs-14 fw-400">
                            {{ $message->message }}
                              
                        </p>
                    </li>
                @endforeach
            </ul>
            
            <!-- Send message -->
            <form class="pt-4" action="{{ route('messages.store') }}" method="POST">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                <div class="form-group">
                    <textarea class="form-control rounded-0" rows="4" name="message" placeholder="{{ translate('Type your reply') }}" required></textarea>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary rounded-0 w-150px">{{ translate('Send') }}</button>
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
        $.post('{{ route('conversations.refresh') }}', {_token:'{{ @csrf_token() }}', id:'{{ encrypt($conversation->id) }}'}, function(data){
            $('#messages').html(data);
        })
    }

    refresh_messages(); // This will run on page load
    setInterval(function(){
        refresh_messages() // this will run after every 4 seconds
    }, 4000);
    </script>
@endsection
