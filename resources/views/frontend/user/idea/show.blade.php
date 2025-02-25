@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="card rounded-0 shadow-none border">
        <!-- Ticket info -->
        <div class="card-header border-bottom-0">
            <div class="text-center text-md-left">
                <h5 class="mb-md-0 fs-20 fw-700 text-dark">{{ $idea->subject }} #{{ $idea->code }}</h5>
               <div class="mt-4 fs-14">
                   <span class="fw-700 text-dark"> {{ $idea->user->name }} </span>
                   <span class="ml-2 text-secondary"> {{ $idea->created_at }} </span>
                   <span class="badge badge-inline badge-gray ml-2 p-3 fs-12" style="border-radius: 25px; min-width: 80px !important;"> {{ ucfirst($idea->status) }} </span>
               </div>
            </div>
        </div>
        <hr class="mx-4">
        
        <div class="card-body">
            <!-- Reply form -->
            <form action="{{route('idea.seller_store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="idea_id" value="{{$idea->id}}" required>
                <input type="hidden" name="user_id" value="{{$idea->user_id}}">
                <div class="form-group">
                    <textarea class="aiz-text-editor rounded-0" name="reply" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]' required></textarea>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium rounded-0">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="attachments" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-primary rounded-0 w-150px" onclick="submit_reply('pending')">{{ translate('Send Reply') }}</button>
                </div>
            </form>
            
            <div class="pad-top">
                <ul class="list-group list-group-flush mt-3">
                    <!-- Replies -->
                    @foreach($idea->ideareplies as $ideareply)
                        <li class="list-group-item px-0 border-bottom-0">
                            <div class="media">
                                <a class="media-left" href="#">
                                    @if($ideareply->user->avatar_original != null)
                                        <span class="avatar avatar-sm mr-3">
                                            <img src="{{ uploaded_asset($ideareply->user->avatar_original) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                        </span>
                                    @else
                                        <span class="avatar avatar-sm mr-3">
                                            <img src="{{ static_asset('assets/img/avatar-place.png') }}">
                                        </span>
                                    @endif
                                </a>
                                <div class="media-body">
                                    <div class="comment-header">
                                        <span class="fs-14 fw-700 text-dark">{{ $ideareply->user->name }}</span>
                                        <p class="text-muted text-sm fs-12 mt-2">{{ date('d.m.Y h:i:m', strtotime($ideareply->created_at)) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="fs-14 fw-400">
                                {!! $ideareply->reply !!}
                                <br>
                                <br>
                                @foreach ((explode(",",$ideareply->files)) as $key => $file)
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
                    @endforeach

                    <!-- Ticket Details -->
                    <li class="list-group-item px-0">
                        <div class="media">
                            <a class="media-left" href="#">
                                @if($idea->user->avatar_original != null)
                                    <span class="avatar avatar-sm mr-3">
                                        <img src="{{ uploaded_asset($idea->user->avatar_original) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                    </span>
                                @else
                                    <span class="avatar avatar-sm mr-3">
                                        <img src="{{ static_asset('assets/img/avatar-place.png') }}">
                                    </span>
                                @endif
                            </a>
                            <div class="media-body">
                                <div class="comment-header">
                                    <span class="fs-14 fw-700 text-dark">{{ $idea->user->name }}</span>
                                    <p class="text-muted text-sm fs-12 mt-2">{{ date('d.m.Y h:i:m', strtotime($idea->created_at)) }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            {!! $idea->details !!}
                            <br>
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
@endsection
