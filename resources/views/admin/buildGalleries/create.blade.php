@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.buildGallery.title_singular') }}
    </div>
    
    <div class="card-body">
        {{-- Validasi --}}
        @if ($errors->any())
        <div class="mb-5" role="alert">
            <div class="bg-danger text-white fw-bold rounded-t px-4 py-2">
                There's Something Wrong!
            </div>
            <div class="border border-t-0 rounded-b bg-danger px-4 py-3">
                <p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </p>
            </div>
        </div>
        @endif
        <form method="POST" action="{{ route("admin.builds.gallery.store", $build->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="col-form-label">{{ trans('cruds.buildGallery.fields.photo') }}</label>
                <input type="file"  multiple name="files[]" placeholder="Photos" accept="image/*;capture=camera"  class="d-block w-100 bg-light text-gray-700 border border-light rounded py-3 px-4 leading-tight focus:outline-none">

                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>

    </div>
</div>



@endsection

@section('scripts')
<script>
    Dropzone.options.photoDropzone = {
    url: '{{ route('admin.build-galleries.storeMedia') }}',
    maxFilesize: 5, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 1,
      width: 1024,
      height: 768
    },
    success: function (file, response) {
      $('form').find('input[name="photo"]').remove()
      $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="photo"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($buildGallery) && $buildGallery->photo)
      var file = {!! json_encode($buildGallery->photo) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
@endsection
