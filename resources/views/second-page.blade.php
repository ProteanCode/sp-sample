@extends('layout')

@section('content')

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Author name</th>
                <th>Author surname</th>
                <th>Filename</th>
                <th>Hash</th>
                <th>Disk</th>
                <th>Path</th>
                <th>Extension</th>
                <th>Width</th>
                <th>Height</th>
                <th>Size (Bytes)</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($paginator as $image)
            <tr>
                <td>{{ $image->id }}</td>
                <td>
                    <!-- There could be srcset based on scaled down children images, but I had no more time -->
                    <img width="200" src="data:image/{{ $image->extension }};base64,{{ $image->base64_content }}" alt="{{ $image->name }}">
                </td>
                <td>{{ $image->authors->first()->name }}</td>
                <td>{{ $image->authors->first()->surname }}</td>
                <td>{{ $image->filename }}</td>
                <td>{{ $image->hash }}</td>
                <td>{{ $image->disk }}</td>
                <td>{{ $image->path }}</td>
                <td>{{ $image->extension }}</td>
                <td>{{ $image->width }}</td>
                <td>{{ $image->height }}</td>
                <td>{{ $image->size_in_bytes }}</td>
                <td>{{ $image->created_at }}</td>
                <td>{{ $image->updated_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <nav>
        <ul>
            @if ($paginator->onFirstPage())
                <li><span>Wstecz</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">Wstecz</a></li>
            @endif

            @foreach ($paginator->links() as $page => $url)
                <li class="{{ $paginator->currentPage() === $page ? 'active' : '' }}">
                    <a href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">Dalej</a></li>
            @else
                <li><span>Dalej</span></li>
            @endif
        </ul>
    </nav>
@endsection

