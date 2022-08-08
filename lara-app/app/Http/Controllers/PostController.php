<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Post;
use App\Jobs\ProcessPost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * Получает список полей таблицы posts
     *
     * @return array
     */
    private function getPostsColumns(): array
    {
        $product = new Post;
        return $product->getTableColumns();
    }

    /**
     * @param Request $request
     * @return mixed
     * @property integer $limit get-параметр устанавливает количество элементов для постраничной разбивки
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit') ?? false;

        $collection = new Post();
        if ($request->get('sort')) {
            $collection = ($request->get('by') == 'desc') ?
                $collection->orderByDesc($request->get('sort')) :
                $collection->orderBy($request->get('sort'));
        }

        $collection = $collection->with('author'); //все записи
        foreach ($request->toArray() as $filterKey => $filterValue) {
            if (  // фильтруем записи если переданный параметр есть в заголовке столбцов таблицы
                $filterValue &&
                in_array($filterKey, $this->getPostsColumns())
            ) {
                $collection = $collection->where($filterKey, $filterValue);
            }
        }
        if ($limit) {
            $collection = $collection->paginate($limit);
        } else {
            $collection = $collection->get();
        }

        return $collection;
    }

    /**
     * @param $id
     * @return Model
     */
    public function show($id): Model
    {
        return Post::with('author')->findOrFail($id);
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return PendingDispatch
     */
    private function postSetAttributes(Post $post, Request $request): PendingDispatch
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'author_email' => ['required', 'email'],
        ]);

        $post->author_id = Author::firstOrCreate(
            ['email' => $validatedData['author_email']],
            ['author' => $validatedData['author_email']]
        )
            ->id;
        $post->title = $validatedData['title'];
        $post->description = $validatedData['description'] ?? "";
        return ProcessPost::dispatch($post)->onConnection('database');
    }

    /**
     * @param Request $request
     * @return bool|void
     */
    public function store(Request $request)
    {
        $post = new Post;
        if ($this->postSetAttributes($post, $request)) {
            return true;
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return bool|void
     */
    public function update(Request $request, int $id)
    {
        $post = Post::findOrFail($id);
        if ($this->postSetAttributes($post, $request)) {
            return true;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function destroy($id): bool
    {
        return Post::destroy($id) ?? false;
    }
}
