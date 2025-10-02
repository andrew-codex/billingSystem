@include('includes.sidebar')


<div class="container">
    <div class="permissions-card">
        
      
        <div class="header-actions">
            <a href="{{ route('dashboard.index') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <h2 class="title">
                Edit Permissions for Role: <span>{{ ucfirst($role) }}</span>
            </h2>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('permissions.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="permissions-grid">

    

@foreach($permissions as $permission)
    <label class="permission-item">
      <input type="checkbox" name="permissions[]" value="{{ $permission }}"
    {{ in_array($permission, $savedPermissions ?? []) ? 'checked' : '' }}>
        <span>{{ ucfirst(str_replace('_', ' ', $permission)) }}</span>
    </label>
@endforeach

            </div>

            <div class="btn-container">
                <button type="submit" class="btn-save">
                    Save Permissions
                </button>
            </div>
        </form>
    </div>
</div>

<style>
 .container {
    margin-left: 250px;
    padding: 30px;
}

.permissions-card {
   
    padding: 30px;
    border-radius: 14px;

    width: 100%;
    max-width: 1000px;
}

.title {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
}

.title span {
    color: #2563eb;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    background: #f1f5f9;
    color: #374151;
    font-size: 14px;
    font-weight: 500;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-back:hover {
    background: #e5e7eb;
    color: #111827;
}

.permissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 15px;
}

.permission-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f9fafb;
    padding: 14px 18px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid #e5e7eb;
}

.permission-item:hover {
    background: #f1f5f9;
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0,0,0,0.05);
}

.permission-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #2563eb;
    cursor: pointer;
}

.permission-item span {
    font-size: 14px;
    color: #374151;
    font-weight: 500;
}

.btn-container {
    display: flex;
    justify-content: flex-end;
    margin-top: 25px;
}

.btn-save {
    padding: 12px 20px;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 15px;
}

.btn-save:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(37,99,235,0.3);
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}


</style>