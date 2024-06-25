<?php

namespace App\Livewire;

use App\Helpers\ImageUpload;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post as PostModel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Livewire\WithFileUploads;

class Post extends Component
{
    use WithPagination, WithFileUploads;


    public $postId = null;

    // modal
    public $modalStatus = false;
    public $modalTitle = '';

    // search
    public $title_filter;
    public $status_filter;

    // post properties
    public $image;
    public $title;
    public $slug;
    public $content;
    public $mini_description;
    public $status;


    public function render()
    {
        $query = $this->filterPosts();
        $posts =  $query->latest("id")->paginate(8);

        return view('livewire.posts', ['posts' => $posts]);
    }

    private function rules()
    {
        $rules = [
            'image' => 'sometimes|nullable|image|mimes:png,jpg,jpeg|max:1024',
            'title' => 'required|string|max:255|unique:posts,title',
            'mini_description' => 'required|string',
            'content' => 'required|string|max:255',
            'status' => 'required|string|in:draft,published,scheduled,archived,inactive',
        ];

        // rule title in edit mode
        if ($this->postId) {
            $rules['title'] .= "," . $this->postId;
        }

        return $rules;
    }

    public function create()
    {
        self::resetFields();
        $this->modalTitle = 'Create new post';
        $this->openModal();
    }

    public function edit($id)
    {
        $post = self::findById($id);

        $this->modalTitle = "Edit post ({$post->title})";

        $this->postId = $post->id;
        $this->image = $post->image;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->content = $post->content;
        $this->mini_description = $post->mini_description;
        $this->status = $post->status;

        $this->openModal();
    }

    public function save()
    {

        $data = $this->validate($this->rules());

        $data = Arr::except($data,['image']);
        $data['slug']  = str()->slug($data['title']);

        $post = PostModel::updateOrCreate(['id' => $this->postId,], $data);

        $this->prepare_image_upload($post, 'image', 250);

        self::resetFields();
        self::closeModal();

        toastr()->success('Operation Successfull!');
    }

    public function updateStatus($id, $status)
    {
        $post = self::findById($id);
        $post->fill(['status' => $status])->save();

        toastr()->success('Status updated successfully!');
    }

    public function delete($id)
    {
        $post = self::findById($id);
        self::removeImage($post->image);
        $post->delete();

        toastr('Post deleted successfully', 'success', 'Success');
    }

    public function filterPosts()
    {
        $query = PostModel::query();

        $query->when($this->title_filter, function ($query, $searchTerm) {
            return $query->where('title', 'like', '%' . $searchTerm . '%');
        });
        $query->when($this->status_filter, function ($query, $searchTerm) {
            return $query->where('status', $searchTerm);
        });

        return $query;
    }


    static private function findById($id)
    {
        return PostModel::findOrFail($id);
    }

    private function resetFields()
    {
        $this->modalTitle = "";

        $this->postId = "";
        $this->image = "";
        $this->title = "";
        $this->slug = "";
        $this->content = "";
        $this->mini_description = "";
        $this->status = "";

        // reset input filters
        $this->resetInputFilters();
    }

    public function resetInputFilters()
    {
        $this->title_filter = '';
        $this->status_filter = '';
    }

    private function prepare_image_upload(PostModel $post, $fileName = 'image', $width)
    {
        // Get old image path from database or wherever you store it
        $imagePath = $post->$fileName; // Example old image path

        if ($this->image) {

            // Process image update
            $loaderSettings = [
                'file' => $this->image,
                'width' => $width, // Set your desired width (optional)
                'height' => null, // Set your desired height (optional)
                'quality' => 100, // Set image quality (optional)
                'storagePath' => 'uploads/images', // Set storage path
                'old_image' => $imagePath,
                'default' => $imagePath, // Default image if any
            ];

            $fileInformation = ImageUpload::update($loaderSettings);
            $imagePath= $fileInformation['file_path'];
        }

        $post->fill(['image' => $imagePath])->save();

    }

    static private function removeImage($path)
    {
        if (File::exists($path)) {
            @unlink(public_path($path));
        }
    }

    public function closeModal()
    {
        $this->modalStatus = false;
    }

    public function openModal()
    {
        $this->modalStatus = true;
    }
}
