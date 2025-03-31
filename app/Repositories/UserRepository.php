<?php

namespace App\Repositories;

use App\Models\User;
use App\Core\BaseRepository;
use App\Interfaces\Repository;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository implements Repository
{
    protected $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        // encriptar password
        $data['password'] = bcrypt($data['password']);

        // guardar usuario
        $user = $this->model->create($data);

        // asociar unidad
        // $user->unidades()->attach($data['unidad_id']);

        DB::commit();

        return $user;
    }
}
