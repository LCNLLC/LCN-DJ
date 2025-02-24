@extends('backend.layouts.app')

@section('content')

<div class="col-lg-10 mx-auto">
    <div class="card">
        <div class="card-header row gutters-5">
            <div class="text-center text-md-left">
                <h5 class="mb-md-0 h5">{{ $idea->subject }} #{{ $idea->code }}</h5>
               <div class="mt-2">
                   <span> {{ $idea->user->name }} </span>
                   <span class="ml-2"> {{ $idea->created_at }} </span>
                   <span class="badge badge-inline badge-secondary ml-2 text-capitalize"> 
                       {{ translate($idea->status) }} 
                   </span>
               </div>
            </div>
        </div>
        
        <div class="card-body">
            @can('reply_to_ideas')
                <form action="{{ route('idea.admin_store') }}" method="post" id="ticket-reply-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="idea_id" value="{{$idea->id}}" required>
                    <input type="hidden" name="status" value="{{ $idea->status }}" required>
                    <div class="form-group">
                        <textarea class="aiz-text-editor" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]' name="reply" required></textarea>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="attachments" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-dark" onclick="submit_reply('pending')">
                            {{ translate('Submit as') }} 
                            <strong>
                                <span class="text-capitalize"> 
                                    {{ translate($idea->status) }}
                                </span>
                            </strong>
                        </button>
                        <button type="submit" class="btn btn-icon btn-sm btn-dark" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"><i class="las la-angle-down"></i></button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" onclick="submit_reply('open')">{{ translate('Submit as') }} <strong>{{ translate('Open') }}</strong></a>
                            <a class="dropdown-item" href="#" onclick="submit_reply('solved')">{{ translate('Submit as') }} <strong>{{ translate('Solved') }}</strong></a>
                        </div>
                    </div>
                </form>
            @endcan
            <div class="pad-top">
                <ul class="list-group list-group-flush">
                    @foreach($idea->ideareplies as $ideareply)
                        <li class="list-group-item px-0">
                            <div class="media">
                                <a class="media-left" href="#">
                                    @if($ideareply->user->avatar_original != null)
                                        <span class="avatar avatar-sm mr-3"><img src="{{ uploaded_asset($ideareply->user->avatar_original) }}"></span>
                                    @else
                                        <span class="avatar avatar-sm mr-3"><img src="{{ static_asset('assets/img/avatar-place.png') }}"></span>
                                    @endif
                                </a>
                                <div class="media-body">
                                    <div class="">
                                        <span class="text-bold h6">{{ $ideareply->user->name }}</span>
                                        <p class="text-muted text-sm fs-11">{{$ideareply->created_at}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                @php echo $ideareply->reply; @endphp

                                <div class="mt-3">
                                @foreach ((explode(",",$ideareply->files)) as $key => $file)
                                    @php $file_detail = \App\Models\Upload::where('id', $file)->first(); @endphp
                                    @if($file_detail != null)
                                        <a href="{{ uploaded_asset($file) }}" download="" class="badge badge-lg badge-inline badge-light mb-1">
                                            <i class="las la-paperclip mr-2">{{ $file_detail->file_original_name.'.'.$file_detail->extension }}</i>
                                        </a>
                                    @endif
                                @endforeach
                                </div>
                            </div>
                        </li>
                    @endforeach
                    <li class="list-group-item px-0">
                        <div class="media">
                            <a class="media-left" href="#">
                                @if($idea->user->avatar_original != null)
                                    <span class="avatar avatar-sm m-3"><img src="{{ uploaded_asset($idea->user->avatar_original) }}"></span>
                                @else
                                    <span class="avatar avatar-sm m-3"><img src="{{ static_asset('assets/img/avatar-place.png') }}"></span>
                                @endif
                            </a>
                            <div class="media-body">
                                <div class="comment-header">
                                    <span class="text-bold h6 text-muted">{{ $idea->user->name }}</span>
                                    <p class="text-muted text-sm fs-11">{{ $idea->created_at }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            @php echo $idea->details; @endphp
                            <br>
                            @foreach ((explode(",",$idea->files)) as $key => $file)
                                @php $file_detail = \App\Models\Upload::where('id', $file)->first(); @endphp
                                @if($file_detail != null)
                                    <a href="{{ uploaded_asset($file) }}" download="" class="badge badge-lg badge-inline badge-light mb-1">
                                        <i class="las la-download text-muted">{{ $file_detail->file_original_name.'.'.$file_detail->extension }}</i>
                                    </a>
                                    <br>
                                @endif
                            @endforeach
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        function submit_reply(status){
            $('input[name=status]').val(status);
            if($('textarea[name=reply]').val().length > 0){
                $('#ticket-reply-form').submit();
            }
        }
    </script>
@endsection
