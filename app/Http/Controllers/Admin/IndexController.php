<?php
namespace Corp\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Corp\Http\Requests;
use Corp\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class IndexController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->template = config('settings.theme') . '.admin.index';
    }

    public function index()
    {
        if (Gate::denies('VIEW_ADMIN')) {
            return redirect()->route('forbidden');
        }
        $this->title = 'Панель администратора';
        return $this->renderOutput();
    }
}
