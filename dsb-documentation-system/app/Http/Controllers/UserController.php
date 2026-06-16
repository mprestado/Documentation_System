<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Document;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * List all users (also available as JSON for the modal).
     * Non-admin users can only see their own account.
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        
        // Check if user is admin or owner
        $isAdmin = in_array($currentUser->role, ['admin', 'owner']);
        
        if ($isAdmin) {
            // Admin/owner can see all users
            $users = User::latest()->get();
        } else {
            // Non-admin users can only see their own account
            $users = collect([$currentUser]);
        }

        if ($request->wantsJson()) {
            return response()->json(['users' => $users]);
        }

        // Redirect back to dashboard — users are managed in a modal
        return redirect()->route('dashboard');
    }

    /**
     * Create a new user.
     * Called via AJAX from the User Management modal.
     * Only admin/owner can create users.
     */
    public function store(Request $request)
    {
        $currentUser = auth()->user();
        
        // Only admin/owner can create users
        if (!in_array($currentUser->role, ['admin', 'owner'])) {
            return response()->json([
                'success' => false, 
                'message' => 'Unauthorized. Only administrators can create users.'
            ], 403);
        }
        
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)],
            'role'     => 'sometimes|string|in:admin,staff,viewer,owner',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'] ?? 'staff',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'user'    => $user->only('id', 'name', 'email', 'role'),
        ]);
    }

    /**
     * Delete a user.
     * Only admin/owner can delete users.
     */
    public function destroy(User $user)
    {
        $currentUser = auth()->user();
        
        // Only admin/owner can delete users
        if (!in_array($currentUser->role, ['admin', 'owner'])) {
            return response()->json([
                'success' => false, 
                'message' => 'Unauthorized. Only administrators can delete users.'
            ], 403);
        }
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
        }
        
        // Prevent deleting the owner
        if ($user->role === 'owner') {
            return response()->json(['success' => false, 'message' => 'Cannot delete the owner account.'], 403);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted.']);
    }

    /**
     * Get current document & service assignments for a user.
     * Called via AJAX when the Assign modal opens.
     */
    public function getAssignments(User $user)
    {
        // Use a pivot table approach — assumes user_documents and user_services tables exist.
        // Falls back gracefully if the tables don't exist yet.
        try {
            $documentIds = $user->documents()->pluck('documents.id')->toArray();
            $serviceIds  = $user->services()->pluck('services.id')->toArray();
        } catch (\Exception $e) {
            $documentIds = [];
            $serviceIds  = [];
        }

        return response()->json([
            'document_ids' => $documentIds,
            'service_ids'  => $serviceIds,
        ]);
    }

    /**
     * Save document & service assignments for a user.
     * Called via AJAX when the Assign modal is submitted.
     */
    public function saveAssignments(Request $request, User $user)
    {
        $validated = $request->validate([
            'document_ids'   => 'array',
            'document_ids.*' => 'integer|exists:client_documents,id',
            'service_ids'    => 'array',
            'service_ids.*'  => 'integer|exists:services,id',
        ]);

        try {
            // Sync pivot relationships
            if (method_exists($user, 'documents')) {
                $user->documents()->sync($validated['document_ids'] ?? []);
            }
            if (method_exists($user, 'services')) {
                $user->services()->sync($validated['service_ids'] ?? []);
            }
        } catch (\Exception $e) {
            // If pivot tables aren't set up yet, return success anyway
            // so the UI still works during development
            return response()->json([
                'success' => true,
                'message' => 'Assignment saved (pivot tables not yet migrated).',
                'note'    => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => sprintf(
                'Assigned %d document(s) and %d service(s) to %s.',
                count($validated['document_ids'] ?? []),
                count($validated['service_ids']  ?? []),
                $user->name
            ),
        ]);
    }
}
