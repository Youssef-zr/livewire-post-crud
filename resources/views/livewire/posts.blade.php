<div>
    <div class="card card-primary">
        <div class="card-header d-flex justify-content-end">
            <button class="btn btn-primary" wire:click='create'>New Post</button>
        </div>

        <div class="card-body">

            <!-- filter fields -->
            <div class="p-3 mb-3 filter-data bg-light">

                <form wire:submit.prevent='filterPosts'>
                    <div class="row align-items-end">
                        <!-- title field -->
                        <div class="col-md-5">
                            <div class="mb-0 form-group">
                                {!! Form::label('title', 'Title', ['class' => 'form-label']) !!}
                                {!! Form::text('title', old('title'), [
                                    'class' => 'form-control',
                                    'placeholder' => 'Post title',
                                    'wire:model' => 'title_filter',
                                    'wire:keydown' => 'filterPosts',
                                ]) !!}
                            </div>
                        </div>

                        <!-- status field -->
                        <div class="col-md-5">
                            <div class="mb-0 form-group">
                                {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                                {!! Form::select(
                                    'status',
                                    [
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'scheduled' => 'Scheduled',
                                        'archived' => 'Archived',
                                        'inactive' => 'Inactive',
                                    ],
                                    old('status'),
                                    [
                                        'class' => 'form-select',
                                        'placeholder' => 'Post status',
                                        'rows' => '3',
                                        'wire:model' => 'status_filter',
                                        'wire:change' => 'filterPosts',
                                    ],
                                ) !!}
                            </div>
                        </div>

                        <!-- filter button -->
                        <div class="col-md-2">
                            <div class="gap-3 d-flex">
                                <button class="btn btn-primary w-50">Search</button>
                                <button class="btn btn-secondary w-50"
                                    wire:click.prevent='resetInputFilters'>Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- posts table content -->
            <table class="table text-center table-light table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Descripiton</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td><img src="{{ $post->image }}" class="img-thumbnail" style="width: 90px;height:90px">
                            </td>
                            <td>{{ $post->title }}</td>
                            <td>
                                <p title="{{ $post->content }}" style="cursor:default">
                                    {{ str()->limit($post->content, 20, '...') }}
                                </p>
                            </td>
                            <td>
                                {!! Form::select(
                                    "posts.{$loop->index}.status",
                                    [
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'scheduled' => 'Scheduled',
                                        'archived' => 'Archived',
                                        'inactive' => 'Inactive',
                                    ],
                                    $post->status,
                                    [
                                        'class' => 'form-select',
                                        'wire:model' => "posts.{$loop->index}.status",
                                        'wire:change' => "updateStatus($post->id, \$event.target.value)",
                                    ],
                                ) !!}

                            </td>
                            <td>{{ $post->created_at_human }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm"
                                    wire:click="edit({{ $post->id }})">Edit</button>
                                <button class="btn btn-danger btn-sm" wire:click="delete({{ $post->id }})"
                                    wire:confirm="Are you sure you want to delete this post?">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <p class="mb-0 alert alert-danger">Table is empty!!! </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($posts)
                {{ $posts->links() }}
            @endif
        </div>
    </div>


    <!-- modal create/update post -->
    @if ($modalStatus)
        <div class="post-modal">
            <div class="modal show d-block" id="myModal">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title"> {{ $modalTitle }} </h4>

                            <button type="button" class="btn-close" wire:click='closeModal'></button>
                        </div>

                        <!-- Modal body -->
                        <form wire:submit.prevent="save" enctype="multipart/form-data">
                            <div class="modal-body">

                                <!-- image live preview -->
                                @if ($image)
                                    <div class="mb-3 image-preview img-thumbnail" style="width: 150px;height:150px">

                                        @if (!empty($postId) and getType($image) == "string")
                                            <img src="{{ $image }}" class="w-100 h-100">
                                        @else
                                            <img src="{{ $image->temporaryUrl() }}" class="w-100 h-100">
                                        @endif
                                    </div>
                                @endif

                                <!-- image field -->
                                <div class="form-group mb-3{{ $errors->has('image') ? ' has-error' : '' }}">
                                    {!! Form::label('image', 'image', ['class' => 'form-label']) !!}
                                    {!! Form::file('image', [
                                        'class' => 'form-control',
                                        'wire:model' => 'image',
                                    ]) !!}

                                    <small class="text-danger">{{ $errors->first('image') }}</small>
                                </div>

                                <!-- title field -->
                                <div class="form-group mb-3{{ $errors->has('title') ? ' has-error' : '' }}">
                                    {!! Form::label('title', 'Title *', ['class' => 'form-label']) !!}
                                    {!! Form::text('title', old('title'), [
                                        'class' => 'form-control',
                                        'placeholder' => 'Post title',
                                        'wire:model' => 'title',
                                    ]) !!}
                                    <small class="text-danger">{{ $errors->first('title') }}</small>
                                </div>

                                <!-- mini_description field -->
                                <div class="form-group mb-3{{ $errors->has('mini_description') ? ' has-error' : '' }}">
                                    {!! Form::label('mini_description', 'Mini description *', ['class' => 'form-label']) !!}
                                    {!! Form::textarea('mini_description', old('mini_description'), [
                                        'class' => 'form-control',
                                        'placeholder' => 'Post mini description',
                                        'rows' => '2',
                                        'wire:model' => 'mini_description',
                                    ]) !!}
                                    <small class="text-danger">{{ $errors->first('mini_description') }}</small>
                                </div>

                                <!-- content field -->
                                <div class="form-group mb-3{{ $errors->has('content') ? ' has-error' : '' }}">
                                    {!! Form::label('content', 'Content *', ['class' => 'form-label']) !!}
                                    {!! Form::textarea('content', old('content'), [
                                        'class' => 'form-control',
                                        'placeholder' => 'Post Content',
                                        'rows' => '5',
                                        'wire:model' => 'content',
                                    ]) !!}
                                    <small class="text-danger">{{ $errors->first('content') }}</small>
                                </div>

                                <!-- status field -->
                                <div class="form-group mb-3{{ $errors->has('status') ? ' has-error' : '' }}">
                                    {!! Form::label('status', 'status *', ['class' => 'form-label']) !!}
                                    {!! Form::select(
                                        'status',
                                        [
                                            'draft' => 'Draft',
                                            'published' => 'Published',
                                            'scheduled' => 'Scheduled',
                                            'archived' => 'Archived',
                                            'inactive' => 'Inactive',
                                        ],
                                        old('status'),
                                        [
                                            'class' => 'form-select',
                                            'placeholder' => 'Post status',
                                            'rows' => '3',
                                            'wire:model' => 'status',
                                        ],
                                    ) !!}
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-danger" wire:click='closeModal'>Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-backdrop show" wire:click='closeModal'></div>
        </div>
    @endif
</div>
