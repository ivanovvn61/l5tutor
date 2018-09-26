<?php

namespace Corp\Http\Controllers;

use Illuminate\Http\Request;

use Corp\Http\Requests;

use Corp\Repositories\MenusRepository;
use Lavary\Menu\Menu;


class SiteController extends Controller
{
    protected $p_rep;
    protected $s_rep;
    protected $a_rep;
    protected $m_rep;
    protected $c_rep;
    protected $keywords;
    protected $meta_desc;
    protected $title;
    protected $temlate;
    protected $vars = array();
    protected $contentRightBar = false;
    protected $contentLeftBar = false;
    protected $bar = 'no';

    public function __construct(MenusRepository $m_rep)
    {
        $this->m_rep = $m_rep;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    protected function renderOutput()
    {
        $menu = $this->getMenu();
        //$r = $menu->roots();
        $navigation = view(config('settings.theme') . '.navigation')->with('menu', $menu)->render();
        $this->vars = array_add($this->vars, 'navigation', $navigation);
        if ($this->contentRightBar) {
            $rightBar = view(config('settings.theme') . '.rightBar')->with('content_rightBar', $this->contentRightBar)->render();
            $this->vars = array_add($this->vars, 'rightBar', $rightBar);
        }
        if ($this->contentLeftBar) {
            $leftBar = view(config('settings.theme') . '.leftBar')->with('content_leftBar', $this->contentLeftBar)->render();
            $this->vars = array_add($this->vars, 'leftBar', $leftBar);
        }
        $this->vars = array_add($this->vars, 'bar', $this->bar);
        $this->vars = array_add($this->vars, 'keywords', $this->keywords);
        $this->vars = array_add($this->vars, 'meta_desc', $this->meta_desc);
        $this->vars = array_add($this->vars, 'title', $this->title);
        $footer = view(config('settings.theme') . '.footer')->render();
        $this->vars = array_add($this->vars, 'footer', $footer);
        return view($this->template)->with($this->vars);
    }

    /**
     * @return mixed
     */
    public function getMenu()
    {
        $menu = $this->m_rep->get();

        $m = new Menu();
        $mBuilder = $m->make('MyNav', function ($m) use ($menu) {
            foreach ($menu as $item) {
                if ($item->parent == 0) {
                    $m->add($item->title, env('APP_URL', '') . $item->path)->id($item->id);
                } else {
                    if ($m->find($item->parent)) {
                        $m->find($item->parent)->add($item->title, env('APP_URL', '') . $item->path)->id($item->id);
                    }
                }
            }
        });
        return $mBuilder;
    }
}
