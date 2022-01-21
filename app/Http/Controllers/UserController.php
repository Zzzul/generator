<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreUserRequest, UpdateUserRequest};
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view user')->only('index', 'show');
        $this->middleware('permission:create user')->only('create', 'store');
        $this->middleware('permission:edit user')->only('edit', 'update');
        $this->middleware('permission:delete user')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $users = User::query();

            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('action', 'users.include.action')
                ->addColumn('photo', function ($row) {
                    if ($row->photo == null) {
                        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($row->email))) . '&s=500';
                    }

                    return asset('uploads/images/' . $row->photo);
                })
                ->toJson();
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $attr = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ];

        if ($request->file('photo') && $request->file('photo')->isValid()) {

            $filename = time() . '.' . $request->file('photo')->getClientOriginalExtension();

            $destination = 'uploads/images/';

            if (!file_exists($path = public_path($destination))) {
                mkdir($path, 0777, true);
            }

            Image::make($request->file('photo')->getRealPath())->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destination . $filename);

            $attr['photo'] = $filename;
        }

        $user = User::create($attr);

        $user->assignRole($request->role);

        return redirect()
            ->route('users.index')
            ->with('success', trans('User created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $attr = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->file('photo') && $request->file('photo')->isValid()) {

            $filename = time() . '.' .  $request->file('photo')->getClientOriginalExtension();

            $destination = 'uploads/images/';

            // if folder dont exist, then create folder
            if (!file_exists($path = public_path($destination))) {
                mkdir($path, 0777, true);
            }

            // Intervention Image
            Image::make($request->file('photo')->getRealPath())->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destination . $filename);

            // delete old photo from storage
            if ($user->photo != null && file_exists(public_path("/uploads/images/$user->photo"))) {
                unlink(public_path("/uploads/images/$user->photo"));
            }

            $attr['photo'] = $filename;
        }

        if ($request->password) {
            $attr['password'] = bcrypt($request->password);
        }

        $user->update($attr);

        $user->syncRoles($request->role);

        return redirect()
            ->route('users.index')
            ->with('success', trans('User updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->photo != null && file_exists(public_path("/uploads/images/$user->photo"))) {
            unlink(public_path("/uploads/images/$user->photo"));
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', trans('User deleted successfully.'));
    }
}
