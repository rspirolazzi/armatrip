<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Admin\Category;
use App\Repositories\Admin\CategoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CategoryController extends AppBaseController
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->categoryRepository->pushCriteria(new RequestCriteria($request));
        $this->categoryRepository->scopeQuery(function ($query) {
            return $query->where('allowed_to_sell', 0);
        });
        $categories = $this->categoryRepository->paginate(25);

        return view('admin.categories.index')
            ->with('categories', $categories);
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        $order = Category::max('order');
        $order = is_null($order) ? 0 : $order +1;
        return view('admin.categories.create')
            ->with('order', $order)
            ;
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateCategoryRequest $request)
    {
        $input = $request->all();

        $category = $this->categoryRepository->create($input);

        Flash::success('Categoría se guardó con éxito.');

        return redirect(route('admin.categories.index'));
    }

    /**
     * Display the specified Category.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Categoría no encontrada');

            return redirect(route('admin.categories.index'));
        }

        return view('admin.categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Categoría no encontrada');

            return redirect(route('admin.categories.index'));
        }

        return view('admin.categories.edit')
            ->with('category', $category)
            ;
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCategoryRequest $request)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Categoría no encontrada');

            return redirect(route('admin.categories.index'));
        }

        $category = $this->categoryRepository->update($request->all(), $id);

        Flash::success('Categoría se actualizó con éxito.');

        return redirect(route('admin.categories.index'));
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Categoría no encontrada');

            return redirect(route('admin.categories.index'));
        }

        $this->categoryRepository->delete($id);

        Flash::success('Categoría deleted con éxito.');

        return redirect(route('admin.categories.index'));
    }
}
