<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:app.users.index')->only('index', 'getUsersData');
    }
    /**
     * Muestra la vista de lista de usuarios
     */
    public function index()
    {
        return view('app.users.index');
    }

    /**
     * Retorna los datos para DataTables vía AJAX
     */
    public function getUsersData(Request $request)
    {
        // Parámetros de búsqueda y filtrado
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        // Query base
        $query = User::query();

        // Aplicar búsqueda
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Aplicar filtro de estado
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // Contar total de registros (sin filtros)
        $totalRecords = User::count();

        // Contar registros filtrados
        $filteredRecords = $query->count();

        // Obtener datos paginados
        $users = $query->orderBy('id', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        // Formatear datos para DataTables
        $data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->first()?->name ?? 'Sin rol',
                'status' => $user->status ?? 'inactivo',
                'created_at' => $user->created_at->toISOString(),
            ];
        });

        // Retornar respuesta en formato DataTables
        return response()->json([
            'draw' => intval($request->get('draw', 1)),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Actualizar el estado del usuario
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'status' => 'required|in:activo,inactivo'
            ]);

            $user->status = $request->status;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente',
                'status' => $user->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar estado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'status' => 'required|in:activo,inactivo',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'],
        ]);

        return response()->json(['success' => true, 'user' => $user]);
    }


    public function edit(User $user)
    {
        $roles = Role::all()->pluck('name', 'id');

        $userRoleId = $user->roles->first()?->id ?? null;

        return response()->json([
            'success' => true,
            'user' => $user,
            'roles' => $roles,
            'user_role_id' => $userRoleId
        ]);
    }


    // Actualizar usuario
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:activo,inactivo',
            'password' => 'nullable|string|min:6',     
            'role_id' => 'nullable|exists:roles,id',       
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->status;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

       // Sincronizar rol usando Spatie
    if ($request->role_id) {
        $role = Role::findById($request->role_id);
        $user->syncRoles([$role->name]); // Spatie usa nombres, no IDs
    } else {
        $user->syncRoles([]); // Si no hay rol, quitar todos
    }

        return response()->json(['success' => true]);
    }

    /**
     * Eliminar usuario
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar usuario: ' . $e->getMessage()
            ], 500);
        }
    }
}
