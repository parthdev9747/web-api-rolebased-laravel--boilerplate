<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FrontendUserRequest;
use App\Http\Resources\FrontendResource;
use App\Models\FrontendUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FrontendUserController extends Controller
{

    public function __construct(public FrontendUser $model)
    {
        $this->moduleName = 'User';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $frontendUserList = FrontendUser::all();
        $request->merge(['include_id' => true]);
        $response = [
            'message' => $this->moduleName . ' fetched successfully',
            'users' => FrontendResource::collection($frontendUserList)
        ];
        return $this->showMessage($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FrontendUserRequest $request)
    {
        $validated = $request->validated();
        try {
            $validated['password'] = Hash::make($validated['password']);
            $user = $this->model->create($validated);
            $response = [
                'message' => $this->moduleName . ' created successfully',
                'user' => new FrontendResource($user)
            ];
            return $this->showMessage($response, 200);
        } catch (\Exception $e) {
            \Log::error('Error while creating user: ' . $e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = FrontendUser::find($id);
        if (!$user) {
            return $this->error("User not found!", 500);
        }

        $response = [
            'message' => $this->moduleName . ' fetched successfully',
            'user' => new FrontendResource($user)
        ];
        return $this->showMessage($response, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FrontendUserRequest $request, FrontendUser $frontendUser)
    {

        $validatedData = $request->validated();
        try {
            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            $frontendUser->update($validatedData);
            $updatedRecord = $frontendUser->fresh();
            $response = [
                'message' => $this->moduleName . ' updated successfully',
                'user' => new FrontendResource($updatedRecord)
            ];
            return $this->showMessage($response, 200);
        } catch (\Exception $e) {
            \Log::error('Error while updating user: ' . $e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FrontendUser $frontendUser)
    {
        try {
            $frontendUser->delete();
            $response = [
                'message' => $this->moduleName . ' deleted successfully',
            ];
            return $this->showMessage($response, 200);
        } catch (\Exception $e) {
            \Log::error('Error while deleting user: ' . $e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }
}
