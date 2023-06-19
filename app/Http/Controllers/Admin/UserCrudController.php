<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Requests\UserUpdateRequest;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation{store as traitStore;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation{update as traitUpdate;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('email');
        CRUD::column('role');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);

        CRUD::field('name');
        CRUD::field('email');
        CRUD::field('password');
        CRUD::addField([
            'name' => 'role',
            'type' => 'select_from_array',
            'options' => [
                'user' => 'User',
                'admin' => 'Admin',
            ],
            'allow_null' => false,
            'default' => 'user',
            'attributes' => [
                'placeholder' => 'Select User'
            ]
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
        CRUD::setValidation(UserUpdateRequest::class);
    }

    protected function store()
    {
        //before save
        $request = $this->crud->getRequest()->all();
        // dd($request);
       
        $response = $this->traitStore(); // actual save

        // after save
        $user_id = (backpack_user()->id) ?? null;
        $entry = $this->crud->entry;

        $entry->user_id = $user_id;
        if(!empty(@$request['password'])){
            $entry->password = bcrypt($request['password']);
        }
        $entry->save();

        return $response;
    }

    protected function update()
    {
        //before update
        $request = $this->crud->getRequest()->all();
        // dd($request);
        
        $user_id = (backpack_user()->id) ?? null;
        $password = null;
        if(!empty(@$request['password'])){
            $password = $request['password'];
        }
        $this->crud->getRequest()->request->remove('password');


        $response = $this->traitUpdate(); // actual update
        
        
        //after update
        $entry = $this->crud->entry;
        // dd($entry);
        $entry->user_id = $user_id;
        if(!empty($password)){
            $entry->password = bcrypt($password);
        }
        $entry->save();
        $request = $this->crud->getRequest()->all();
        // dd($request);

        return $response;
    }
}
