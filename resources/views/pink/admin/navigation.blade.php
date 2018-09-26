<div class="extra-content">
@if(Auth::check())
    <div class="menu classic right">
        <ul id="nav" class="menu">
            <li><a class="login" href="#">Пользователь: {{  Auth::user()->name }}</a>
                <ul class="sub-menu" style="display: none;">
                    <li> <a onclick="document.getElementById('logout-form').submit(); return false;" href="#">Выход</a>
                        <form id="logout-form" style="display:none" action="{{ action("Auth\AuthController@logout") }}" method="GET"></form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
@endif

@if ($menu)
    <div class="menu classic left">
        {!! $menu->asUl(['class'=>'menu']) !!}
    </div>
@endif
</div>