<img
    src="{{ $data->headlineImage && Storage::exists($data->headlineImage->path . $data->headlineImage->hash_name) ? asset(Storage::url($data->headlineImage->path . $data->headlineImage->hash_name)) : '' }}"
    class="max-h-[200px] block rounded-lg mx-auto {{ $data->headlineImage && Storage::exists($data->headlineImage->path . $data->headlineImage->hash_name) ? 'border mt-3' : 'hidden' }}"/>
