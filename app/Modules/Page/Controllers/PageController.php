<?php

namespace App\Modules\Page\Controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Modules\Page\Models\PageModel;

class PageController extends Controller
{   
    protected PageModel $pagesModel;

    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->pagesModel = new PageModel($this->app->getDatabase());
    }

    public function index(string $slug): string
    {
        return $this->viewWithLayout('index', 'layout', [
            'title' => 'Actualités - Gitopedia',
            'page' => $this->pagesModel->getBySlug($slug) 
        ]);
    }

    private function titleToSlug(string $title): string
    {
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $title);

        $slug = strtolower($slug);

        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        $slug = trim($slug, '-');

        return $slug;
    }
}