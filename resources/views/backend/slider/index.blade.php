@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center d-flex justify-content-between">
		<h1 class="h3">{{translate('All Sliders')}}</h1>
        <a href="{{ route('slider-export') }}" class="btn btn-success"> {{ translate('Export in Excel ') }}</a>
	</div>
</div>

<div class="row">
	<div class="@if(auth()->user()->can('add_brand')) col-lg-7 @else col-lg-12 @endif">
		<div class="card">
			<div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">{{ translate('Sliders') }}</h5>
				</div>
				<div class="col-md-4">
					<form class="" id="sort_brands" action="" method="GET">
						<div class="input-group input-group-sm">
							<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
						</div>
					</form>
				</div>
			</div>
			<div class="card-body">
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{translate('Image')}}</th>
                                <th>{{translate('Position')}}</th>
                                <th>{{translate('link')}}</th>
                                <th>{{translate('schedule ')}}</th>
                                <th>{{translate('Status')}}</th>
                                <th class="text-right">{{translate('Options')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sliders as $key => $slider)
                            <tr>
                                <td>{{ ($key+1) + ($sliders->currentPage() - 1)*$sliders->perPage() }}</td>

                                <td>
                                    <div class="">
                                        <div class="">

                                        @if ($slider->image)
                                            <a href="{{ asset('storage/app/public/' . $slider->image) }}" target="_blank">
                                                <img width="50" height="50" src="{{ asset('storage/app/public/' . $slider->image) }}" alt="Slider Image" class="img-fit">
                                            </a>

                                            @else
                                                <img width="50" src="{{ asset('assets/img/placeholder.jpg') }}" alt="Placeholder Image" class="img-fit">
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $slider->position }}
                                </td>
                                <td>
                                    @if($slider->link)
                                    {{ $slider->link }}
                                    @else
                                    {{ translate('Null') }}

                                    @endif
                                </td>
                                <td>
                                    @if($slider->start_date && $slider->end_date)
                                    @if(now()->greaterThan(\Carbon\Carbon::parse($slider->end_date)))
                                    <span style="width: 50px !important;" class="badge badge-warning">{{ translate('Expired') }}</span>

                                    @else
                                        {{ \Carbon\Carbon::parse($slider->start_date)->format('d M Y') }} to {{ \Carbon\Carbon::parse($slider->end_date)->format('d M Y') }}
                                    @endif
                                @else
                                    {{ translate('Not Scheduled') }}
                                @endif

                                </td>
                                <td>

                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="slider_status(this)" value="{{ $slider->id }}" type="checkbox" <?php if ($slider->status == 1) echo "checked"; ?> >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td class="text-right d-flex">
                                    @can('delete_slider')
                                    <a href="#" class=" mr-1 btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('website.slider.destroy', $slider->id)}}" title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
                                    @endcan

                                    <a href="{{ route('website.slider.edit', $slider->id) }}" class="btn btn-soft-success btn-icon btn-circle btn-sm "  title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
				<div class="aiz-pagination">
					{{ $sliders->appends(request()->input())->links() }}
				</div>
			</div>
		</div>
	</div>
	@can('add_job_type')
	<div class="col-md-5">
		<div class="card">
			<!-- Error Meassages -->
			@if ($errors->any())
			<br>
			<div class="pl-3 pr-3">
				<div class="alert alert-danger" >
					<ul>
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			</div>

			@endif
			<div class="card-header">

				<h5 class="mb-0 h6">{{ translate('Add Slider') }}</h5>
			</div>
			<div class="card-body">
				<form action="{{ route('website.slider.store') }}" method="POST" enctype="multipart/form-data">
					@csrf
					{{-- <div class="form-group mb-3">
                        <label for="slider-image">{{ translate('Slider Image (Recommended size: 1180x627)') }}</label>

						<div class="input-group" data-toggle="aizuploader" data-type="image">
							<div class="input-group-prepend">
								<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
							</div>
							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
							<input type="hidden" name="photo" class="selected-files" accept="image/*">
						</div>
						<div class="file-preview box sm">
						</div>
						<small class="text-muted">{{translate('This image is visible in home page slider. Use 1180x627 sizes images.')}}</small>
                        <small class="form-text text-danger">{{ translate('Upload an image with a size of 1180x627 pixels.') }}</small>
					</div> --}}
                    <div class="form-group">
                        <label for="photo">{{ translate('Upload Slider Image') }}</label>
                        <input type="file" id="slider-image" name="slider_image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">
                            {{ translate('Image size should be 1180x627 px.') }}
                        </small>
                        @error('slider_image')
                            <strong style="color: red">{{ $message }}</strong>
                        @enderror
                        <div id="image-preview"></div>
                    </div>
					<div class="form-group mb-3">
						<label >{{translate('position')}} </label>

						<input type="number" min="1" value="1" placeholder="{{ translate('position') }}" name="position" class="form-control" required>
                        @error('position')
                        <strong style="color: red">{{ $message }}</strong>
                        @enderror

					</div>
					<div class="form-group mb-3">
						<label for="status">{{translate('Status')}}</label>
						<select class="form-control aiz-selectpicker" name="status" >
							<option value="1" selected >{{ translate('Active') }}</option>
							<option value="0">{{ translate('In Active') }}</option>
						</select>
					</div>
                    @error('status')
                    <strong style="color: red">{{ $message }}</strong>
                    @enderror
                    <div class="form-group">
                        <label for="link"> {{ translate('Link') }} <span>{{ translate('Optional ') }}</span></label>
                        <input type="url" name="link" id="link" class="form-control" placeholder="{{ translate('Enter a custom URL (optional)') }}">
                    </div>
                       <!-- Start Date Field -->
                     <div class="form-group">
                        <label for="start_date">{{ translate('Start Date') }} <span class="text-muted">({{ translate('Optional') }})</span></label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
                        @error('start_date')
                            <strong style="color: red">{{ $message }}</strong>
                        @enderror
                    </div>

                    <!-- End Date Field -->
                    <div class="form-group">
                        <label for="end_date">{{ translate('End Date') }} <span class="text-muted">({{ translate('Optional') }})</span></label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
                        @error('end_date')
                            <strong style="color: red">{{ $message }}</strong>
                        @enderror
                    </div>
					<div class="form-group mb-3 text-right">
						<button onclick="this.form.submit(); this.disabled=true; this.innerHTML='<span class=\'spinner-border spinner-border-sm\' role=\'status\' aria-hidden=\'true\'></span> {{ translate('Please wait...') }}';" type="submit" class="btn btn-primary">{{translate('Save')}}</button>
					</div>

				</div>

			</form>
		</div>
	</div>
</div>
@endcan
</div>

@endsection

@section('modal')
@include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
	function sort_brands(el){
       // $('#sort_brands').submit();
   }
   function slider_status(el){
   	if(el.checked){
   		var status = 1;
   	}
   	else{
   		var status = 0;
   	}
   	$.post('{{ route('website.slider.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
   		if(data == 1){
   			AIZ.plugins.notify('success', '{{ translate('Slider updated successfully') }}');
   		}
   		else{
   			AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
   		}
   	});
   }
</script>

<script>
    document.getElementById('slider-image').addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (file) {
            const img = new Image();
            const reader = new FileReader();

            reader.onload = function(e) {
                img.onload = function () {
                    const width = img.width;
                    const height = img.height;
                    const maxWidth = 1180;
                    const maxHeight = 627;

                    if (width !== maxWidth || height !== maxHeight) {
                        alert(`The image must be ${maxWidth}x${maxHeight} pixels. Current dimensions: ${width}x${height}.`);
                        event.target.value = ''; // Clear the input field
                    } else {
                        // Show the image preview if dimensions are correct
                        const previewContainer = document.getElementById('image-preview');
                        previewContainer.innerHTML = ''; // Clear previous preview
                        const preview = document.createElement('img');
                        preview.src = e.target.result;
                        preview.style.maxWidth = '200px';
                        preview.style.maxHeight = '150px';
                        previewContainer.appendChild(preview);
                    }
                };
                img.src = e.target.result;
            };

            reader.readAsDataURL(file); // Read the image file and trigger onload
        }
    });

    $(document).ready(function() {
        // Set the minimum date to today
        $('#start_date').attr('min', new Date().toISOString().split('T')[0]);
    });
</script>

@endsection
