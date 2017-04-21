@if((sizeof($files) > 0) || (sizeof($directories) > 0))
<table class="table table-responsive table-condensed table-striped hidden-xs">
  <thead>
    <th style='width:50%;'>{{ Lang::get('laravel-filemanager::lfm.title-item') }}</th>
    <th>{{ Lang::get('laravel-filemanager::lfm.title-size') }}</th>
    <th>{{ Lang::get('laravel-filemanager::lfm.title-type') }}</th>
    <th>{{ Lang::get('laravel-filemanager::lfm.title-modified') }}</th>
    <th>{{ Lang::get('laravel-filemanager::lfm.title-action') }}</th>
  </thead>
  <tbody>
    @foreach($items as $item)
    <tr>
      <td>
        <i class="fa {{ $item->icon }}"></i>
        @if(!$item->is_file)
        <a class="folder-item clickable" data-id="{{ $item->path }}">
        @else
        <a href="javascript:useFile('{{ $item->name }}')" id="{{ $item->name }}" data-url="{{ $item->url }}">
        @endif
          {{ str_limit($item->name, $limit = 20, $end = '...') }}
        </a>
      </td>
      <td>{{ $item->size }}</td>
      <td>{{ $item->type }}</td>
      <td>{{ $item->time }}</td>
      <td>
        @if($item->is_file)
        <a href="javascript:trash('{{ $item->name }}')">
          <i class="fa fa-trash fa-fw"></i>
        </a>
        @if($item->thumb)
        <a href="javascript:cropImage('{{ $item->name }}')">
          <i class="fa fa-crop fa-fw"></i>
        </a>
        <a href="javascript:resizeImage('{{ $item->name }}')">
          <i class="fa fa-arrows fa-fw"></i>
        </a>
        @endif
        @endif
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

<table class="table visible-xs">
  <tbody>
    @foreach($items as $item)
    <tr>
      <td>
        <div class="media" style="height: 70px;">
          <div class="media-left">
            <div class="clickable thumbnail-mobile">
              @if(!$item->is_file)
              <div class="square folder-item" data-id="{{ $item->path }}">
              @else
              <div class="square" id="{{ $item->name }}" data-url="{{ $item->url }}">
              @endif
                @if($item->thumb)
                <img src="{{ $item->thumb }}">
                @else
                <div class="icon-container">
                  <i class="fa {{ $item->icon }} fa-5x"></i>
                </div>
                @endif
              </div>
            </div>
          </div>
          <div class="media-body" style="padding-top: 10px;">
            <div class="media-heading">
              <p>
                @if(!$item->is_file)
                <a class="folder-item clickable" data-id="{{ $item->path }}">
                @else
                <a href="javascript:useFile('{{ $item->name }}')" id="{{ $item->name }}" data-url="{{ $item->url }}">
                @endif
                  {{ str_limit($item->name, $limit = 20, $end = '...') }}
                </a>
                &nbsp;&nbsp;
                {{-- <a href="javascript:rename('{{ $item->name }}')">
                  <i class="fa fa-edit"></i>
                </a> --}}
              </p>
            </div>
            <p style="color: #aaa;font-weight: 400">{{ $item->time }}</p>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@else
<p>{{ trans('laravel-filemanager::lfm.message-empty') }}</p>
@endif
