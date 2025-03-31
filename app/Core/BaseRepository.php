<?php

namespace App\Core;

use App\Interfaces\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\QueryBuilder\QueryBuilder;

class BaseRepository implements Repository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        try {
            return $this->model->all();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param  array  $options  (e.g. ["filter" => ["name" => 'Juan"], "include" => "contravencion"])
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($options = [])
    {
        try {
            if (count($options) > 0) {
                // agrego manualmente al request los filtros, includes,etc
                request()->merge($options);
            }

            // filter, sort, includes
            $q = QueryBuilder::for($this->model)
                ->allowedFilters($this->getFilters())
                ->allowedSorts($this->getSorts())
                ->allowedIncludes($this->getIncludes());

            // paginated
            if (request()->has('page')) {
                $q->limit(1);

                return $q
                    ->paginate(request()->page_size ?? 10)
                    ->withQueryString();
            }


            return $q
                ->limit(request()->limit ?? null)
                ->get();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    // TODO: refactor orrden de parametros ($id, $data)
    public function update(array $data, $id)
    {
        try {
            $result = $this->model::find($id)->update($data);

            if ($result) {
                return $this->model::find($id);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            return $this->model->destroy($id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function find($id, $includes = [])
    {
        // TODO cambiar el parametro includes por options
        // if (count($options) > 0) {
        //     // agrego manualmente al request los filtros, includes,etc
        //     request()->merge($options);
        // }

        $q = QueryBuilder::for($this->model)
            ->allowedIncludes($this->getIncludes());

        if (null == $instance = $q->find($id)) {
            throw new ModelNotFoundException('Object not found');
        }

        if (count($includes)) {
            $instance->load($includes);
        }

        return $instance;
    }

    public function getByIds($ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    /**
     * Devuelve los filtros disponibles:
     *      - Filtros del modelo actual (inyectado)
     *      - Filtros del modelo base
     */
    private function getFilters()
    {
        if (! method_exists($this->model, 'allowedFilters')) {
            return (new BaseModel)->allowedFilters();
        }

        $all_filters = [
            $this->model->allowedFilters() ?? [],
            (new BaseModel)->allowedFilters(),
        ];

        $filters = array_merge([], ...$all_filters);

        return $filters;
    }

    /**
     * Devuelve los sorts del modelo inyectado
     * y los del modelo base
     */
    private function getSorts()
    {
        if (! method_exists($this->model, 'allowedSorts')) {
            return (new BaseModel)->allowedSorts();
        }

        $all_sorts = [
            $this->model->allowedSorts() ?? [],
            (new BaseModel)->allowedSorts(),
        ];

        $sorts = array_merge([], ...$all_sorts);

        return $sorts;
    }

    /**
     * Devuelve los sorts del modelo inyectado
     * y los del modelo base
     */
    private function getIncludes()
    {
        $model_includes = [];

        if (method_exists($this->model, 'allowedIncludes')) {
            $model_includes = $this->model->allowedIncludes();
        }

        $includes = array_merge($model_includes, (new BaseModel)->allowedIncludes());

        return $includes;
    }
}
