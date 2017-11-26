<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\PermissionsRepository;
use App\Repositories\RolesRepository;
use Illuminate\Http\Request;

class PermissionsController extends AdminController
{
    protected $perm_rep;
    protected $rol_rep;

    public function __construct(PermissionsRepository $permissionsRepository, RolesRepository $rolesRepository)
    {
        parent::__construct();
        $this->perm_rep = $permissionsRepository;
        $this->rol_rep = $rolesRepository;

        $this->template = env('THEME').'.admin.permissions';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (\Gate::denies('EDIT_USERS')) {
            abort(403);
        }
        $this->title = 'Менеджер прав';
        $roles = $this->getRoles();
        $permissions = $this->getPermissions();

        $this->content = view(env('THEME').'.admin.permissions_content')->with(['roles' => $roles, 'priv' => $permissions]);
        return $this->renderOutput();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $result = $this->perm_rep->changePermissions($request);
//        dd($result);
//        if (is_array($result) && !empty($result['error'])){
//            return back()->with($result);
//        }
        return back()->with($result);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getRoles()
    {
        $roles = $this->rol_rep->get();
        return $roles;

    }

    private function getPermissions()
    {
        $permissions = $this->perm_rep->get();
        return $permissions;
    }
}
