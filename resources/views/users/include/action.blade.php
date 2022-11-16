<td>
    <a href="{{ route('users.show', $model->id) }}" class="btn btn-outline-success btn-sm">
        <i class="fa fa-eye"></i>
    </a>

    @can('user edit')
        <a href="{{ route('users.edit', $model->id) }}" class="btn btn-outline-primary btn-sm">
            <i class="fa fa-pencil-alt"></i>
        </a>
    @endcan

    @can('user delete')
        <form action="{{ route('users.destroy', $model->id) }}" method="post" class="d-inline"
            onsubmit="return confirm('Are you sure to delete this record?')">
            @csrf
            @method('delete')

            <button class="btn btn-outline-danger btn-sm">
                <i class="ace-icon fa fa-trash-alt"></i>
            </button>
        </form>
    @endcan
</td>
