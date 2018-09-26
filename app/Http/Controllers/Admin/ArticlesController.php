<?php
namespace Corp\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Corp\Http\Requests\ArticleRequest;

use Corp\Http\Requests;
use Corp\Http\Controllers\Controller;

use Corp\Repositories\ArticlesRepository;

use Gate;

use Corp\Category;
use Corp\Article;

class ArticlesController extends AdminController
{
    public function __construct(ArticlesRepository $a_rep)
    {
        parent::__construct();
        $this->a_rep = $a_rep;
        $this->template = config('settings.theme') . '.admin.articles';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index()
    {
        if (Gate::denies('VIEW_ADMIN_ARTICLES')) {
            return redirect()->route('forbidden');
        }
        $this->title = 'Менеджер статтей';
        $articles = $this->getArticles();
        $this->content = view(config('settings.theme') . '.admin.articles_content')->with('articles', $articles)->render();
        return $this->renderOutput();
    }

    public function getArticles()
    {
        if (Gate::denies('VIEW_ADMIN_ARTICLES')) {
            return redirect()->route('forbidden');
        }
        return $this->a_rep->get();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function create()
    {
        if (Gate::denies('save', new \Corp\Article)) {
            return redirect()->route('forbidden');
        }
        $this->title = "Добавить новый материал";
        $categories = Category::select(['title', 'alias', 'parent_id', 'id'])->get();
        $lists = array();
        foreach ($categories as $category) {
            if ($category->parent_id == 0) {
                $lists[$category->title] = array();
            } else {
                $lists[$categories->where('id', $category->parent_id)->first()->title][$category->id] = $category->title;
            }
        }
        $this->content = view(config('settings.theme') . '.admin.articles_create_content')->with('categories', $lists)->render();
        return $this->renderOutput();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $result = $this->a_rep->addArticle($request);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }
        return redirect('/admin')->with($result);
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param Article $article
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function edit(Article $article)
    {
        //
        //$article = Article::where('alias', $alias);
        if (Gate::denies('VIEW_ADMIN_ARTICLES')) {
            return redirect()->route('forbidden');
        }
        $article->img = json_decode($article->img);
        $categories = Category::select(['title', 'alias', 'parent_id', 'id'])->get();
        $lists = array();

        foreach ($categories as $category) {
            if ($category->parent_id == 0) {
                $lists[$category->title] = array();
            } else {
                $lists[$categories->where('id', $category->parent_id)->first()->title][$category->id] = $category->title;
            }
        }
        $this->title = 'Реадактирование материала - ' . $article->title;

        $this->content = view(config('settings.theme') . '.admin.articles_create_content')->with(['categories' => $lists, 'article' => $article])->render();

        return $this->renderOutput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    //   articles -> Article
    public function update(ArticleRequest $request, Article $article)
    {
        $result = $this->a_rep->updateArticle($request, $article);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $result = $this->a_rep->deleteArticle($article);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }
}
