@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{ translate('Edit Slider') }}</h5>

</div>

<div class="col-lg-11 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <form action="{{ route('website.slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Slider Image -->
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="slider_image">{{ translate('Slider Image') }}</label>
                    <div class="col-sm-9">
                        <input type="file" id="slider_image" name="slider_image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">
                            {{ translate('Image size should be 1180x627 px.') }}
                        </small>

                        @error('slider_image')
                            <strong style="color: red">{{ $message }}</strong>
                        @enderror
                        <div class="my-2">
                            <strong>{{ translate('Previous Slider image') }}</strong>
                            <br>
                            <img width="200" src="{{ asset('storage/app/public/' . $slider->image) }}" alt="Slider Image" height="100">
                        </div>
                        <div id="image-preview"></div>
                    </div>

                </div>

                <!-- Position -->
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="position">{{ translate('Position') }}</label>
                    <div class="col-sm-9">
                        <input type="number" min="1" id="position" name="position" value="{{ $slider->position }}" class="form-control" required>
                        @error('position')
                            <strong style="color: red">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="status">{{ translate('Status') }}</label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" name="status">
                            <option value="1" @if($slider->status == 1) selected @endif>{{ translate('Active') }}</option>
                            <option value="0" @if($slider->status == 0) selected @endif>{{ translate('Inactive') }}</option>
                        </select>
                        @error('status')
                            <strong style="color: red">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>

                <!-- Link -->
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="link">{{ translate('Custom Link') }}</label>
                    <div class="col-sm-9">
                        <input type="url" id="link" name="link" value="{{ $slider->link }}" class="form-control" placeholder="{{ translate('Enter a custom URL (optional)') }}">
                        @error('link')
                            <strong style="color: red">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>

                <!-- Start Date -->
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="start_date">{{ translate('Start Date') }}</label>
                    <div class="col-sm-9">
                        <input type="date" id="start_date" name="start_date" value="{{ $slider->start_date }}" class="form-control">
                        @error('start_date')
                            <strong style="color: red">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>

                <!-- End Date -->
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="end_date">{{ translate('End Date') }}</label>
                    <div class="col-sm-9">
                        <input type="date" id="end_date" name="end_date" value="{{ $slider->end_date }}" class="form-control">
                        @error('end_date')
                            <strong style="color: red">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>

                <!-- Save Button -->
                <div class="form-group mb-0 text-right">
                    <a class="btn btn-info" href="{{ route('website.slider') }}">{{ Translate('back') }}</a>
                    <button  onclick="this.form.submit(); this.disabled=true; this.innerHTML='<span class=\'spinner-border spinner-border-sm\' role=\'status\' aria-hidden=\'true\'></span> {{ translate('Please wait...') }}';" type="submit" class="btn btn-primary">
                        {{ translate('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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
  document.addEventListener('DOMContentLoaded', function () {
    const sliderImageInput = document.getElementById('slider_image');

    // Add event listener to the file input
    if (sliderImageInput) {
        sliderImageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                const img = new Image();
                const reader = new FileReader();

                // Read the file as a Data URL
                reader.onload = function (e) {
                    img.onload = function () {
                        const width = img.width;
                        const height = img.height;
                        const maxWidth = 1180;
                        const maxHeight = 627;

                        if (width !== maxWidth || height !== maxHeight) {
                            alert(`The image must be ${maxWidth}x${maxHeight} pixels. Current dimensions: ${width}x${height}.`);
                            event.target.value = ''; // Clear the input field
                        } else {
                            // Show the image preview
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

                reader.onerror = function () {
                    alert('There was an error reading the file. Please try again.');
                };

                reader.readAsDataURL(file); // Trigger onload
            }
        });
    }

    // Set the minimum date for the start_date field
    const startDateInput = document.getElementById('start_date');
    if (startDateInput) {
        startDateInput.setAttribute('min', new Date().toISOString().split('T')[0]);
    }
});


    $(document).ready(function() {
        // Set the minimum date to today
        $('#start_date').attr('min', new Date().toISOString().split('T')[0]);
    });
</script>

@endsection
