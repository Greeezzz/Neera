@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <div class="card-coffee">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-coffee-800 dark:text-coffee-100 mb-6">Create Story</h2>
            
            <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Media Upload -->
                <div>
                    <label for="media" class="block text-sm font-medium text-coffee-700 dark:text-coffee-300 mb-2">
                        Upload Photo or Video
                    </label>
                    <div class="mt-1">
                        <input id="media" name="media" type="file" class="block w-full text-sm text-coffee-500 dark:text-coffee-400
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-coffee-50 file:text-coffee-700
                            hover:file:bg-coffee-100
                            dark:file:bg-coffee-800 dark:file:text-coffee-300
                            dark:hover:file:bg-coffee-700
                            border border-coffee-300 dark:border-coffee-600 rounded-lg
                            focus:ring-coffee-500 focus:border-coffee-500" 
                            accept="image/*,video/*" required>
                    </div>
                    <p class="mt-2 text-sm text-coffee-500 dark:text-coffee-400">
                        PNG, JPG, GIF, MP4 up to 10MB
                    </p>
                    @error('media')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Caption -->
                <div>
                    <label for="caption" class="block text-sm font-medium text-coffee-700 dark:text-coffee-300 mb-2">
                        Caption (Optional)
                    </label>
                    <textarea 
                        id="caption" 
                        name="caption" 
                        rows="3" 
                        class="input-coffee w-full"
                        placeholder="Write a caption for your story..."
                    >{{ old('caption') }}</textarea>
                    @error('caption')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Section -->
                <div id="preview-section" class="hidden">
                    <label class="block text-sm font-medium text-coffee-700 dark:text-coffee-300 mb-2">
                        Preview
                    </label>
                    <div class="border-2 border-coffee-200 dark:border-coffee-700 rounded-lg overflow-hidden">
                        <img id="image-preview" class="hidden w-full h-64 object-cover" />
                        <video id="video-preview" class="hidden w-full h-64 object-cover" controls></video>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between pt-4">
                    <a href="{{ url()->previous() }}" class="px-4 py-2 text-coffee-600 dark:text-coffee-300 hover:text-coffee-800 dark:hover:text-coffee-100 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="btn-coffee-primary">
                        Share Story
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('media').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const previewSection = document.getElementById('preview-section');
        const imagePreview = document.getElementById('image-preview');
        const videoPreview = document.getElementById('video-preview');
        
        previewSection.classList.remove('hidden');
        
        if (file.type.startsWith('image/')) {
            imagePreview.src = URL.createObjectURL(file);
            imagePreview.classList.remove('hidden');
            videoPreview.classList.add('hidden');
        } else if (file.type.startsWith('video/')) {
            videoPreview.src = URL.createObjectURL(file);
            videoPreview.classList.remove('hidden');
            imagePreview.classList.add('hidden');
        }
    }
});
</script>
@endsection