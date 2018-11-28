<?php
namespace Corp\Http\Controllers\Admin;

use Corp\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Lavary\Menu\Menu;

class AdminController extends Controller
{
    protected $p_rep;
    protected $a_rep;
    protected $user;
    protected $template;
    protected $content = FALSE;
    protected $title;
    protected $vars;

    public function __construct()
    {
//        $this->user = Auth::user();
//
//        if (!$this->user) {
//            abort(403);
//        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function renderOutput()
    {
        $this->vars = array_add($this->vars, 'title', $this->title);

        $menu = $this->getMenu();
        $navigation = view(config('settings.theme') . '.admin.navigation')->with('menu', $menu)->render();
        $this->vars = array_add($this->vars, 'navigation', $navigation);

        if ($this->content) {
            $this->vars = array_add($this->vars, 'content', $this->content);
        }

        $footer = view(config('settings.theme') . '.admin.footer')->render();

        $this->vars = array_add($this->vars, 'footer', $footer);
        return view($this->template)->with($this->vars);
    }

    public function getMenu()
    {
        $m = new Menu();
        return $m->make('adminMenu', function ($menu) {
            $menu->add('Статьи', array('route' => 'admin.articles.index'));
            $menu->add('Меню', array('route' => 'admin.menus.index'));
            $menu->add('Пользователи', array('route' => 'admin.users.index'));
            $menu->add('Привилегии', array('route' => 'admin.permissions.index'));
        });
    }
}
