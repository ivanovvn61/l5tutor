<?php

namespace Corp\Http\Controllers;

use Illuminate\Http\Request;

use Corp\Http\Requests;

use Corp\Repositories\PortfoliosRepository;

class PortfolioController extends SiteController
{
    public function __construct(PortfoliosRepository $p_rep)
    {
        parent::__construct(new \Corp\Repositories\MenusRepository(new \Corp\Menu));
        $this->p_rep = $p_rep;
        $this->template = config('settings.theme') . '.portfolios';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index()
    {
        $this->title = 'Портфолио';
        $this->keywords = 'Портфолио';
        $this->meta_desc = 'Портфолио';
        $portfolios = $this->getPortfolios();
        $content = view(config('settings.theme') . '.portfolios_content')->with('portfolios', $portfolios)->render();
        $this->vars = array_add($this->vars, 'content', $content);
        return $this->renderOutput();
    }

    public function getPortfolios($take = FALSE, $paginate = TRUE)
    {
        $portfolios = $this->p_rep->get('*', $take, $paginate);
        if ($portfolios) {
            $portfolios->load('filter');
        }
        return $portfolios;
    }

    /**
     * @param $alias
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function show($alias)
    {
        $portfolio = $this->p_rep->one($alias);
        $portfolios = $this->getPortfolios(config('settings.other_portfolios'), FALSE);
        $this->title = $portfolio->title;
        $this->keywords = $portfolio->keywords;
        $this->meta_desc = $portfolio->meta_desc;
        $content = view(config('settings.theme') . '.portfolio_content')->with(['portfolio' => $portfolio, 'portfolios' => $portfolios])->render();
        $this->vars = array_add($this->vars, 'content', $content);
        return $this->renderOutput();
    }
}
