<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gerenciar Usuários</h2>
  </x-slot>
  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded">{{ session('success') }}</div>
      @endif
      <table class="w-full bg-white shadow rounded">
        <thead>
          <tr class="border-b">
            <th class="p-3 text-left">Nome</th>
            <th class="p-3 text-left">Email</th>
            <th class="p-3 text-left">Role</th>
            <th class="p-3 text-left">Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
            <tr class="border-b">
              <td class="p-3">{{ $user->name }}</td>
              <td class="p-3">{{ $user->email }}</td>
              <td class="p-3">
                <form method="POST" action="{{ route('users.update.role', $user) }}">
                  @csrf
                  @method('PATCH')
                  <select name="role" onchange="this.form.submit()" class="border rounded pl-2 pr-6 py-1">
                    <option value="viewer" @selected($user->role === 'viewer')>viewer</option>
                    <option value="editor" @selected($user->role === 'editor')>editor</option>
                    <option value="admin" @selected($user->role === 'admin')>admin</option>
                  </select>
                </form>
              </td>
              <td class="p-3">
                <form method="POST" action="{{ route('users.destroy', $user) }}">
                  @csrf
                  @method('DELETE')
                  <button onclick="return confirm('Apagar usuário?')" class="text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                    </svg>
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</x-app-layout>
