@extends('layouts.admin')

@section('content')
    @if(auth()->user()->isAdmin())
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('events.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.event.title_singular') }}
                </a>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.event.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-Event">
                    <thead>
                        <tr>
                            <th>{{ trans('cruds.event.fields.id') }}</th>
                            <th>{{ trans('cruds.event.fields.name') }}</th>
                            <th>{{ trans('cruds.event.fields.start_time') }}</th>
                            <th>{{ trans('cruds.event.fields.end_time') }}</th>
                            <th>Lokasi</th> <!-- Kolom lokasi -->
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            <tr>
                                <td>{{ $event->id }}</td>
                                <td>{{ $event->name }}</td>
                                <td>{{ $event->start_time }}</td>
                                <td>{{ $event->end_time }}</td>
                                <td>{{ $event->location ?? 'Lokasi tidak tersedia' }}</td> <!-- Menampilkan lokasi -->
                                <td>
                                @can('event_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('events.show', $event->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @if(auth()->user()->isAdmin())
                                    <a class="btn btn-xs btn-info" href="{{ route('events.edit', $event->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <form action="{{ route('events.destroy', $event->id) }}" 
                                        method="POST" 
                                        onsubmit="return confirm('{{ $event->events_count || $event->event ? 'Do you want to delete future recurring events, too?' : trans('global.areYouSure') }}');" style="display: inline-block;"
                                    >
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endif

                            </td>
                            
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
