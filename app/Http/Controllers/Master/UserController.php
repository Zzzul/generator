<?php

namespace App\Http\Controllers\Master;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\{StoreUserRequest, UpdateUserRequest};
use Yajra\DataTables\Facades\DataTables;
use Image;

class UserController extends Controller
{
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
                ->addColumn('action', 'master-data.user.include.action')
                ->addColumn('photo', function ($row) {
                    if ($row->photo == null) {
                        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(auth()->user()->email))) . '&s=500';
                    }

                    return asset('uploads/images/' . $row->photo);
                })
                ->toJson();
        }

        return view('master-data.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master-data.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $filename = null;

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
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'photo' => $filename
        ]);

        return redirect()
            ->route('user.index')
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
        return view('master-data.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('master-data.user.edit', compact('user'));
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

        return redirect()
            ->route('user.index')
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
        $user->delete();

        return redirect()
            ->route('user.index')
            ->with('success', trans('User deleted successfully.'));
    }
}
