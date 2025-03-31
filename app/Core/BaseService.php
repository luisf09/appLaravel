<?php

namespace App\Core;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Core\BaseRepository;
use App\Interfaces\Service;

class BaseService implements Service
{
    /**
    * @var $repository
    */
    protected $repository;

    /**
    * BaseRepository constructor.
    *
    * @param BaseRepository $repository
    */
    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
    * Get all.
    *
    * @return String
    */
    public function all()
    {
        return $this->repository->all();
    }

    /**
    * Get by id.
    *
    * @param $id
    * @return String
    */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
    *
    * @param array $data
    * @return String
    */
    public function create($data)
    {
        $result = $this->repository->create($data);
        return $result;
    }

    /**
    * Update data
    *
    * @param array $data
    * @return String
    */
    public function update($data, $id)
    {
        DB::beginTransaction();

        try {
            $result = $this->repository->update($data, $id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('No se pueden actualizar los datos');
        }

        DB::commit();

        return $result;
    }

    /**
    * Delete by id.
    *
    * @param $id
    * @return String
    */
    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $result = $this->repository->delete($id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('No se pueden eliminar');
        }

        DB::commit();

        return $result;
    }
}
