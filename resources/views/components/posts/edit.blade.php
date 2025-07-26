@push('stylesfilepond')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
@endpush
<div class="relative p-4 bg-white rounded-lg border dark:bg-gray-800 sm:p-5">
    <!-- Modal header -->
    <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Post</h3>
    </div>

    <!-- Modal body -->
    <form action="/dashboard/{{ $post->slug }}" method="POST" class="space-y-4" id="post-form">
        @csrf
        @method('PATCH')
        <div class="grid gap-4 mb-4 sm:grid-cols-2">
            <div>
                <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                <input type="text" name="title" id="title" class="@error('title') border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type post title" autofocus value="{{ old('title') ?? $post->title }}">
                {{-- The above line uses the null coalescing operator to set the value to old('title') if it exists, otherwise it uses $post->title --}}
                {{-- <input type="text" name="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type post title" autofocus value="{{ old('title') }} ?? {{ $post->title }}"> --}}
                @error('title')    
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div>
                <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                <select name="category_id" id="category" class="@error('category_id') border-red-500 text-red-700 placeholder-red-700 focus:ring-red-500 focus:border-red-500 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option selected="" value="">Select category</option>
                    @foreach (App\Models\Category::get() as $category)
                        <option value="{{ $category->id }}" @selected((old('category_id') ?? $post->category->id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')    
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="sm:col-span-2">
                <label for="body" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Body</label>
                <textarea name="body" id="body" rows="4" class="hidden @error('body') border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 @enderror block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Write post body here">{{ old('body') ?? $post->body }}</textarea>
            </div>
                @error('body')    
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                </p>
                @enderror
        </div>
        <div class="mt-2" id="editor">{!! old('body', $post->body) !!}</div>
        @error('body')    
            <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                {{ $message }}
            </p>
        @enderror

        <div class="mt-4 flex justify-start items-center space-x-4">
            <button type="submit" class="text-white inline-flex items-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 cursor-pointer">
                {{-- <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg> --}}
                Update post
            </button>
            <a href="/dashboard" class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                {{-- <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg> --}}
                Cancel
            </a>
        </div>
    </form>
</div>
@push('scriptsfilepond')
    <!-- Include the Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <!-- Initialize Quill editor -->
    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Write your post content here...',
            modules: {
                toolbar: true,
            }
            // modules: {   
            //     toolbar: [
            //         ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            //         ['blockquote', 'code-block'],
            //         [{ 'header': 1 }, { 'header': 2 }],               // custom button values
            //         [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            //         [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
            //         [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
            //         [{ 'direction': 'rtl' }],                          // text direction
            //         [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
            //         [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            //         [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            //         [{ 'font': [] }],
            //         ['clean']                                         // remove formatting button
            //     ]
            // }
        });

        const postForm = document.querySelector('#post-form');
        const postBody = document.querySelector('#body');
        const quillEditor = document.querySelector('#editor');
        
        postForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
            // Get the HTML content from the Quill editor
            const content = quillEditor.children[0].innerHTML;
            // Set the value of the hidden input field to the Quill content
            postBody.value = content;
            // Submit the form
            this.submit();
        });
    </script>
@endpush