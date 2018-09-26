@extends(config('settings.theme') . '.layouts.site')

{{--@section('navigation')--}}
    {{--{!! $navigation !!}--}}
{{--@endsection--}}

@section('content')
    <div id="content-index" class="content group">
        <div class="error-404-text group">
            <p>У вас нет прав на совершение данной операции.</p>
        </div>
    </div>
@endsection

@section('footer')
    @include(config('settings.theme').'.footer')
@endsection