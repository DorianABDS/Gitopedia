<?php

namespace App\Modules\Page\Models;

use App\Core\Model;
use App\Core\Database;

class PageModel extends Model
{
    protected string $table = "pages";
    protected string $primaryKey = "id";

    public function __construct(Database $db) 
    {
        parent::__construct($db);
    }

    public function getBySlug(string $slug): array
    {
        return $this->firstWhere(["slug"=> $slug, "status" => "published"]);
    }
}